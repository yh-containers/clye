<?php
namespace backend\controllers;

class OrdersController extends CommonController
{

    public function actionIndex()
    {
        $state = $this->request->get('state',0);
        $keyword = trim($this->request->get('keyword'));



        $cond_where=[];
        $query = \common\models\Order::find();
        if($state==1){ //待付款
            $cond_where = [\common\models\Order::tableName().'.status'=>0];
        }elseif($state==2){//已付款
            $cond_where = [\common\models\Order::tableName().'.status'=>1];
        }elseif($state==3){//未生产
            $cond_where = [\common\models\Order::tableName().'.status'=>1,'is_produce'=>0];
        }elseif($state==4){//未生产中
            $cond_where = [\common\models\Order::tableName().'.status'=>1,'is_produce'=>1];
        }elseif($state==5){//生产完成
            $cond_where = [\common\models\Order::tableName().'.status'=>1,'is_produce'=>2];
        }elseif($state==6){//待发货
            $cond_where = [\common\models\Order::tableName().'.status'=>1,'is_produce'=>2,'is_send'=>1];
        }elseif($state==7){//已发货
            $cond_where = [\common\models\Order::tableName().'.status'=>1,'is_produce'=>2,'is_send'=>2];
        }elseif($state==8){//待收货
            $cond_where = [\common\models\Order::tableName().'.status'=>1,'is_produce'=>2,'is_send'=>2,'is_receive'=>1];
        }elseif($state==9){//已收货
            $cond_where = [\common\models\Order::tableName().'.status'=>1,'is_produce'=>2,'is_send'=>2,'is_receive'=>2];
        }elseif($state==10){//已收货
            $cond_where = [\common\models\Order::tableName().'.status'=>3];
        }
        !empty($cond_where) && $query = $query->andWhere($cond_where);
        //关键字搜索
        !empty($keyword)  && $query = $query->andWhere(['like','no',$keyword]);

        //获取管理员角色问题
        //获取所有超级管理员组
        $roles = \common\models\SysRole::find()->asArray()->where(['or',['pid'=>1],['id'=>1]])->all();
        $roles_groups = array_column($roles,'id');
        if(!in_array($this->user_model->rid,$roles_groups)){
            $query = $query->andWhere(['or',[\common\models\Order::tableName().'.area_id'=>$this->user_model->area_id],['m_uid'=>$this->user_id]]);
        }

        $count = $query->count();
        $pagination = \Yii::createObject(array_merge(\Yii::$app->components['pagination'],['totalCount'=>$count]));
        $list = $query->joinWith(['linkLocationArea','linkFlowManager'])->offset($pagination->offset)->limit($pagination->limit)->orderBy('id desc')->all();

        //所有员工
        $manager = \common\models\SysManager::find()->all();
        return $this->render('index',[
            'state'  =>  $state,
            'keyword'  =>  $keyword,
            'list'  =>  $list,
            'pagination' => $pagination,
            'manager' => $manager
        ]);
    }

    public function actionDetail()
    {
        $id = $this->request->get('id');
        //订单状态

        $model = \common\models\Order::find()
            ->with(['linkUser','linkOrderAddr','linkOrderGoods','linkOrderContract','linkOrderLogs','linkOrderLogistics','linkFlowManager'])
            ->where(['id'=>$id])
            ->one();
        $opt_handle = [];
        if(!empty($model)){
            //订单信息
            $order_flow_info = \common\models\Order::getStepFlowInfo($model['step_flow']);
            if(!empty($order_flow_info)){
                //流程控制
                $func = $order_flow_info['func'];
                $field = $order_flow_info['field'];
                $state_info = \common\models\Order::$func($model->$field);
                if(isset($state_info['opt_handle'])) {
                    $opt_handle = $state_info['opt_handle'];
                }
            }
        }
        //所有员工
        $manager = \common\models\SysManager::find()->all();
        return  $this->render('detail',[
            'model' => $model,
            'opt_handle' => $opt_handle,
            'area' => \common\models\SysLocationArea::getCacheData(),
            'manager' => $manager
        ]);
    }
    //已收到付款
    public function actionSurePay()
    {
        $id = $this->request->get('id');
        try{
            \common\models\Order::surePay($id);
        }catch (\Exception $e){
            throw new \yii\base\UserException($e->getMessage());
        }
        $this->asJson(['code'=>1,'msg'=>'操作成功']);
    }

    //产品生成
    public function actionProductUp()
    {
        $id = $this->request->get('id');
        try{
            \common\models\Order::optProduce($id,1);
        }catch (\Exception $e){
            throw new \yii\base\UserException($e->getMessage());
        }
        $this->asJson(['code'=>1,'msg'=>'操作成功']);
    }


    //产品生产完成
    public function actionProductDown()
    {
        $id = $this->request->get('id');
        try{
            \common\models\Order::optProduce($id,2);
        }catch (\Exception $e){
            throw new \yii\base\UserException($e->getMessage());
        }
        $this->asJson(['code'=>1,'msg'=>'操作成功']);
    }

    //产品生产完成
    public function actionSendUp()
    {
        $id = $this->request->get('id');
        try{
            \common\models\Order::optSend($id,1);
        }catch (\Exception $e){
            throw new \yii\base\UserException($e->getMessage());
        }
        $this->asJson(['code'=>1,'msg'=>'操作成功']);
    }
    //产品生产完成
    public function actionSendDown()
    {
        $id = $this->request->get('id');
        $logistics = $this->request->get('logistics');
        try{
            \common\models\Order::optSend($id,2,$logistics);
        }catch (\Exception $e){
            throw new \yii\base\UserException($e->getMessage());
        }
        $this->asJson(['code'=>1,'msg'=>'操作成功']);
    }

    //修改订单行政区
    public function actionModArea()
    {
        $id = $this->request->get('id');
        $area_id = $this->request->get('area_id');
        try{
            \common\models\Order::modArea($id,$area_id);
        }catch (\Exception $e){
            throw new \yii\base\UserException($e->getMessage());
        }
        $this->asJson(['code'=>1,'msg'=>'操作成功']);
    }

    //查看合同信息
    public function actionContract()
    {
        $oid = $this->request->get('oid');
        $model = \common\models\UserContract::find()->where(['oid'=>$oid])->one();
        return $this->renderPartial('contract',[
            'model' => $model,
        ]);
    }
    //查看合同信息
    public function actionPointManager()
    {
        $id = $this->request->get('id');
        $uid = $this->request->get('uid');
        $model = \common\models\Order::findOne($id);
        if(empty($uid)) throw new \yii\base\UserException('请选择指派的用户');
        if(empty($model)) throw new \yii\base\UserException('操作数据异常');

        $model->m_uid=$uid;
        $model->pm_time = time();
        $bool = $model->save(false);
        return $this->asJson(['code'=>(int)$bool,'msg'=>$bool?'操作成功':'操作异常']);
    }

    //取消订单
    public function actionCancelOrder()
    {
        $id = $this->request->get('id');
        try{
            \common\models\Order::cancelOrder($id);
        }catch (\Exception $e){
            throw new \yii\base\UserException($e->getMessage());
        }
        $this->asJson(['code'=>1,'msg'=>'操作成功']);
    }

    //证书上传
    public function actionContractFile()
    {
        //合同id
        $id = $this->request->get('id');
        $model = \common\models\UserContract::findOne($id);
        if(empty($model)) throw new \yii\base\UserException('合同信息异常,无法进行此操作');
        $result = \Yii::$app->runAction('upload/upload',['type'=>'contract','user_id'=>$model['uid'],'is_return'=>1]);
        if($result['code']){
            $model->file = $result['path'];
            $bool = $model->save(false);
            if(!$bool){
                return $this->asJson(['code'=>0,'msg'=>'更新异常']);
            }
            //添加操作日志
            \common\models\UserOrderLogs::recordLog($model['oid'],'更新合同附件','更新合同附件',$this->user_id);
            return $this->asJson($result);
        }else{
            return $this->asJson($result);
        }
    }

    //导出excel
    public function actionExportExcel()
    {
        //数据库取出数据
        $query = \common\models\Order::find()
            ->with(['linkUser','linkLocationArea','linkFlowManager'])
            ->orderBy('id desc')
        ;
        $state = $this->request->get('state',0);
        $keyword = trim($this->request->get('keyword'));

        if($state==1){ //待付款
            $cond_where = ['status'=>0];
        }elseif($state==2){//已付款
            $cond_where = ['status'=>1];
        }elseif($state==3){//未生产
            $cond_where = ['status'=>1,'is_produce'=>0];
        }elseif($state==4){//未生产中
            $cond_where = ['status'=>1,'is_produce'=>1];
        }elseif($state==5){//生产完成
            $cond_where = ['status'=>1,'is_produce'=>2];
        }elseif($state==6){//待发货
            $cond_where = ['status'=>1,'is_produce'=>2,'is_send'=>1];
        }elseif($state==7){//已发货
            $cond_where = ['status'=>1,'is_produce'=>2,'is_send'=>2];
        }elseif($state==8){//待收货
            $cond_where = ['status'=>1,'is_produce'=>2,'is_send'=>2,'is_receive'=>1];
        }elseif($state==9){//已收货
            $cond_where = ['status'=>1,'is_produce'=>2,'is_send'=>2,'is_receive'=>2];
        }elseif($state==10){//已收货
            $cond_where = ['status'=>3];
        }
        !empty($cond_where) && $query = $query->andWhere($cond_where);
        //关键字搜索
        !empty($keyword)  && $query = $query->andWhere(['like','no',$keyword]);

        $data = [
            ['订单号','用户名','用户手机号码','订单金额','支付金额','运费','税费','创建时间','行政区','跟进人','状态(流程)'],
        ];
        foreach ($query->batch() as $orders){
            foreach ($orders as $item){
                $state_info = $item->getOrderStatusInfo();
                array_push($data,[
                   $item['no'],
                   $item['linkUser']['username'],
                   $item['linkUser']['phone'],
                   $item['money'],
                   $item['pay_money'],
                   $item['freight_money'],
                   $item['taxation_money'],
                   $item['create_time']?date('Y-m-d H:i:s',$item['create_time']):'',
                   $item['linkLocationArea']['name'],
                   $item['linkFlowManager']['name'],
                   $state_info['name'].'('.\common\models\Order::getStepFlowInfo($item['step_flow'],'name').')',
               ]);
            }
        }
        \backend\components\ExportExcel::handleData($data,'订单信息');
        return $this->render('');

    }
}
