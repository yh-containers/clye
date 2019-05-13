<?php
namespace common\models;

use common\models\use_traits\SoftDelete;
use phpDocumentor\Reflection\DocBlock\Tags\Throws;

class UserReqUp extends BaseModel
{

    use SoftDelete;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user_req_up}}';
    }

    //创建时间
    public static function getStatusName($status)
    {
        $data =['创建','通过','拒绝'];
        return isset($data[$status])?$data[$status]:'--';
    }


    //
    public function getLinkUser()
    {
        return $this->hasOne(User::className(),['id'=>'uid']);
    }
    /*
     * 日志记录
     * */
    public function getLogIntro(\yii\base\Event $event)
    {
        $content = '';
        $object = $event->sender;
        if($event->name==self::EVENT_AFTER_INSERT){
            $content = '新增用户申请信息状态:'.self::getStatusName($object['status']);
        }elseif ($event->name==self::EVENT_BEFORE_DELETE){
            $content = '删除用户申请信息:'.self::getStatusName($object['status']);
        }elseif ($event->name==self::EVENT_AFTER_UPDATE){
            $content = '更新用户申请信息:'.self::getStatusName($object['status']);
        }
        return $content;
    }
}
