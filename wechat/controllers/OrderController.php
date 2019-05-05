<?php
namespace wechat\controllers;


class OrderController extends CommonController
{
    public $is_need_login = true;
    /**
     * 用户模型
     * @var \common\models\User
     * */
    protected $user_model;

    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
        $this->user_model = \common\models\User::findOne($this->user_id);
        if (empty($this->user_model)) {
            $this->user_id = 0;
        }
    }

    //订单列表
    public function actionIndex()
    {
        $state = $this->request->get('state');
        return $this->render('index', [
            'state' => $state
        ]);
    }

    public function actionShowList()
    {
        $state = $this->request->get('state');
        $where['uid'] = $this->user_id;
        if ($state == 1) { //待付款
            $where['status'] = 0;
        } elseif ($state == 2) {//生产中
            $where['is_produce'] = 1;
        } elseif ($state == 3) {//待发货
            $where['is_send'] = 1;
        } elseif ($state == 4) {//待收货
            $where['is_receive'] = 1;
        } elseif ($state == 5) {//已完成
            $where['status'] = 3;
        }


        $query = \common\models\Order::find()->with(['linkOrderGoods'])->where($where)->orderBy('id desc');
        $data = [];
        foreach ($query->each() as $vo) {
            $current_step_info = $vo->getOrderStatusInfo();
            //可以处理的事件
            $handle = isset($current_step_info['handle']) ? $current_step_info['handle'] : [];
            $sure_handle = [];
            foreach ($handle as $key => $hl) {
                if (is_array($hl)) {
                    foreach ($hl as $con_key => $con_val) {
                        if (!empty($vo[$con_key]) && $vo[$con_key] == $con_val) {
                            $sure_handle[] = $key;
                        }
                    }
                } else {
                    $sure_handle[] = $hl;
                }
            }
            $info = [
                'id' => $vo['id'],
                'no' => $vo['no'],
                'step_info_name' => $current_step_info['name'],
                'goods_num' => 0,
                'pay_money' => $vo['pay_money'],
                'handle' => $sure_handle,
                'goods' => [],
            ];
            foreach ($vo['linkOrderGoods'] as $item) {
                $info['goods_num'] += $item['num'];
                $info['goods'][] = [
                    'gid' => $item['gid'],
                    'money' => $item['per_price'],
                    'num' => $item['num'],
                    'name' => $item['name'],
                    'cover_img' => \common\models\Goods::getCoverImg($item['img']),
                ];
            }
            $data[] = $info;
        }
        return $this->asJson(['code' => 1, 'msg' => '获取成功', 'data' => $data]);
    }

    public function actionInfo($is_return = false)
    {
        $addr_id = $this->request->isGet ? $this->request->get('addr_id') : $this->request->post('addr_id');
        $channel = $this->request->isGet ? $this->request->get('channel') : $this->request->post('channel');
        $gid = $this->request->isGet ? $this->request->get('gid') : $this->request->post('gid');
        $num = $this->request->isGet ? $this->request->get('num', 1) : $this->request->post('num', 1);

        //地址
        $addr_where['uid'] = $this->user_id;
        !empty($addr_id) && $addr_where['id'] = $addr_id;
        $model_addr = \common\models\UserAddr::find()->asArray()->where($addr_where)->orderBy('is_default desc, id desc')->one();

        $model = new \common\models\Order();
        //详情渠道
        $channel && $model->check_channel = $channel;
        //检出订单信息
        list($goods_info, $money) = $model->checkOrderInfo($this->user_model, $gid, $num);
        /**/
        if ($is_return) {
            return [$model, $goods_info, $money, $model_addr];
        }

        //支付方式
//        $this->renderPartial()
        $pay_way = \common\models\Order::getPayWay();
        return $this->render('info', [
            'gid' => $gid,
            'num' => $num,
            'channel' => $channel,
            'model_addr' => $model_addr,
            'goods_info' => $goods_info,
            'money' => $money,
            'pay_way' => $pay_way,
            'user_model' => $this->user_model,
        ]);
    }

    //订单地址
    public function actionConfirm()
    {
        //合同信息
        $contract = $this->request->post('contract');
        //订单数据
        list($order_model, $goods_info, $money, $model_addr) = $this->actionInfo(true);
        try {
            $order_model->confirm($this->user_model, $goods_info, $money, $model_addr, $contract);
        } catch (\Exception $e) {
            throw new \yii\base\UserException($e->getMessage());
        }
        return $this->asJson(['code' => 1, 'msg' => '创建订单成功', 'url' => \yii\helpers\Url::to(['order/detail', 'id' => $order_model->id])]);
    }

    //订单详情
    public function actionDetail()
    {
        $id = $this->request->get('id');

        $model = \common\models\Order::find()->with(['linkOrderAddr', 'linkOrderGoods', 'linkOrderContract'])->where(['id' => $id])->one();
        //获取当前订单状态信息
        $current_step_info = null;
        $model && $current_step_info = $model->getOrderStatusInfo();
        return $this->render('detail', [
            'model' => $model,
            'current_step_info' => $current_step_info,
            'user_model' => $this->user_model,
        ]);
    }

    //订单物流信息
    public function actionLogistics()
    {
        $id = $this->request->get('id');

        $model = \common\models\Order::find()->with(['linkOrderLogistics','linkOrderLogs'=>function($query){
            return $query->andWhere(['is_show_user'=>1]);
        }])->where(['id' => $id])->one();
        return $this->render('logistics',[
            'model' => $model
        ]);
    }

    //删除订单
    public function actionDel()
    {
        $id = $this->request->get('id');
        $model = \common\models\Order::find()->where(['id'=>$id,'uid'=>$this->user_id])->one();
        if(empty($model)) throw new \yii\base\UserException('删除对象异常');
        try{
            $model->del();
        }catch (\Exception $e){
            throw new \yii\base\UserException($e->getMessage());
        }
        return $this->asJson(['code'=>1,'msg'=>'删除成功']);
    }

    //订单收货
    public function actionReceive()
    {
        $id = $this->request->get('id');
        try{
            \common\models\Order::receive($id);
        }catch (\Exception $e){

        }
        return $this->asJson(['code'=>1,'msg'=>'操作成功']);
    }

}