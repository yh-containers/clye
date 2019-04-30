<?php
namespace common\components;

use yii\base\BaseObject;

class Wechat extends BaseObject
{
    //开发者id
    public $appid;
    //开发者密码
    public $appsecret;


    //获取微信授权登录信息
    public function getAuthInfo($code)
    {
        //换取微信信息
        $param = [
            'appid' => $this->appid,
            'secret' => $this->appsecret,
            'code' => $code,
            'grant_type' => 'authorization_code',
        ];
        $result = \common\components\HttpCurl::req('https://api.weixin.qq.com/sns/oauth2/access_token',$param);
        $info = json_decode($result,true);
        if(empty($info)){
            throw new \Exception('授权信息异常');
        }else{
            if(!empty($info['errcode'])){
                //报错
                throw new \Exception('授权异常:'.$info['errmsg'].' 错误代码:'.$info['errcode']);
            }else{
                return $info;
            }
        }
    }

    //获取用户基本信息
    public function getUserInfo($access_token, $openid)
    {
        $param = [
            'access_token' => $access_token,
            'openid' => $openid,
        ];
        $result = \common\components\HttpCurl::req('https://api.weixin.qq.com/sns/userinfo',$param);
        $info = json_decode($result,true);
        if(empty($info)){
            throw new \Exception('获取用户信息异常');
        }else{
            if(!empty($info['errcode'])){
                //报错
                throw new \Exception('异常:'.$info['errmsg'].' 错误代码:'.$info['errcode']);
            }else{
                return $info;
            }
        }
    }

    //获取凭证
    public function getAccessToken()
    {
        
        file_get_contents('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$this->appid.'&secret='.$this->appsecret);
    }
}