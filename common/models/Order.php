<?php
namespace common\models;


use common\models\use_traits\SoftDelete;

class Order extends BaseModel
{
    use SoftDelete;
    public $check_channel = false;
    public $channel_g_data;//渠道数据
    public static function tableName()
    {
        return '{{%order}}';
    }

    //当前订单处于什么阶段/流程
//    public function getOrderStep()
//    {
//        $step_cond = [
//            ['status'=>0],//创建订单
//            ['status'=>1],//订单待支付
//        ];
//    }


    //获取订单信息
    public function getOrderStatusInfo()
    {
        $info = self::getStepFlowInfo($this->step_flow);
        if(!empty($info)){
            $func = $info['func'];
            $field = $info['field'];
            $current_step_info = self::$func($this->$field);
            return $current_step_info;
        }
        return null;
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
        if($this->check_channel=='cart'){
            //购物车过来
            $cart_info = UserCart::find()->asArray()->where(['uid'=>$user_model->id,'is_checked'=>1])->all();
            foreach($cart_info as $vo) {
                if(array_key_exists($vo['gid'],$gid)){
                    $gid[$vo['gid']] +=$vo['num'];
                }else{
                    $gid[$vo['gid']] =$vo['num'];
                }
            }
        }
        if($this->check_channel=='once_again'){
            $channel_g_data = array_filter(explode(',',$this->channel_g_data));
            foreach ($channel_g_data as $vo){
                $arr = explode('-',$vo);
                if(count($arr)==2){
                    $gid[$arr[0]] = $arr[1];
                }
            }
        }else{
            //指定商品
            $gid[$id] = $num;
        }
        //所有商品数据
        $goods_ids = array_keys($gid);
        //商品数据
        $goods_info = Goods::find()->with(['linkSpu'])->where(['id'=>$goods_ids])->all();

        foreach ($goods_info as $vo){
            if(isset($gid[$vo['id']])){
                $goods_arr = $vo->getAttributes();
                $goods_arr['linkSpu'] = [];
                foreach ($vo['linkSpu'] as $spu){
                    $goods_arr['linkSpu'][] = $spu->getAttributes();
                }
//                var_dump($goods_arr);exit;

                $goods_arr['buy_num'] = $gid[$goods_arr['id']];
                $goods_arr['per']  = Goods::getGoodsPer($user_model['type']);//商品折扣
                $goods_arr['per_price']  = $vo->getUserPrice($user_model);
                $goods_data[]=  $goods_arr;
            }
        }
        //计算金额相关数据
        $money = [
            'money' => 0.00 ,//总金额
            'goods_money' => 0.00 ,//商品总金额
            'pay_money' => 0.00 ,//实际支付总金额
            'freight_money' => 0.00 ,//运费金额
            'taxation_money' => 0.00 ,//税费总金额
        ];
        foreach ($goods_data as $vo){

            $goods_price = $vo['per_price']*$vo['buy_num']; // 购买金额
            $goods_per_price = $vo['per_price']*$vo['buy_num']; // 购买金额
            $freight_money = 0.00;//$vo['freight_money']*$vo['buy_num']; // 运费金额
            $taxation_money = $vo['taxation_money']*$vo['buy_num']; // 税费金额

            $money['money'] += $goods_price+$freight_money+$taxation_money;
            $money['goods_money'] += $goods_price;
            $money['pay_money'] += $goods_per_price+$freight_money+$taxation_money;
            $money['freight_money'] += $freight_money;
            $money['taxation_money'] += $taxation_money;
        }
        //强转2位小数
        foreach ($money as &$vo){
            $vo = sprintf('%.2f',$vo);
        }

        return [$goods_data,$money];
    }
    /**
     * 订单未处理数量
     * */
    public static function noHandleNum($province,$m_uid,$is_spuer_manager=false)
    {
        $where['status']=0;
        if(!$is_spuer_manager){
            $where['province']= $province;
        }
        $count = self::find()->where($where)->count();
        return  $count?$count:0;
    }


    /**
     * 确认订单
     * @param User $model_user  当前操作用户
     * @param array $goods_info  购买的商品
     * @Param array $money array 商品金额汇总
     * @Param array $model_addr  购买地址
     * @param array $contract  合同信息
     * @throws
     * @return void
     * */
    public function confirm(User $model_user,$goods_info,$money,$model_addr,$contract)
    {
        if(empty($model_addr)) throw new \Exception('请选择收货地址');
        if(empty($goods_info)) throw new \Exception('请选择购买商品');
        //创建合同副本
        $temp_contract = $contract;
        //忽略某个字段
        unset($temp_contract['pay_way']);
        $filter_temp_contract = array_filter($temp_contract);
        if(count($temp_contract)!=count($filter_temp_contract)) throw new \Exception('请完善合同资料');

        //订单数据
        $model_order = $this;
        $model_order->no = self::getOrderNo();
        $model_order->uid = $model_user->id;
        $model_order->area_id = empty($model_user->area_id)?0:$model_user->area_id;
        $model_order->province = $model_user->province;

        //指定选择区域的人
        if(!empty($model_order->province)){
            $manager = \common\models\SysManager::find()->asArray()->where(['province'=>$model_order->province])->orderBy('id asc')->all();
            if(!empty($manager)){
                //循环指定同一区的其他人
                $point_manager_id = $manager[0]['id']; //默认第一个人
                $cache_name = 'up_point_manager'.$model_order->province;
                $up_manager_id = \Yii::$app->cache->get($cache_name);
                foreach ($manager as $key=>$vo) {
                    if($vo['id']==$up_manager_id && isset($manager[$key+1])){
                        $point_manager_id = $manager[$key+1]['id'];
                        break;
                    }elseif (count($manager)-1 == $key){
                        $point_manager_id = $manager[0]['id'];
                    }
                }
                \Yii::$app->cache->set($cache_name,$point_manager_id);

                //直接指定第一个人
                $model_order->m_uid= $point_manager_id;
                $model_order->pm_time= time();
            }
        }

        $model_order->money = !empty($money['money'])?$money['money']:0.00;
        $model_order->pay_money = !empty($money['pay_money'])?$money['pay_money']:0.00;
        $model_order->freight_money = !empty($money['freight_money'])?$money['freight_money']:0.00;
        $model_order->taxation_money = !empty($money['taxation_money'])?$money['taxation_money']:0.00;
        $model_order->taxation_money = !empty($money['taxation_money'])?$money['taxation_money']:0.00;

        try{
            $transaction = self::getDb()->beginTransaction();
            if($this->check_channel=='cart'){
                //购物车过来删除购物车内容
                UserCart::deleteAll(['uid'=>$model_user->id,'is_checked'=>1]);
            }

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
                $model_order_goods->per = $vo['per'];  //折扣
                $model_order_goods->per_price = $vo['per_price']?$vo['per_price']:0.00;//
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
            $model_user_contract->no = self::getContractNo();
            $model_user_contract->oid = $model_order->id;
            $model_user_contract->uid =  $model_user->id;
            $model_user_contract->name =  !empty($contract['name'])?$contract['name']:'';
            $model_user_contract->addr =  !empty($contract['addr'])?$contract['addr']:'';
            $model_user_contract->f_name =  !empty($contract['f_name'])?$contract['f_name']:'';
            $model_user_contract->w_name =  !empty($contract['w_name'])?$contract['w_name']:'';
            $model_user_contract->pay_way =  !empty($contract['pay_way'])?$contract['pay_way']:0;
            $model_user_contract->money =  $model_order->pay_money;//合同金额
            $contract['money'] = $model_order->pay_money;//合同金额
            list($temp_content,$extra_data) = UserContract::setTempContent($contract);//模板内容
            //绑定额外属性
            foreach($extra_data as $key=>$vo){
                $model_user_contract->hasAttribute($key) && $model_user_contract->$key=$vo;
            }
            $model_user_contract->content = $temp_content;
            $model_user_contract->save(false);
            $transaction->commit();
        }catch (\Exception $e){
            $transaction->rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * 删除订单
     * */
    public function del()
    {
        //获取当前订单状态
        $current_state_info = $this->getOrderStatusInfo();
        if(!isset($current_state_info['handle']) || (!in_array('delete',$current_state_info['handle']) && !array_key_exists('delete',$current_state_info['handle']))){
            throw new \Exception('订单未处于可删除状态无法进行删除动作');
        }
        //删除订单
        $this->delete();
    }

    //确定收款
    public static function surePay($id)
    {
        if(empty($id)) throw new \Exception('订单数据异常');
        //查询订单信息
        $model = self::findOne($id);
        if(empty($model)) throw new \Exception('操作对象异常');

        if($model['status']!=0) throw new \Exception('订单未处于待付款状态;无法进行此操作');
        //查询购买商品
        $transaction = \Yii::$app->db->beginTransaction();
        try{

            $model_goods = $model->linkOrderGoods;
            if($model_goods) {
                $gid =[];
                foreach($model_goods as $vo){
                    array_push($gid,$vo['gid']);
                }
                Goods::updateAllCounters(['sold_num'=>1],['id'=>$gid]);
            }

            $model->status = 1;
            $model->step_flow = 1;//开始制作流程
            $model->pay_time=time();

            //付款直接开始生产
            $model->is_produce=1;
            $model->pro_start_time = time();
            $model->save(false);

            $transaction->commit();
        }catch (\Exception $e){
            $transaction->rollBack();
            throw new \Exception('订单保存异常');
        }

    }

    //生产
    public static function optProduce($id,$state=1)
    {
        if(empty($id)) throw new \Exception('订单数据异常');
        //查询订单信息
        $model = self::findOne($id);
        if(empty($model)) throw new \Exception('操作对象异常');

        if($model['step_flow']!=1) throw new \Exception('订单流程异常,无法进行此操作');

        $model->is_produce = $state;
        if($state==2){
            //完成生产
            $model->pro_end_time = time();
            $model->step_flow = 2; //进入发货

            //生产完成就是待发货
            $model->is_send =1;
            $model->send_end_time = time();

        }elseif($state==1){
            $model->pro_start_time = time();
        }
        $save_bool = $model->save(false);
        if(!$save_bool){
            throw new \Exception('订单保存异常');
        }
    }
    //发货
    public static function optSend($id,$state=1,array $logistics=[])
    {
        if(empty($id)) throw new \Exception('订单数据异常');
        //查询订单信息
        $model = self::findOne($id);
        if(empty($model)) throw new \Exception('操作对象异常');

        if($model['step_flow']!=2) throw new \Exception('订单流程异常,无法进行此操作');

        $transaction = self::getDb()->beginTransaction();
        try{
            $model->is_send = $state;
            if($state==2){
                if(empty($logistics['no']))  throw new \Exception('请输入物流单号');
                if(empty($logistics['company']))  throw new \Exception('请输入公司名称');
                //物流
                $model_logistics = OrderLogistics::find()->where(['oid'=>$id])->limit(1)->one();
                if(empty($model_logistics)){
                    $model_logistics = new OrderLogistics();
                }
                $model_logistics->oid = $id;
                $model_logistics->no = $logistics['no'];
                $model_logistics->company = $logistics['company'];
                $model_logistics->money = empty($logistics['money'])?0.00:$logistics['money'];
                $model_logistics->save(false);
                //发货完成
                $model->send_end_time = time();
                $model->step_flow = 3; //进入发货
                $model->is_receive=1;//等待收货状态
                $model->receive_start_time=time();//开始收货时间
            }elseif($state==1){
                $model->send_start_time = time();
            }
            $model->save(false);
            $transaction->commit();
        }catch (\Exception $e){
            $transaction->rollBack();
            throw new \Exception('订单操作异常:'.$e->getMessage());
        }

    }
    //收货
    public static function receive($id)
    {
        if(empty($id)) throw new \Exception('订单数据异常');
        //查询订单信息
        $model = self::findOne($id);
        if(empty($model)) throw new \Exception('操作对象异常');

        if($model['step_flow']!=3) throw new \Exception('订单流程异常,无法进行此操作');

        //完成生产
        $model->step_flow = 4; //进入发货
        $model->is_receive=2;//收货成功
        $model->receive_end_time = time();
        //交易完成
        $model->status=3;
        $model->complete_time = time();//交易完成

        $save_bool = $model->save(false);
        if(!$save_bool){
            throw new \Exception('订单保存异常');
        }
    }
    /**
     * 修改订单行政区
     * @param $id int 订单id
     * @param $area_id int 行政区id
     * @throws
     * */
    public static function modArea($id,$area_id)
    {
        if(empty($id)) throw new \Exception('订单数据异常');
        //查询订单信息
        $model = self::findOne($id);
        if(empty($model)) throw new \Exception('操作对象异常');
        if($model['area_id']!=$area_id){
            $model->area_id = $area_id;
            $save_bool = $model->save(false);
            if(!$save_bool){
                throw new \Exception('订单保存异常');
            }
        }
    }
    /**
     * 修改订单所在省份
     * @param $id int 订单id
     * @param $area_id int 行政区id
     * @throws
     * */
    public static function modProvince($id,$province_id)
    {
        if(empty($id)) throw new \Exception('订单数据异常');
        //查询订单信息
        $model = self::findOne($id);
        if(empty($model)) throw new \Exception('操作对象异常');
        if($model['province']!=$province_id){
            $model->province = $province_id;
            $save_bool = $model->save(false);
            if(!$save_bool){
                throw new \Exception('订单保存异常');
            }
        }
    }
    /**
     * 取消订单
     * @param $id int 订单id
     * @param $user_id int 用户id
     * @param $is_force bool 是否强制取消订单
     * @throws
     * */
    public static function cancelOrder($id,$user_id=0,$is_force=false)
    {
        if(empty($id)) throw new \Exception('订单数据异常');
        //查询订单信息
        $where['id'] = $id;
        $user_id!==0 && $where['uid'] = $user_id;
        $model = self::find()->where($where)->one();
        if(empty($model)) throw new \Exception('操作对象异常');
        if($model['status'] && !$is_force) throw new \Exception('正在处理订单无法取消');

        $model->status = 2;
        $model->cancel_time = time();
        $save_bool = $model->save(false);
        if(!$save_bool){
            throw new \Exception('订单保存异常');
        }
    }
    /**
     * 自动添加时间戳，序列化参数
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        //订单处理日志
        $behaviors[]=\common\components\OrderBehavior::className();
        //开启软删除
        $behaviors['softDeleteBehavior'] = [
            'class' => \yii2tech\ar\softdelete\SoftDeleteBehavior::className(),
            'softDeleteAttributeValues' => [
                self::getSoftDeleteField() => time(),
            ],
            'replaceRegularDelete' => true // mutate native `delete()` method
        ];
        return $behaviors;
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

    //订单号
    public static function getOrderNo()
    {
        $cache = \Yii::$app->cache;
        $cache_name = self::tableName().'-order_no'.date('Y-m-d');
        $number = $cache->get($cache_name);
        empty($number) && $number = 0;
        $number+=1;
        //保存一天时间
        $cache->set($cache_name,$number,86400);
        $number = sprintf('%05d',$number);
        return date('YmdHis').rand(10,99).$number;
    }
    //订单号
    public static function getContractNo()
    {
        $cache = \Yii::$app->cache;
        $cache_name = self::tableName().'-contract_no'.date('Y-m-d');
        $number = $cache->get($cache_name);
        empty($number) && $number = 0;
        $number+=1;
        //保存一天时间
        $cache->set($cache_name,$number,86400);
        $number = sprintf('%05d',$number);
        return date('YmdHis').rand(10,99).$number;
    }


    //订单状态流程
    public static function getStepFlowInfo($type=null,$field=null)
    {
        $data = [
            ['name'=>'待付款','func'=>'getStatusInfo','field'=>'status'],
            ['name'=>'生产','func'=>'getProduceInfo','field'=>'is_produce'],
            ['name'=>'发货','func'=>'getSendInfo','field'=>'is_send'],
            ['name'=>'收货','func'=>'getReceiveInfo','field'=>'is_receive'],
            ['name'=>'订单完成','func'=>'getStatusInfo','field'=>'status'],
        ];

        if(is_null($type)){
            return $data;
        }else{
            $info = isset($data[$type])?$data[$type]:[];
            if(is_null($field)){
                return $info;
            }else{
                return isset($info[$field])?$info[$field]:'';
            }
        }
    }

    //订单状态切换
    public static function getStatusInfo($type=null,$field=null)
    {
        $data = [
            ['name'=>'待付款','intro'=>'您的订单已下单成功，请联系商家进行付款','handle'=>['cancel_order'],'opt_handle'=>['sure_pay','cancel_order']],
            ['name'=>'已付款','intro'=>'您的订单已付款,待商家进行其它操作'],
            ['name'=>'已取消','intro'=>'您的订单交易失败','handle'=>['delete']],
            ['name'=>'已完成','intro'=>'您的订单已完成','handle'=>['logistics'=>['is_send'=>2]],'delete'],
        ];

        if(is_null($type)){
            return $data;
        }else{
            $info = isset($data[$type])?$data[$type]:[];
            if(is_null($field)){
                return $info;
            }else{
                return isset($info[$field])?$info[$field]:'';
            }
        }
    }

    //生产状态
    public static function getProduceInfo($type=null,$field=null)
    {
        $data = [
            ['name'=>'未生产','intro'=>'未生产','opt_handle'=>['product_up']],
            ['name'=>'生产中','intro'=>'您的订单已进入生产阶段，请耐心等待发货通知','opt_handle'=>['product_down']],
            ['name'=>'生产完成','intro'=>'您的订单已进入生产完成，请耐心等待发货通知'],
        ];

        if(is_null($type)){
            return $data;
        }else{
            $info = isset($data[$type])?$data[$type]:[];
            if(is_null($field)){
                return $info;
            }else{
                return isset($info[$field])?$info[$field]:'';
            }
        }
    }

    //发货状态
    public static function getSendInfo($type=null,$field=null)
    {
        $data = [
            ['name'=>'待发货','intro'=>'您的订单准备发货','opt_handle'=>['send_up']],
            ['name'=>'完成发货','intro'=>'您的订单已发货，等待收货','handle'=>['logistics'],'opt_handle'=>['send_down']],
            ['name'=>'发货完成','intro'=>'您的订单发货已完成','handle'=>['logistics']],
        ];

        if(is_null($type)){
            return $data;
        }else{
            $info = isset($data[$type])?$data[$type]:[];
            if(is_null($field)){
                return $info;
            }else{
                return isset($info[$field])?$info[$field]:'';
            }
        }
    }
    //收货状态
    public static function getReceiveInfo($type=null,$field=null)
    {
        $data = [
            ['name'=>'未开始','intro'=>'未开始'],
            ['name'=>'等待收货','intro'=>'您的订单已发货,等待收货','handle'=>['receive','logistics']],
            ['name'=>'已收货','intro'=>'您已收货,物流已完成','handle'=>['logistics']],
        ];

        if(is_null($type)){
            return $data;
        }else{
            $info = isset($data[$type])?$data[$type]:[];
            if(is_null($field)){
                return $info;
            }else{
                return isset($info[$field])?$info[$field]:'';
            }
        }
    }



    /*
     * 日志记录
     * */
    public function getLogIntro(\yii\base\Event $event)
    {
        $content = '';
        $object = $event->sender;
        if($event->name==self::EVENT_AFTER_INSERT){
            $content = '新增订单:'.$object['no'];
        }elseif ($event->name==self::EVENT_BEFORE_DELETE){
            $content = '删除订单:'.$object['no'];
        }elseif ($event->name==self::EVENT_AFTER_UPDATE){
            $content = '更新订单:'.$object['no'];
        }
        return $content;
    }

    //获取订单用户信息
    public function getLinkUser()
    {
        return $this->hasOne(User::className(),['id'=>'uid']);
    }
    //获取订单地址
    public function getLinkOrderAddr()
    {
        return $this->hasOne(OrderAddr::className(),['oid'=>'id'])->orderBy('id desc');
    }
    //获取订单物流信息
    public function getLinkOrderLogistics()
    {
        return $this->hasOne(OrderLogistics::className(),['oid'=>'id'])->orderBy('id desc');
    }
    //获取订单商品
    public function getLinkOrderGoods()
    {
        return $this->hasMany(OrderGoods::className(),['oid'=>'id']);
    }
    //获取订单合同
    public function getLinkOrderContract()
    {
        return $this->hasOne(UserContract::className(),['oid'=>'id']);
    }
    //产品日志
    public function getLinkOrderLogs()
    {
        return $this->hasMany(UserOrderLogs::className(),['oid'=>'id'])->orderBy('id desc');
    }
    //跟进人
    public function getLinkFlowManager()
    {
        return $this->hasOne(SysManager::className(),['id'=>'m_uid']);
    }
    //行政区
    public function getLinkLocationArea()
    {
        return $this->hasOne(SysLocationArea::className(),['id'=>'area_id']);
    }
    //行政区
    public function getLinkLocationProvince()
    {
        return $this->hasOne(SysLocation::className(),['id'=>'province']);
    }
}