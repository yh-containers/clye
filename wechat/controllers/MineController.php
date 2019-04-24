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
    }

    public function actionIndex()
    {

        return $this->render('index',[
            'user_model' => $this->user_model
        ]);
    }


}