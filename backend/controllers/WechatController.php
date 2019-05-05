<?php
namespace backend\controllers;

class WechatController extends CommonController
{
    //消息模版
    public function actionMessage()
    {
        $wx_object = \Yii::createObject(\Yii::$app->components['wechat']);
        $industry = $wx_object->getIndustry();
        $temp = $wx_object->getTempList();
        return $this->render('message',[
            'temp' => isset($temp['template_list'])?$temp['template_list']:[],
            'industry' => $industry
        ]);
    }
}
