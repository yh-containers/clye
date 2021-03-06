<?php
namespace common\models;

use common\models\use_traits\SoftDelete;
use phpDocumentor\Reflection\DocBlock\Tags\Throws;

class User extends BaseModel
{
    //h5注册
    const SCENARIO_USER_WECHAT_REG= 'SCENARIO_USER_WECHAT_REG';
    const SCENARIO_USER_WECHAT_FORGET= 'SCENARIO_USER_WECHAT_FORGET';
    const SCENARIO_USER_MOD_PWD= 'SCENARIO_USER_MOD_PWD';
    const SCENARIO_USER_MOD_PHONE= 'SCENARIO_USER_MOD_PHONE';

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

    public static function getUserTypePoint($type=null,$field=null,$type_val=null)
    {
        $data = [
            ['type'=>1,'name'=>'A类用户'],
            ['type'=>0,'name'=>'B类用户'],
        ];
        //强制返回用户类型
        if(!is_null($type_val)){
            foreach ($data as $vo){
                if($vo['type']==$type_val){
                    if(is_null($field)){
                        return $vo;
                    }else{
                        return isset($vo[$field])?$vo[$field]:'';
                    }
                    break;
                }
            }
            return false;
        }

        if(is_null($type)){
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


    public static function getSexInfo($type=null)
    {
        $data = ['未知','男','女','保密'];
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

    /**
     * 添加购物车
     * @param $gid int 商品id
     * @param $num int 数量
     * @param $mod bool 调整数量
     * @return bool
     */
    public function addShoppingCart($gid,$num=1,$mod=false)
    {
        $bool = true;
        $model = UserCart::find()->where(['uid'=>$this->id,'gid'=>$gid])->one();
        if(!empty($model)){
            if($num<0 && $model->num<=1){

            }elseif($mod){
                $model->num=$num;
                $bool = $model->save();
            }else{
                $bool = $model->updateCounters(['num'=>$num]);
            }
        }else{
            $model = new UserCart();
            $model->uid = $this->id;
            $model->gid = $gid;
            $model->num = $num;
            $bool = $model->save(false);
        }
        return $bool;
    }
    /**
     * 商品收藏
     * @param $goods_id int|array 商品id
     * @param $is_del bool 是否强制删除
     * @throws
     * @return array
     */
    public function goodsCol($goods_id,$is_del=false)
    {
        if(is_array($goods_id)){
            foreach ($goods_id as $gid){
                $this->_handleGoodsCol($gid,true,$is_del);
            }
            return [true,1];
        }else{
            return $this->_handleGoodsCol($goods_id,false,$is_del);
        }

    }
    /**
     * 收藏动作商品
     * @param $gid int 商品id
     * @param $is_force bool 强制收藏数据
     * @param $is_del bool 是否强制删除
     * @return array
     * */
    private function _handleGoodsCol($gid,$is_force=false,$is_del=false)
    {
        $bool = true;
        // 1添加收藏  0取消收藏
        $is_col = 1;
        $model = UserCol::find(true)->where(['uid'=>$this->id,'gid'=>$gid])->one();

        if(!empty($model)){
            $soft_field = UserCol::getSoftDeleteField();
            if(!$is_del && ($is_force || !empty($model[$soft_field]))){
                //有收藏记录 被删除 现在重新收藏
                $model->$soft_field = null;
                $model->col_time = date('Y-m-d H:i:s');
                $state = $model->save();
                $bool = $state!==false?true:false;
            }else{
                $is_col = 0;
                //取消收藏
                $model->$soft_field = time();
                $state = $model->save();
                $bool = $state!==false?true:false;
            }
        }else{
            if(!$is_del){
                //新增收藏
                $model = new UserCol();
                $model->uid = $this->id;
                $model->gid = $gid;
                $model->col_time = date('Y-m-d H:i:s');
                $bool = $model->save(false);
            }

        }
        return [$bool,$is_col];
    }

    /**
     * 购物车商品选中和取消选中效果
     * @var $cart_id int 购物车id
     * @var $is_full_checked int|null 全选和反全选
     * @return array
     */
    public function cartChoose($cart_id,$is_full_checked=null)
    {
        // 1选中 0未选中
        $is_checked = 1;
        $bool = true;
        if(!is_null($is_full_checked)){
            if(!$is_full_checked){
                $is_checked=0;
            }
            //全选
            UserCart::updateAll(['is_checked'=>$is_full_checked?1:0],['uid'=>$this->id]);
        }else{
            $model = UserCart::find()->where(['uid'=>$this->id,'id'=>$cart_id])->one();
            if(empty($model)){

            }else{
                if($model->is_checked==1){
                    //取消选中
                    $model->is_checked = 0;
                    $is_checked = 0;
                }else{
                    $model->is_checked = 1;
                }
                $bool = $model->save(false);
            }
        }

        return [$bool,$is_checked];
    }

    /**
     * 删除购物车
     * */
    public function cartDel(array $cart_id)
    {
        $ids = [];
        foreach ($cart_id as $vo){
            if((is_numeric($vo) && $vo>0 )){
                $ids[] = $vo;
            }
        }
        if(!empty($ids)){
            UserCart::deleteAll(['uid'=>$this->id,'id'=>$ids]);
        }
    }

    /**
     * 微信授权登录处理
     * @param $info array 配置信息
     * @throws
     * @return User
     * */
    public static function wechatAuth(\yii\base\BaseObject $wx_obj,$info)
    {
        if(empty($info['openid'])) throw new \Exception('微信openid参数异常');

        $model_user = self::find()->where(['openid'=>$info['openid']])->one();
        if(empty($model_user)){
            if(empty($info['access_token'])) throw new \Exception('微信 access_token 参数异常');
            //获取用户资料
            $user_info = $wx_obj->getUserInfo($info['access_token'],$info['openid']);
            $model_user = new self();
            $model_user->username = $user_info['nickname'];
            $model_user->sex = $user_info['sex'];
            $model_user->face = $user_info['headimgurl'];
            //默认用户类型
            $model_user->type = 1;

            $model_user->openid = $info['openid'];
            $model_user->wx_access_token = $info['access_token'];
            $model_user->refresh_token = $info['refresh_token'];
            $model_user->wx_auth_time = time();
//            var_dump($model_user->getAttributes());exit;
            //直接入库
            $save_bool = $model_user->save(false);
            if(!$save_bool) throw new \Exception('保存用户信息异常');
        }
//        var_dump($model_user->getAttributes());exit;
        return $model_user;
    }

    //设置微信信息
    public function setWxInfo($event,$attribute)
    {
        //验证是否有微信授权信息
        if(\Yii::$app->session->has(\common\components\Wechat::WX_AUTH_USER_INFO)){
            $wx_info = \Yii::$app->session->get(\common\components\Wechat::WX_AUTH_USER_INFO);

            !empty($wx_info['nickname']) && $this->setAttribute('username',$wx_info['nickname']);
            !empty($wx_info['sex']) && $this->setAttribute('sex',$wx_info['sex']);
            !empty($wx_info['headimgurl']) && $this->setAttribute('face',$wx_info['headimgurl']);
            !empty($wx_info['openid']) && $this->setAttribute('openid',$wx_info['openid']);
            !empty($wx_info['access_token']) && $this->setAttribute('wx_access_token',$wx_info['access_token']);
            !empty($wx_info['refresh_token']) && $this->setAttribute('refresh_token',$wx_info['refresh_token']);
            $this->setAttribute('wx_auth_time',time());
        }

        return empty($this->openid)?null:$this->openid;
    }

    //操作-修改密码
    public function modPwd($mod_info)
    {
        if(empty($mod_info['verify']))  throw new \Exception('请输入验证码');
        if(empty($mod_info['password']))  throw new \Exception('请输入密码');
        if(strlen($mod_info['password'])<6)  throw new \Exception('密码不得低于6位');
        if(strlen($mod_info['password'])>16)  throw new \Exception('密码不得高于16位');
        if(empty($mod_info['re_password']))  throw new \Exception('请输入确认密码');
        if($mod_info['re_password']!=$mod_info['password'])  throw new \Exception('两次密码不一致');

        //验证码
        Sms::checkVerify($this->phone,$mod_info['verify'],3);

        $this->password = $mod_info['password'];
        $bool = $this->save(false);
        if(!$bool){
            throw new \Exception('修改异常');
        }
    }
    //操作-修改手机号码
    public function modPhone($mod_info)
    {
        if(empty($mod_info['verify']))  throw new \Exception('请输入原始手机号的验证码');
        if(empty($mod_info['new_verify']))  throw new \Exception('请输入更换的手机验证码');
        if(empty($mod_info['new_phone']))  throw new \Exception('请输入更换的手机号');
        if(!preg_match('/^1[0-9]{10}$/',$mod_info['new_phone']))  throw new \Exception('请输入正确的手机号码');
        if($this->phone==$mod_info['new_phone'])  throw new \Exception('更换的手机号码不能跟原手机号一致');
        //验证新手机号码是否已注册
        if(self::find()->where(['phone'=>$mod_info['new_phone']])->count()){
            throw new \Exception('更换的手机号已被注册,无法进行更换');
        }

        //验证码
        Sms::checkVerify($this->phone,$mod_info['verify'],4);
        Sms::checkVerify($mod_info['new_phone'],$mod_info['verify'],5);

        $this->phone = $mod_info['new_phone'];
        $bool = $this->save(false);
        if(!$bool){
            throw new \Exception('修改异常');
        }
    }


    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors[]=[
            'class' => \yii\behaviors\AttributesBehavior::className(),
            'attributes' =>  [
                'openid'  =>[
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => [$this,'setWxInfo'],
                ],
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
            ['type','default','value'=>1],
            ['cg_type','default','value'=>0],
            ['sex','default','value'=>0],
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
                    ['phone','match','pattern'=>'/^1[0-9]{10}$/','message'=>'请输入正确的手机号码'],
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
                $rule = array_merge($rule,[
                    [['type','sex','username','phone','province','city','area','area_id','openid','face','email','company_addr',
                    'password','salt','company_name','contacts','money','history_money','auth_key','access_token','status'],
                    'safe']
                ]);
                break;
        }
        return $rule;
    }


    //用户类型
    public function getLinkType()
    {
        return $this->hasOne(UserType::className(),['id'=>'type']);
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
