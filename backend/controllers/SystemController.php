<?php
namespace backend\controllers;

class SystemController extends CommonController
{

    public function actionIndex()
    {
        return $this->render('index',[

        ]);
    }

    //系统角色
    public function actionRoles()
    {
        $model = \common\models\SysRole::find()->with(['linkRoles'])->where(['pid'=>0])->orderBy('sort asc')->all();
        return $this->render('roles',[
            'model'  => $model,
        ]);
    }

    /*
    * 管理员--新增
    * */
    public function actionRolesAdd()
    {

        $id = $this->request->get('id',0);
        $model = new \common\models\SysRole();
        if($this->request->isAjax){
            $php_input = $this->request->post();
            if(empty($php_input['password']))  unset($php_input['password']);
//            var_dump($php_input);exit;
            $result = $model->actionSave($php_input);
            return $this->asJson($result);
        }
        $top_role = \common\models\SysRole::find()->asArray()->where(['pid'=>0,'status'=>1])->all();
        $model = $model::findOne($id);
        return $this->render('rolesAdd',[
            'model' => $model,
            'top_role' => $top_role
        ]);
    }



    //管理员--删除
    public function actionRolesDel()
    {

        $id = $this->request->get('id');
        $model = new \common\models\SysRole();
        $result = $model->actionDel(['id'=>$id]);
        return $this->asJson($result);
    }

    /*
     * 常规设置
     * */
    public function actionSetting()
    {
        $normal_content = \common\models\SysSetting::getContent('normal');
        $normal_content = json_decode($normal_content,true);
        //使用说明
        $problem_content = \common\models\SysSetting::getContent('problem');
        $problem_content = explode(',',$problem_content);
        return $this->render('setting',[
            'normal_content'  => $normal_content,
            'problem_content'  => $problem_content
        ]);
    }

    /*
     * 保存动作
     * */
    public function actionSettingSave()
    {
        $type = $this->request->post('type');
        $content = $this->request->post('content');
        try{
            if(is_array($content)){
                $key=key($content);
                if(is_numeric($key)){
                    $content = array_filter($content);
                    $content = implode(',',$content);

                }else{
                    $content = json_encode($content);
                }
            }
            \common\models\SysSetting::setContent($type,$content);
            return $this->asJson(['code'=>1,'msg'=>'保存成功']);
        }catch (\Exception $e) {
            return $this->asJson(['code'=>0,'msg'=>'保存异常:'.$e->getMessage()]);
        }
    }

    /*
     * 管理员列表
     * */
    public function actionManage()
    {
        $query = \common\models\SysManager::find();
        $count = $query->count();
        $pagination = \Yii::createObject(array_merge(\Yii::$app->components['pagination'],['totalCount'=>$count]));
        $list = $query->with(['linkRole.linkParentRoles','linkAreaName'])->offset($pagination->offset)->limit($pagination->limit)->all();
        return $this->render('manage',[
            'list'  => $list,
            'pagination' => $pagination
        ]);
    }

    /*
    * 管理员--新增
    * */
    public function actionManageAdd()
    {

        $id = $this->request->get('id',0);
        $model = new \common\models\SysManager();
        if($this->request->isAjax){
            $php_input = $this->request->post();
            if(empty($php_input['password']))  unset($php_input['password']);

            $result = $model->actionSave($php_input);
            return $this->asJson($result);
        }

        $model = $model::findOne($id);
        //角色
        $roles = \common\models\SysRole::find()->asArray()->with(['linkRoles'=>function($query){
            return $query->where(['status'=>1]);
        }])->where(['pid'=>0,'status'=>1])->orderBy('sort asc')->all();
        //行政区
        $area = \common\models\SysLocationArea::getCacheData();
        return $this->render('manageAdd',[
            'model' => $model,
            'roles' => $roles,
            'area' => $area,
        ]);
    }



    //管理员--删除
    public function actionManageDel()
    {

        $id = $this->request->get('id');
        $model = new \common\models\SysManager();
        $result = $model->actionDel(['id'=>$id]);
        return $this->asJson($result);
    }

    //系统日志
    public function actionLogs()
    {
        $query = \common\models\SysOptLogs::find();
        $count = $query->count();
        $pagination = \Yii::createObject(array_merge(\Yii::$app->components['pagination'],['totalCount'=>$count]));
        $list = $query->with(['linkManager'])->offset($pagination->offset)->limit($pagination->limit)->orderBy('id desc')->all();

        return $this->render('logs',[
            'list'  => $list,
            'pagination' => $pagination
        ]);
    }

    //轮播图设置
    public function actionFlowImg()
    {
        $query = \common\models\Images::find()->where(['type'=>0]);
        $count = $query->count();
        $pagination = \Yii::createObject(array_merge(\Yii::$app->components['pagination'],['totalCount'=>$count]));
        $list = $query->offset($pagination->offset)->limit($pagination->limit)->orderBy('sort asc')->all();
        return $this->render('flowImg',[
            'list'  => $list,
            'pagination' => $pagination
        ]);
    }

    //轮播图设置
    public function actionFlowImgAdd()
    {

        $id = $this->request->get('id',0);
        $model = new \common\models\Images();
        if($this->request->isAjax){
            $php_input = $this->request->post();
            $model->scenario=\common\models\Images::SCENARIO_FLOW_IMAGE;
            $result = $model->actionSave($php_input);
            return $this->asJson($result);
        }

        $model = $model::findOne($id);
        return $this->render('flowImgAdd',[
            'model' => $model,
        ]);
    }

    //轮播图--删除
    public function actionFlowImgDel()
    {
        $id = $this->request->get('id');
        $model = new \common\models\Images();
        $result = $model->actionDel(['id'=>$id]);
        return $this->asJson($result);
    }

}
