<?php
namespace common\models;

use common\models\use_traits\SoftDelete;
use phpDocumentor\Reflection\DocBlock\Tags\Throws;

class User extends BaseModel
{
    //h5注册
    const SCENARIO_USER_WECHAT_REG= 'SCENARIO_USER_WECHAT_REG';
    const SCENARIO_USER_WECHAT_FORGET= 'SCENARIO_USER_WECHAT_FORGET';

    use SoftDelete;
    public $verify;
    public $qr_password;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }
    public function attributeLabels()
    {
        return [
            'phone' => '手机号码',
            'verify' => '验证码',
            'password' => '密码',
            'qr_password' => '确认密码',
        ];
    }

    public static function getSexInfo($type=null)
    {
        $data = ['未知','男','女'];
        if(is_null($type)){
            return $data;
        }else{
            return isset($data[$type])?$data[$type]:'';
        }
    }

    public function getPassword($event,$attribute)
    {
        if($this->isAttributeChanged($attribute) && !empty($this->password)){
            $salt = rand(10000,99999);
            $password = self::generatePwd($this->password,$salt);
            $this->setAttribute('salt',$salt);
            return $password;
        }

        return isset($this->oldAttributes['password'])?$this->oldAttributes['password']:$this->$attribute;
    }

    public function setUserName($event,$attribute)
    {
        if(empty($this->username)){
            $this->username = substr($this->phone,-4).'用户';
        }
        return $this->username;
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors[]=[
            'class' => \yii\behaviors\AttributesBehavior::className(),
            'attributes' =>  [
                'password'  =>[
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => [$this,'getPassword'],
                    \yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => [$this,'getPassword'],
                ],
                'username'  =>[
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => [$this,'setUserName'],
                ],
            ]
        ];
        //开启软删除
        $behaviors['softDeleteBehavior'] = [
            'class' => \yii2tech\ar\softdelete\SoftDeleteBehavior::className(),
            'softDeleteAttributeValues' => [
                self::getSoftDeleteField() => time(),
            ],
            'replaceRegularDelete' => true // mutate native `delete()` method
        ];
        return $behaviors;
    }

    /*
   * 生成用户密码
   * */
    public static function generatePwd($pwd,$salt)
    {
        return md5($salt.md5($pwd.$salt).$pwd.'_user');
    }

    /**
     * 处理用户短信验证登录无用户问题
     * @throws  \Exception
     * @return \common\models\User
     * */
    public static function handleVerifyLogin($phone,$verify)
    {
        if(empty($phone)) throw new \Exception('请输入手机号');
        if(empty($verify)) throw new \Exception('请输入验证码');
        //验证短信验证码

        \common\models\Sms::checkVerify($phone,$verify,1);


        $model_user = \common\models\User::find()->where(['phone'=>$phone])->one();
        if(empty($model_user)){
            $model_user = new self();
            $model_user->phone = $phone;
            $bool = $model_user->save();
            if(!$bool) throw new \Exception('创建异常');
        }

        return $model_user;

    }



    public function scenarios()
    {
        $scenarios =  parent::scenarios();
        $scenarios[self::SCENARIO_USER_WECHAT_REG]=$scenarios[self::SCENARIO_DEFAULT];
        $scenarios[self::SCENARIO_USER_WECHAT_FORGET]=['phone','verify','password','qr_password'];
        return $scenarios;
    }


    public function rules()
    {
        $rule = parent::rules();
        $rule = array_merge($rule,[
            ['face','default','value'=>'/assets/images/default.jpg'],
            ['type','default','value'=>0]
        ]);
        switch ($this->scenario){
            case self::SCENARIO_USER_WECHAT_REG: //h5用户注册
                $rule = array_merge($rule,[
                    [['phone','verify','password','qr_password'],'required','message'=>'{attribute}不能为空'],
                    ['phone','match','pattern'=>'/^1[0-9]{10}$/','message'=>'{attribute}请输入正确的手机号码'],
                    ['phone','unique','message'=>'该手机号已被使用'],
                    ['password','string','min'=>6, 'max' => 16,'message'=>'{attribute}位数为6至16位'],
                    ['password','compare','compareAttribute'=>'qr_password','message'=>'两次密码不一致'],
                    ['verify',function($attribute,$param){
                        if(!$this->hasErrors()){
                            try{
                                \common\models\Sms::checkVerify($this->phone,$this->verify,0);
                            }catch (\Exception $e){
                                $this->addError('verify','验证码错误..');
                            }
                        }
                    }],
                ]);
                break;
            case self::SCENARIO_USER_WECHAT_FORGET: //h5用户注册
                $rule = array_merge($rule,[
                    [['phone','verify','password','qr_password'],'required','message'=>'{attribute}不能为空'],
                    ['phone','match','pattern'=>'/^1[0-9]{10}$/','message'=>'{attribute}请输入正确的手机号码'],
                    ['phone','exist','message'=>'该手机号码未注册'],
                    ['password','string','min'=>6, 'max' => 16,'message'=>'{attribute}位数为6至16位'],
                    ['password','compare','compareAttribute'=>'qr_password','message'=>'两次密码不一致'],
                    ['verify',function($attribute,$param){
                        if(!$this->hasErrors()){
                            try{
                                \common\models\Sms::checkVerify($this->phone,$this->verify,2);
                            }catch (\Exception $e){
                                $this->addError('verify','验证码错误..');
                            }
                        }
                    }],
                ]);
                break;
            default:
                break;
        }
        return $rule;
    }


    //省
    public function getLinkProvince()
    {
        return $this->hasOne(SysLocation::className(),['id'=>'province']);
    }

    //市
    public function getLinkCity()
    {
        return $this->hasOne(SysLocation::className(),['id'=>'city']);
    }

    //区
    public function getLinkArea()
    {
        return $this->hasOne(SysLocation::className(),['id'=>'area']);
    }

    //行政区
    public function getLinkAreaInfo()
    {
        return $this->hasOne(SysLocationArea::className(),['id'=>'area_id']);
    }


    /*
     * 日志记录
     * */
    public function getLogIntro(\yii\base\Event $event)
    {
        $content = '';
        $object = $event->sender;
        if($event->name==self::EVENT_AFTER_INSERT){
            $content = '新增用户:'.$object['username'];
        }elseif ($event->name==self::EVENT_BEFORE_DELETE){
            $content = '删除用户:'.$object['username'];
        }elseif ($event->name==self::EVENT_AFTER_UPDATE){
            $content = '更新信息，用户:'.$object['username'];
        }
        return $content;
    }
}
