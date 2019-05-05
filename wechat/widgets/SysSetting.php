<?php
namespace wechat\widgets;

use yii\base\Widget;

class SysSetting extends Widget
{
    public $type;
    public $field;

    public function init()
    {
        parent::init();

    }

    public function run()
    {
        $content = \common\models\SysSetting::getContent($this->type);
        $json_content = json_decode($content,true);
        if(!$json_content){
            return $content;
        }
        return isset($json_content[$this->field])?$json_content[$this->field]:'';
    }
}