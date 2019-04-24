<?php
namespace frontend\controllers;

use yii\web\Controller;

class CommonController extends Controller
{

    public $user_id = 0;
    public $user_name = '';

    public $is_need_login = true;
    protected $ignore_action = '';
    /**
     * 用户模型
     * @var \common\models\SysManager
     * */
    protected $user_model;

    /**
     *
     * @var \yii\web\Request
     * */
    public $request;


    public function init()
    {
        parent::init();

        $this->request = \Yii::$app->request;


    }
}