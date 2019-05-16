<?php
namespace backend\controllers;



/**
 * Site controller
 */
class UserController extends CommonController
{

    public function actionIndex()
    {
        $keyword = trim($this->request->get('keyword'));

        $query = \common\models\User::find();
        !empty($keyword) && $query= $query->andWhere(['or',['like','username',$keyword],['like','phone',$keyword]]);


        //获取所有超级管理员组
        if(!$this->is_super_manager){
            $province = empty($this->user_model['province'])?-1:$this->user_model['province'];
            $query = $query->andWhere(['province'=>$province]);
        }


        $count = $query->count();
        $pagination = \Yii::createObject(array_merge(\Yii::$app->components['pagination'],['totalCount'=>$count]));
        $list = $query->with(['linkProvince','linkCity','linkArea','linkAreaInfo','linkType'])->offset($pagination->offset)->limit($pagination->limit)->orderBy('id desc')->all();
        return $this->render('index',[
            'keyword'  =>  $keyword,
            'list'  =>  $list,
            'pagination' => $pagination
        ]);
    }

    //删除用户
    public function actionDel()
    {
        $id = $this->request->get('id');
        $model = new \common\models\User();
        $result = $model->actionDel(['id'=>$id]);
        return $this->asJson($result);
    }

    //查看用户
    public function actionDetail()
    {
        $id = $this->request->get('id');
        $model = \common\models\User::find()->with(['linkProvince','linkCity','linkArea','linkAreaInfo'])->where(['id'=>$id])->one();
        return $this->render('detail',[
            'model'  =>  $model,
        ]);
    }

    public function actionAdd()
    {
        $id = $this->request->get('id');
        $model = new \common\models\User();
        if($this->request->isAjax){
            $php_input = $this->request->post();
            !empty($php_input['img']) && $php_input['img']=implode(',',$php_input['img']);
            $php_input['is_hot'] = empty($php_input['is_hot'])?0:1;
            $result = $model->actionSave($php_input);
            return $this->asJson($result);
        }
        $model = $model::findOne($id);
        //用户类型
        $type_list = \common\models\UserType::find()->orderBy('sort asc')->all();
        //行政区
        $area = \common\models\SysLocationArea::getCacheData();
        return $this->render('add',[
            'model'=>$model,
            'type_list'=>$type_list,
            'area'=>$area,
        ]);
    }

    //用户等级
    public function actionType()
    {
        $query = \common\models\UserType::find();
        $count = $query->count();
        $pagination = \Yii::createObject(array_merge(\Yii::$app->components['pagination'],['totalCount'=>$count]));
        $list = $query->offset($pagination->offset)->limit($pagination->limit)->orderBy('sort asc')->all();
        return $this->render('type',[
            'list'  =>  $list,
            'pagination' => $pagination
        ]);
    }

    public function actionTypeAdd()
    {
        $id = $this->request->get('id');
        $model = new \common\models\UserType();
        if($this->request->isAjax){
            $php_input = $this->request->post();
            $result = $model->actionSave($php_input);
            return $this->asJson($result);
        }
        $model = $model::findOne($id);
        return $this->render('typeAdd',[
            'model'=>$model,
        ]);
    }

    //用户申请
    public function actionReqUp()
    {
        $query = \common\models\UserReqUp::find()->joinWith('linkUser.linkType');
        $count = $query->count();
        $pagination = \Yii::createObject(array_merge(\Yii::$app->components['pagination'],['totalCount'=>$count]));
        $list = $query->offset($pagination->offset)->limit($pagination->limit)->orderBy('id desc')->all();
        return $this->render('reqUp',[
            'list'  =>  $list,
            'pagination' => $pagination
        ]);
    }

    //删除用户
    public function actionTypeDel()
    {
        $id = $this->request->get('id');
        if($id==1) throw new \yii\base\UserException('系统指定类型无法删除');

        $model = new \common\models\UserType();
        $result = $model->actionDel(['id'=>$id]);
        return $this->asJson($result);
    }

    //用户申请处理
    public function actionHandleReq()
    {
        $id = $this->request->post('id');
        $status = $this->request->post('status',1);
        $model = \common\models\UserReqUp::findOne($id);
        if(empty($model)) throw new \yii\base\UserException('操作数据异常');
        if($model['status'])  throw new \yii\base\UserException('操作对象已处理，无法再次操作');
        $model->status=$status;
        $model->handle_time=time();
        $model->save(false);
        return  $this->asJson(['code'=>1,'msg'=>'操作成功']);
    }

    public function actionExcel()
    {

    }
}
