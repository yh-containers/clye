<?php
namespace common\models;

use common\models\use_traits\SoftDelete;
use yii\db\ActiveRecord;

class SysManager extends BaseModel
{
    use SoftDelete;
    const Logs_name = '管理员';
    public static function tableName()
    {
        return 'sys_manager';
    }

    public function attributeLabels()
    {
        $attributeLabels = parent::attributeLabels();
        return array_merge($attributeLabels,[
            'name'      => '用户名',
            'account'   => '帐号',
            'rid'       => '角色',
            'area_id'   => '行政区',
            'password'  => '密码',
        ]);
    }

    /*
     * 获取用户角色
     * */
    public function getUserRoleName()
    {
        return !empty($this['linkRole'])?(!empty($this['linkRole']['linkParentRoles'])?($this['linkRole']['linkParentRoles']['name'].'('.$this['linkRole']['name'].')'):$this['linkRole']['name']):'--';
    }

    //获取用户角色节点信息
    public function getRoleNode()
    {
        $info = SysRole::findOne($this->rid);
        return $info['node'];
    }

    public function getPassword($event)
    {
        if($this->isAttributeChanged('password') && !empty($this->password)){
            $salt = rand(10000,99999);
            $password = self::generatePwd($this->password,$salt);
            $this->setAttribute('salt',$salt);
            return $password;
        }else{
            return $this->oldAttributes['password'];
        }

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

    public function rules()
    {
        return [
            [['name','account'], 'required','message'=>'{attribute}必须输入'],
            [['name'], 'string','length'=>[2,15],'tooLong'=>'{attribute}不得超过{max}个字符','tooShort'=>'{attribute}不得低于{min}个字符'],
            [['rid'], 'required','message'=>'请选择管理员{attribute}'],
            [['area_id'], 'required','message'=>'请选择管理员{attribute}'],
            [['account'], 'string','length'=>[4,15],'tooLong'=>'{attribute}不得超过{max}个字符','tooShort'=>'{attribute}不得低于{min}个字符'],
            [['account'], 'unique','message'=>'{attribute}帐号已使用'],
            [['password'], 'string', 'when' => function ($model,$attribute) {
                return empty($model->$attribute);
            },'length'=>[6,15],'tooLong'=>'{attribute}不得超过{max}个字符','tooShort'=>'{attribute}不得低于{min}个字符'],
            [['password'],'required','when' => function ($model) {
                return empty($model->id);
            },'message'=>'{attribute}不能为空'],
            //默认值
            [['status'],'default', 'value' => 1]
        ];
    }


    /*
   * 生成用户密码
   * */
    public static function generatePwd($pwd,$salt)
    {
        return md5($salt.md5($pwd.$salt).$pwd.'_backend');
    }

    /**
     * 用户角色
     * */
    public function getLinkRole()
    {
        return $this->hasOne(SysRole::className(),['id'=>'rid']);
    }

    /**
     * 管理区域
     * */
    public function getLinkAreaName()
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
        if($event->name==ActiveRecord::EVENT_AFTER_INSERT){
            $content = '新增管理员:'.$object['name'];
        }elseif ($event->name==ActiveRecord::EVENT_BEFORE_DELETE){
            $content = '删除管理员:'.$object['name'];
        }elseif ($event->name==ActiveRecord::EVENT_AFTER_UPDATE){
            $content = '更新管理员:'.$object['name'];
        }
        return $content;
    }
}