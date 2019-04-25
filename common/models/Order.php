<?php
namespace common\models;


use common\models\use_traits\SoftDelete;

class Order extends BaseModel
{
    use SoftDelete;
    public $check_is_cart = false;
    public static function tableName()
    {
        return '{{%order}}';
    }


    //支付方式
    public static function getPayWay($type=null)
    {
        $data = [
            ['name'=>'货到付款'],
        ];
        if(is_null($type)){
            return $data;
        }else{
            $info = isset($data[$type])?$data[$type]:[];
            return $info;
        }
    }

    /**
     * 检出订单信息
     * @param $user_model User 用户模型
     * @param $id int 商品id
     * @param $num int 购买数量
     * @return  array
     * */
    public function checkOrderInfo(User $user_model,$id=0,$num=1)
    {
        //商品数据
        $gid = $goods_data =[];
        if($this->check_is_cart){
            //购物车过来
            $cart_info = UserCart::find()->asArray()->where(['uid'=>$user_model->id,'is_checked'=>1])->all();
            $gid = array_column($cart_info,null,'gid');
            foreach($cart_info as $vo) {
                if(array_key_exists($vo['gid'],$gid)){
                    $gid[$vo['gid']] +=$vo['num'];
                }else{
                    $gid[$vo['gid']] =$vo['num'];
                }
            }
        }else{
            //指定商品
            $gid[$id] = $num;
        }
        //所有商品数据
        $goods_ids = array_keys($gid);
        //商品数据
        $goods_info = Goods::find()->asArray()->where(['id'=>$goods_ids])->all();

        foreach ($goods_info as $vo){
            if(isset($gid[$vo['id']])){
                $vo['buy_num'] = $gid[$vo['id']];
                $goods_data[]=  $vo;
            }
        }
        //计算金额相关数据
        $money = [
            'money' => 0.00 ,//商品总金额
            'pay_money' => 0.00 ,//实际支付总金额
            'freight_money' => 0.00 ,//运费金额
            'taxation_money' => 0.00 ,//税费总金额
        ];
        foreach ($goods_data as $vo){
            $goods_price = $vo['price']*$vo['buy_num']; // 购买金额
            $freight_money = $vo['freight_money']*$vo['buy_num']; // 运费金额
            $taxation_money = $vo['taxation_money']*$vo['buy_num']; // 税费金额
            $money['money'] += $goods_price+$freight_money+$taxation_money;
            $money['pay_money'] += $goods_price+$freight_money+$taxation_money;
            $money['freight_money'] += $freight_money;
            $money['taxation_money'] += $taxation_money;
        }
        //强转2位小数
        foreach ($money as &$vo){
            $vo = sprintf('%.2f',$vo);
        }

        return [$goods_data,$money];
    }


    //确认订单
    public function confirm(User $model_user,$goods_info,$money,$model_addr,$contract)
    {

        //订单数据
        $model_order = new self();
        $model_order->no = self::getNo();
        $model_order->uid = $model_user->id;
        $model_order->money = !empty($money['money'])?$money['money']:0.00;
        $model_order->pay_money = !empty($money['pay_money'])?$money['pay_money']:0.00;
        $model_order->freight_money = !empty($money['freight_money'])?$money['freight_money']:0.00;
        $model_order->taxation_money = !empty($money['taxation_money'])?$money['taxation_money']:0.00;
        $model_order->taxation_money = !empty($money['taxation_money'])?$money['taxation_money']:0.00;

        try{
            $transaction = self::getDb()->beginTransaction();
            //保存订单信息
            $model_order->save(false);
            //保存收货地址
            $model_order_addr = new OrderAddr();
            $model_order_addr->oid=$model_order->id;
            $model_order_addr->phone=!empty($model_addr['phone'])?$model_addr['phone']:'';
            $model_order_addr->username=!empty($model_addr['username'])?$model_addr['username']:'';
            $model_order_addr->addr=!empty($model_addr['addr'])?$model_addr['addr']:'';
            $model_order_addr->addr_extra=!empty($model_addr['addr_extra'])?$model_addr['addr_extra']:'';
            $model_order_addr->save(false);

            //商品数据
            foreach($goods_info as $vo){
                $model_order_goods = new OrderGoods();
                $model_order_goods->oid = $model_order->id;
                $model_order_goods->gid = $vo['id'];
                $model_order_goods->price = $vo['price'];
                $model_order_goods->num = $vo['buy_num'];
                $model_order_goods->freight_money = $vo['freight_money'];
                $model_order_goods->taxation_money = $vo['taxation_money'];
                $model_order_goods->name = $vo['name'];
                $model_order_goods->img = $vo['img'];
                $model_order_goods->extra = json_encode($vo);//保存商品原始数据
                $model_order_goods->save(false);
            }

            //合同数据
            $model_user_contract = new UserContract();
            $model_user_contract->oid = $model_order->id;
            $model_user_contract->uid =  $model_user->id;
            $model_user_contract->name =  !empty($contract['name'])?$contract['name']:'';
            $model_user_contract->addr =  !empty($contract['addr'])?$contract['addr']:'';
            $model_user_contract->f_name =  !empty($contract['f_name'])?$contract['f_name']:'';
            $model_user_contract->w_name =  !empty($contract['w_name'])?$contract['w_name']:'';
            $model_user_contract->pay_way =  !empty($contract['pay_way'])?$contract['pay_way']:0;
            $model_user_contract->money =  $model_order->pay_money;//合同金额
            $model_user_contract->save(false);

            $transaction->commit();
        }catch (\Exception $e){
            $transaction->rollBack();
            throw new \Exception($e->getMessage());
        }
    }


    public function rules()
    {
        $rules = parent::rules(); // TODO: Change the autogenerated stub
        $rules = array_merge($rules,[
            ['money','default','value'=>0.00],
            ['pay_money','default','value'=>0.00],
            ['freight_money','default','value'=>0.00],
            ['taxation_money','default','value'=>0.00],
            ['status','default','value'=>0],
            [['uid','no'],'safe'],
        ]);
        return $rules;
    }

    //订单号
    public static function getNo()
    {
        return date('YmdHis').time().rand(100,999);
    }
}