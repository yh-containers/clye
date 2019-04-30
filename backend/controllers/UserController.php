<?php
namespace backend\controllers;



/**
 * Site controller
 */
class UserController extends CommonController
{

    public function actionIndex()
    {
        $query = \common\models\User::find();
        $count = $query->count();
        $pagination = \Yii::createObject(array_merge(\Yii::$app->components['pagination'],['totalCount'=>$count]));
        $list = $query->with(['linkProvince','linkCity','linkArea','linkAreaInfo','linkType'])->offset($pagination->offset)->limit($pagination->limit)->orderBy('id desc')->all();
        return $this->render('index',[
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

}
