<?php
namespace backend\controllers;

class WechatController extends CommonController
{
    //消息模版
    public function actionMessage()
    {


        return $this->render('message',[

        ]);
    }
}
