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
        $list = $query->with(['linkProvince','linkCity','linkArea','linkAreaInfo'])->offset($pagination->offset)->limit($pagination->limit)->orderBy('id desc')->all();
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
        return $this->render('add',[
            'model'=>$model,
        ]);
    }

}
