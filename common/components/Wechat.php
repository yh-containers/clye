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
        $cache = \Yii::$app->cache;
        $cache_name = 'wx_access_token';
        $access_token = $cache->get($cache_name);
        if(empty($access_token)){
            $param = [
                'grant_type' => 'client_credential',
                'appid' => $this->appid,
                'secret' => $this->appsecret,
            ];
            $result = \common\components\HttpCurl::req('https://api.weixin.qq.com/cgi-bin/token',$param);
//            var_dump($result);exit;
            $info = json_decode($result,true);
            if(empty($info)){
                throw new \Exception('获取access_token异常');
            }else{
                if(!empty($info['errcode'])){
                    //报错
                    throw new \Exception('异常:'.$info['errmsg'].' 错误代码:'.$info['errcode']);
                }else{
                    $access_token = $info['access_token'];
                    $cache->set($cache_name,$access_token,($info['expires_in']-1000));
                }
            }
        }
        return $access_token;
    }

    //设置所属行业
    public function setIndustry(array $industry)
    {
        $access_token = $this->getAccessToken();
        $data['industry_id1'] = $industry[0];
        isset($industry[1]) && $data['industry_id2'] = $industry[1];
        $result = \common\components\HttpCurl::req('https://api.weixin.qq.com/cgi-bin/template/api_set_industry?access_token='.$access_token,json_encode($data),'POST',[],true);
        $info = json_decode($result,true);
        if(empty($info)){
            throw new \Exception('获取access_token异常');
        }else{
            if(!empty($info['errcode'])){
                //报错
                throw new \Exception('异常:'.$info['errmsg'].' 错误代码:'.$info['errcode']);
            }else{
                return $info;
            }
        }
    }

    //设置所属行业
    public function getIndustry()
    {
        $access_token = $this->getAccessToken();
//        var_dump($access_token);exit;
        $param = [
            'access_token' => $access_token,
        ];
        $result = \common\components\HttpCurl::req('https://api.weixin.qq.com/cgi-bin/template/get_industry',$param);
        $info = json_decode($result,true);
        if(empty($info)){
            throw new \Exception('获取设置的行业信息异常');
        }else{
            if(!empty($info['errcode'])){
                //报错
                throw new \Exception('异常:'.$info['errmsg'].' 错误代码:'.$info['errcode']);
            }else{
                return $info;
            }
        }
    }

    //获取模版信息
    public function getTemp($template_id)
    {
        $access_token = $this->getAccessToken();
        $data['template_id_short'] = $template_id;
        $result = \common\components\HttpCurl::req('https://api.weixin.qq.com/cgi-bin/template/api_add_template?access_token='.$access_token,$data,'POST');
        $info = json_decode($result,true);
        if(empty($info)){
            throw new \Exception('获得模板ID异常');
        }else{
            if(!empty($info['errcode'])){
                //报错
                throw new \Exception('异常:'.$info['errmsg'].' 错误代码:'.$info['errcode']);
            }else{
                return $info;
            }
        }
    }

    //获取模板列表
    public function getTempList()
    {
        $access_token = $this->getAccessToken();
        $param = [
            'access_token' => $access_token,
        ];
        $result = \common\components\HttpCurl::req('https://api.weixin.qq.com/cgi-bin/template/get_all_private_template',$param);
        $info = json_decode($result,true);
        if(empty($info)){
            throw new \Exception('获得模板列表异常');
        }else{
            if(!empty($info['errcode'])){
                //报错
                throw new \Exception('异常:'.$info['errmsg'].' 错误代码:'.$info['errcode']);
            }else{
                return $info;
            }
        }
    }

    //删除模板
    public function delTemp($template_id)
    {
        $access_token = $this->getAccessToken();
        $data['template_id_short'] = $template_id;
        $result = \common\components\HttpCurl::req('https://api.weixin.qq.com/cgi-bin/template/del_private_template?access_token='.$access_token,$data,'POST');
        $info = json_decode($result,true);
        if(empty($info)){
            throw new \Exception('删除模板异常');
        }else{
            if(!empty($info['errcode'])){
                //报错
                throw new \Exception('异常:'.$info['errmsg'].' 错误代码:'.$info['errcode']);
            }else{
                return $info;
            }
        }
    }

    //发送模板消息
    public function sendTemp($open_id,$template_id,array $data,$url='',array $miniprogram=[])
    {
        $access_token = $this->getAccessToken();
        $data=[
            'touser'  => $open_id,
            'template_id' => $template_id,
            'data' => $data,
        ];

        !empty($url) && $data['url']=$url;
        !empty($miniprogram) && $data['miniprogram']=$miniprogram;

        $result = \common\components\HttpCurl::req('https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$access_token,json_encode($data,JSON_UNESCAPED_UNICODE),'POST',[],true);
        $info = json_decode($result,true);
        if(empty($info)){
            throw new \Exception('删除模板异常');
        }else{
            if(!empty($info['errcode'])){
                //报错
                throw new \Exception('异常:'.$info['errmsg'].' 错误代码:'.$info['errcode']);
            }else{
                return $info;
            }
        }
    }
}