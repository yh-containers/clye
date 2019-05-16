<?php
namespace backend\widgets;

use yii\base\Widget;

class OrderNoHandler extends Widget
{

    public function init()
    {
        parent::init();

    }

    public function run()
    {
        $model_manager = \Yii::$app->controller->user_model;
        return \common\models\Order::noHandleNum($model_manager['province'],$model_manager['id'],\Yii::$app->controller->is_super_manager);
    }
}