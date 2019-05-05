<?php
namespace backend\controllers;

class OrdersController extends CommonController
{

    public function actionIndex()
    {
        $query = \common\models\Order::find();
        $count = $query->count();
        $pagination = \Yii::createObject(array_merge(\Yii::$app->components['pagination'],['totalCount'=>$count]));
        $list = $query->offset($pagination->offset)->limit($pagination->limit)->orderBy('id desc')->all();
        return $this->render('index',[
            'list'  =>  $list,
            'pagination' => $pagination
        ]);


    }

    public function actionDetail()
    {
        $id = $this->request->get('id');
        //订单状态

        $model = \common\models\Order::find()
            ->with(['linkUser','linkOrderAddr','linkOrderGoods','linkOrderContract','linkOrderLogs','linkOrderLogistics'])
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

        return  $this->render('detail',[
            'model' => $model,
            'opt_handle' => $opt_handle,
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

    //查看合同信息
    public function actionContract()
    {
        $oid = $this->request->get('oid');
        $model = \common\models\UserContract::find()->where(['oid'=>$oid])->one();
        return $this->renderPartial('contract',[
            'model' => $model,
        ]);
    }
}
