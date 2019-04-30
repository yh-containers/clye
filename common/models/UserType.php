<?php
namespace common\models;

use common\models\use_traits\SoftDelete;
use phpDocumentor\Reflection\DocBlock\Tags\Throws;

class UserType extends BaseModel
{

    use SoftDelete;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user_type}}';
    }
    public function attributeLabels()
    {
        return [
            'name' => '用户类型名',
            'per' => '比例',
            'sort'      => '排序',
        ];
    }

    public function rules()
    {
        return [
            [['name'],'required','message'=>'{attribute}必须输入'],
            ['sort','number','min'=>0,'max'=>100,'tooSmall'=>'{attribute}不得低于{min}','tooBig'=>'{attribute}不得高于{max}','message'=>'{attribute}必须是数字'],
            ['per','number','min'=>0,'max'=>1,'tooSmall'=>'{attribute}不得低于{min}','tooBig'=>'{attribute}不得高于{max}','message'=>'{attribute}必须是数字'],
        ];
    }


    /*
     * 日志记录
     * */
    public function getLogIntro(\yii\base\Event $event)
    {
        $content = '';
        $object = $event->sender;
        if($event->name==self::EVENT_AFTER_INSERT){
            $content = '新增用户类型:'.$object['name'];
        }elseif ($event->name==self::EVENT_BEFORE_DELETE){
            $content = '删除用户类型:'.$object['name'];
        }elseif ($event->name==self::EVENT_AFTER_UPDATE){
            $content = '更新用户类型:'.$object['name'];
        }
        return $content;
    }
}
