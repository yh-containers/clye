<?php
namespace common\models;


class Sms extends BaseModel
{
    public $use_create_time=false;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%sms}}';
    }

    //发送邮件
    public static function sendMail($phone,$type)
    {
        if(empty($phone))  throw new \Exception('邮箱不能为空');
        $info = self::getTypeInfo($type);
        if(empty($info)) throw new \Exception('发送类型异常');

        list($content,$data) = self::handleContent($info['content']);
        $info = null;
        try{
            //发送信息
            $info = \common\components\Sms::send($content,$phone);

        }catch (\Exception $e){
            $info = $e->getMessage();
        }
        $model = new self();
        $model->setAttributes([
            'type'      => $type,
            'phone'     => $phone,
            'content'   => $content,
            'verify'    => isset($data['{__VERIFY__}'])?$data['{__VERIFY__}']:null,
            'status'    => 1,
            'info'=> $info,
            'create_time'=> time(),
        ],false);
        $model->save();
        return is_null($info);
     }

    public static function getTypeInfo($type=null,$field=null)
    {
        $data = [
            ['name'=>'用户注册','content'=>'欢迎您登录华祖百草通，您本次注册的验证码为{__VERIFY__}'],
            ['name'=>'短信登录','content'=>'欢迎您登录华祖百草通，您本次登录验证码为{__VERIFY__}'],
            ['name'=>'忘记密码','content'=>'欢迎您登录华祖百草通，您本次找回密码的验证码为{__VERIFY__}'],
        ];
        if(is_null($data)){
            return $data;
        }else{
            $info = isset($data[$type])?$data[$type]:[];
            if(is_null($field)){
                return $info;
            }else{
                return isset($info[$field])?$info[$field]:'';
            }
        }
    }

    public static function handleContent($content)
    {
        $replace_info = [];
        preg_match_all('/\{[^}]+\}/',$content,$matches);
        $matches = $matches[0];
        if($matches){
            foreach ($matches as $vo){
                $replace_info[$vo] = self::getTempVarValue($vo);
            }
            $content = str_replace(array_keys($replace_info),array_values($replace_info),$content);
        }
        return [$content,$replace_info];

    }

    public static function getTempVarValue($temp)
    {
        if($temp=='{__VERIFY__}'){
            return rand(10000,99999);
        }
        return null;
    }


    //发送短信验证码
    public static function checkVerify($phone,$verify,$type=0)
    {
        //内部测试密码
        if($verify==1234) return;

        $where = [
            'phone'=>$phone,
            'type'=>$type,
        ];
        $info = self::find()->where($where)->limit(1)->orderBy('id desc')->one();
        if(empty($info)) throw new \Exception('验证码错误');
        if($info['verify']!=$verify) throw new \Exception('验证码错误.');
        if($info['status']!=1) throw new \Exception('验证码已使用');

        $info->status = 2;
        $info->use_time = time();
        $info->save();

    }
}
