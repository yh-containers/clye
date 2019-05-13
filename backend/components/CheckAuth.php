<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/19
 * Time: 9:46
 */

namespace backend\components;


use yii\base\BaseObject;
use yii\base\Behavior;

class CheckAuth extends Behavior
{
    //忽略动作
    private $ignore_action = 'index/captcha,index/logout,index/login,index/index,debug/default/view';
    private $ignore_role_id = [1];

    public function init()
    {
        parent::init();
        \Yii::$app->on(\yii\base\Application::EVENT_BEFORE_ACTION, function ($event) {
            $current_action = \Yii::$app->requestedRoute;
            if(!empty($current_action) && stripos($this->ignore_action,$current_action)===false){
                //当前控制器
                $controller = $event->sender->controller;
                //用户模型
                $user_model = $controller->user_model;
                if(!in_array($user_model['rid'],$this->ignore_role_id)){
                    $opt_node = $user_model->getRoleNode();
                    if(stripos($opt_node,$current_action)===false){
                        if(\Yii::$app->request->isAjax){
                            //需要登录才能访问
                            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                            \Yii::$app->response->data = array(
                                'code' => 0,
                                'msg' => '无权操作'
                            );
                            \Yii::$app->response->send();
                            exit;//直接退出
                        }else{
                            //需要登录才能访问
                            \Yii::$app->response->data = '无权操作';
                            \Yii::$app->response->send();
                            exit;//直接退出
                        }
                    }

                }

            }

        });

    }
}