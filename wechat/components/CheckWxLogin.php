<?php
namespace wechat\components;

use yii\base\BaseObject;

class CheckWxLogin extends BaseObject
{
    //微信对象
    public $wx_object;

    public function init()
    {
        parent::init();
        $session = \Yii::$app->session;
        $request = \Yii::$app->request;
        if($request->isGet && !$session->has('user_info')){//验证用户是否登录状态
            $this->wx_object = \Yii::createObject(\Yii::$app->components['wechat']);
            $code = $request->get('code');
            $state = $request->get('state');
            if($code && $state){

                try{
                    $info = $this->wx_object->getAuthInfo($code);
//                var_dump($info);
                    //用户模型
                    $model_user = \common\models\User::wechatAuth($this->wx_object,$info);
//                var_dump($model_user);exit;
                    //执行登录流程
                    \wechat\controllers\IndexController::handleAction($model_user);
                }catch (\Exception $e){
                    //授权异常
                    var_dump($e->getMessage());exit;
                }


            }else{
                //微信授权登录流程

                //当前请求地址
                $absoluteUrl = $request->absoluteUrl;
                //微信授权链接
                $auth_link = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.
                    $this->wx_object->appid.'&redirect_uri='.
                    urlencode($absoluteUrl).'&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect';
                \Yii::$app->response->redirect($auth_link);
            }
        }


    }
}