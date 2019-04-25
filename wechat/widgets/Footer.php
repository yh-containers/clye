<?php
namespace wechat\widgets;

use yii\base\Widget;

class Footer extends Widget
{
    public $current_active;

    public function init()
    {
        parent::init();

    }

    public function run()
    {
        //获取商品数据
        $cart_num = \common\models\UserCart::find()->where(['uid'=>\Yii::$app->controller->user_id])->sum('num');

        return $this->render('footer',[
            'current_active'=>$this->current_active,
            'cart_num' => $cart_num
        ]);
    }
}