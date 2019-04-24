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
        return $this->render('footer',[
            'current_active'=>$this->current_active,
        ]);
    }
}