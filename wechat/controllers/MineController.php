<?php
namespace wechat\controllers;


class MineController extends CommonController
{

    public $is_need_login=true;
    /**
     * 用户模型
     * @var \common\models\User
     * */
    protected $user_model;

    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
        $this->user_model = \common\models\User::findOne($this->user_id);
        if(empty($this->user_model)){
            $this->user_id = 0;
        }
    }

    public function actionIndex()
    {
        //收藏数量
        $col_num = \common\models\UserCol::find()->where(['uid'=>$this->user_id])->count();
        //待付款
        $wait_num = \common\models\Order::find()->where(['uid'=>$this->user_id,'status'=>0])->count();
        //生产中
        $produce_num = \common\models\Order::find()->where(['uid'=>$this->user_id,'is_produce'=>1])->count();
        //待发货
        $send_num = \common\models\Order::find()->where(['uid'=>$this->user_id,'is_send'=>1])->count();
        //待收货
        $receive_num = \common\models\Order::find()->where(['uid'=>$this->user_id,'is_receive'=>1])->count();
        return $this->render('index',[
            'user_model' => $this->user_model,
            'user_type' => $this->user_model->linkType,
            'col_num'=>$col_num?$col_num:0,
            'wait_num'=>$wait_num?$wait_num:0,
            'produce_num'=>$produce_num?$produce_num:0,
            'send_num'=>$send_num?$send_num:0,
            'receive_num'=>$receive_num?$receive_num:0,
        ]);
    }

    //我的收藏
    public function actionCollect()
    {
        return $this->render('collect',[

        ]);
    }

    //我的收藏-列表
    public function actionCollectList()
    {
        $query = \common\models\UserCol::find()
            ->where([
                \common\models\UserCol::tableName().'.uid'=>$this->user_id
            ]);
        $count = $query->count();
        $pagination = \Yii::createObject(array_merge(\Yii::$app->components['pagination'],['totalCount' => $count]));
        $list = $query->joinWith(['linkGoods'],true,'RIGHT JOIN')->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        $data = [];
        foreach($list as $vo){
            $data[] = [
                'id'         =>  $vo['id'],
                'goods_id'   =>  $vo['linkGoods']['id'],
                'name'       =>  $vo['linkGoods']['name'],
                'price'      =>  $vo['linkGoods']['price'],
                'cover_img'  =>  \common\models\Goods::getCoverImg($vo['linkGoods']['img']),
            ];
        }

        return $this->asJson(['code'=>1,'msg'=>'获取成功','data'=>$data,'page'=>$pagination->pageCount]);
    }


    //我的地址
    public function actionAddress()
    {
        $channel = $this->request->get('channel');
        return $this->render('address',[
            'channel' => $channel
        ]);
    }
    //我的地址-列表
    public function actionAddressList()
    {
        $query = \common\models\UserAddr::find()
            ->where([
                'uid'=>$this->user_id
            ]);
        $count = $query->count();
        $pagination = \Yii::createObject(array_merge(\Yii::$app->components['pagination'],['totalCount' => $count]));
        $list = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->orderBy('is_default desc,id desc')
            ->all();

        $data = [];
        foreach($list as $vo){
            $data[] = [
                'id'         =>  $vo['id'],
                'username'   =>  $vo['username'],
                'phone'      =>  substr_replace($vo['phone'],'****',3,4),
                'is_default' => $vo['is_default'],
                'addr'       => $vo['addr'],
                'addr_extra' => $vo['addr_extra'],
            ];
        }

        return $this->asJson(['code'=>1,'msg'=>'获取成功','data'=>$data,'page'=>$pagination->pageCount]);
    }

    //我的地址-新增/编辑
    public function actionAddressAddr()
    {
        $id = $this->request->get('id',0);
        $model = new \common\models\UserAddr();
        if($this->request->isAjax){
            $is_default = $this->request->post('is_default');
            $php_input = $this->request->post();
            $php_input['uid'] = $this->user_id;
            $php_input['is_default'] = empty($is_default)?0:1;
            $model->scenario = \common\models\UserAddr::SCENARIO_OPT_DATA;
            $result = $model->actionSave($php_input);
            return $this->asJson($result);
        }
        $model = $model::findOne($id);

        return $this->render('addressAddr',[
            'model'=>$model
        ]);
    }

    //删除地址
    public function actionAddressDel()
    {
        $id = $this->request->get('id',0);
        $model = new \common\models\UserAddr();
        $result = $model->actionDel(['id'=>$id,'uid'=>$this->user_id]);

        return $this->asJson($result);
    }

    //基本资料
    public function actionInfo()
    {
        if($this->request->isAjax){
            $limit_field = ['username','sex','area_id','company_name','contacts','email','face'];
            $php_input = $this->request->post();
            foreach($limit_field as $field){
                if(!empty($php_input[$field])){
                    $this->user_model[$field]= $php_input[$field];
                }
            }
            $bool = $this->user_model->save(false);
            if($bool){
                return $this->asJson(['code'=>1,'msg'=>'操作成功']);
            }else{
                return $this->asJson(['code'=>0,'msg'=>'操作失败']);
            }


        }
        //地区列表
        $area = \common\models\SysLocationArea::getCacheData();

        return $this->render('info',[
            'user_model' => $this->user_model,
            'area' => $area,
        ]);
    }


    //修改用户头像
    public function actionModFace()
    {
        $result = \Yii::$app->runAction('upload/upload',['type'=>'header','is_return'=>1]);
        if($result['code']==1){
            $this->user_model->face = $result['path'];
            $this->user_model->save(false);
            return $this->asJson($result);
        }else{
            return $this->asJson($result);
        }
    }

    //合同文件上传
    public function actionContractUploadFile()
    {
        $id = $this->request->post('id');
        $model = \common\models\UserContract::findOne($id);
        if(empty($model)) throw new \yii\base\UserException('合同信息异常');
        if($model['uid']!=$this->user_id) throw new \yii\base\UserException('合同信息异常2');

        $result = \Yii::$app->runAction('upload/upload',['type'=>'contract','user_id'=>$this->user_id,'is_return'=>1]);
        if($result['code']==1){
            $model->file=$result['path'];
            $model->save(false);
            return $this->asJson($result);
        }else{
            return $this->asJson($result);
        }

    }

    //用户信息
    public function actionData()
    {
        //所属区域
        $area_info = \common\models\SysLocationArea::findOne($this->user_model['area_id']);
        return $this->render('data',[
            'user_model' => $this->user_model,
            'area_info' =>$area_info,
            'user_type' => $this->user_model->linkType,
        ]);
    }
    //用户信息
    public function actionUp()
    {
        //所属区域
        $area_info = \common\models\SysLocationArea::findOne($this->user_model['area_id']);
        return $this->render('up',[
            'user_model' => $this->user_model,
            'area_info' =>$area_info,
            'user_type' => $this->user_model->linkType,
        ]);
    }

    //用户信息
    public function actionUpPage()
    {
        //新建用户申请信息
        $model = new \common\models\UserReqUp();
        $model->uid = $this->user_model->id;
        $model->type = $this->user_model->type;
        $model->status = 0;
        $model->save(false);

        return $this->render('upPage',[
        ]);
    }

    //添加购物车
    public function actionAddCart()
    {
        $gid = $this->request->get('gid');
        $num = $this->request->get('num',1);
        if(empty($gid)) throw new \yii\base\UserException('请求信息异常');

        $bool = $this->user_model->addShoppingCart($gid,$num);
        //绑定购物车数量
        $cart_num = \common\models\UserCart::getNum($this->user_id);
        if($bool){
            return  $this->asJson(['code'=>1,'msg'=>'加入购物车成功','cart_num'=>$cart_num]);
        }else{
            return  $this->asJson(['code'=>0,'msg'=>'加入购物车失败']);
        }
    }

    //商品收藏
    public function actionGoodsCol()
    {
        $gid = $this->request->get('gid');
        $is_del = $this->request->get('is_del');
        $is_del==1?true:false;
        if(empty($gid)) throw new \yii\base\UserException('请求信息异常');
        try{
            list($bool,$is_col) = $this->user_model->goodsCol($gid,$is_del);
        }catch (\Exception $e){
            return  $this->asJson(['code'=>0,'msg'=>'操作异常:'.$e->getMessage()]);
        }

        if($bool){
            return  $this->asJson(['code'=>1,'msg'=>!$is_del?($is_col?'收藏成功':'已取消收藏'):'已取消收藏','is_col'=>$is_col]);
        }else{
            return  $this->asJson(['code'=>0,'msg'=>'收藏失败']);
        }
    }

    //购物车商品选中/取消选中效果
    public function actionCartChoose()
    {
        $cart_id = $this->request->get('cart_id');
        $is_checked = $this->request->get('is_checked');
        if(empty($cart_id) && $is_checked=='') throw new \yii\base\UserException('请求信息异常');
        list($bool,$is_checked) = $this->user_model->cartChoose($cart_id,$is_checked==''?null:$is_checked);


        if($bool){
            return  $this->asJson(['code'=>1,'msg'=>'操作成功','is_checked'=>$is_checked]);
        }else{
            return  $this->asJson(['code'=>0,'msg'=>'收藏失败']);
        }
    }

    //删除购物车
    public function actionCartDel()
    {
        $c_ids = $this->request->get('c_ids');
        $c_ids = is_array($c_ids)?$c_ids:($c_ids?explode(',',$c_ids):[]);
        if(empty($c_ids)) throw new \yii\base\UserException('请求信息异常');

        try{
            $this->user_model->cartDel($c_ids);
            //绑定购物车数量
            $cart_num = \common\models\UserCart::getNum($this->user_id);
            return  $this->asJson(['code'=>1,'msg'=>'操作成功','cart_num'=>$cart_num]);
        }catch (\Exception $e) {
            return  $this->asJson(['code'=>0,'msg'=>'操作失败']);
        }

    }


    //合同管理
    public function actionContract()
    {
        $num = \common\models\UserContract::find()->where(['uid'=>$this->user_id])->count();
        return $this->render('contract',[
            'num' => $num
        ]);
    }
    //合同管理
    public function actionContractList()
    {

        $query = \common\models\UserContract::find()
            ->where([
                'uid'=>$this->user_id
            ]);
        $count = $query->count();
        $pagination = \Yii::createObject(array_merge(\Yii::$app->components['pagination'],['totalCount' => $count]));
        $list = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        $data = [];
        foreach($list as $vo){
            $data[] = [
                'id'         =>  $vo['id'],
                'name'       =>  $vo['name'],
                'date'       =>  $vo['create_time']?date('Y-m-d',$vo['create_time']):'',
            ];
        }

        return $this->asJson(['code'=>1,'msg'=>'获取成功','data'=>$data,'page'=>$pagination->pageCount]);
    }


    public function actionContractDetail()
    {
        if($this->request->isAjax){
            $id = $this->request->post('id');
            $file = $this->request->post('file');
            $model = \common\models\UserContract::findOne($id);
            if(empty($model)) throw new \yii\base\UserException('合同信息异常');
            if($model['uid']!=$this->user_id) throw new \yii\base\UserException('合同信息异常2');

            $model->file=$file;
            $model->save(false);
            return $this->asJson(['code'=>1,'msg'=>'保存成功']);
        }


        $id = $this->request->get('id');
        $model = \common\models\UserContract::findOne($id);

        return $this->render('contractDetail',[
            'model' => $model,
        ]);
    }


    //合同具体内容
    public function actionContractContent()
    {
        $id = $this->request->get('id');
        $model = \common\models\UserContract::find()->where(['id'=>$id,'uid'=>$this->user_id])->one();
        $content = '';
        if(!empty($model)){
            $content = $model['content'];
        }else{
            list($content)=\common\models\UserContract::setTempContent();
        }
        return $this->render('contractContent',[
            'content' => $content,
        ]);
    }


    public function actionSetting()
    {

        return $this->render('setting',[
            'user_model' => $this->user_model,
        ]);
    }

    public function actionAccount()
    {

        return $this->render('account',[
            'user_model' => $this->user_model,
        ]);
    }

    public function actionModPwd()
    {
        if($this->request->isAjax){
            $php_input = $this->request->post();
            try{
                $this->user_model->modPwd($php_input);
            }catch (\Exception $e){
                throw new \yii\base\UserException($e->getMessage());
            }
            return $this->asJson(['code'=>1,'msg'=>'操作成功']);
        }

        return $this->render('modPwd',[
            'user_model' => $this->user_model,
        ]);
    }

    public function actionModPhone()
    {
        if($this->request->isAjax){
            $php_input = $this->request->post();
            try{
                $this->user_model->modPhone($php_input);
            }catch (\Exception $e){
                throw new \yii\base\UserException($e->getMessage());
            }
            return $this->asJson(['code'=>1,'msg'=>'操作成功']);
        }
        return $this->render('modPhone',[
            'user_model' => $this->user_model,
        ]);
    }

}