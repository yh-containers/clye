<?php
namespace common\models;


use common\models\use_traits\SoftDelete;

class HotKw extends BaseModel
{
    use SoftDelete;
    public static function tableName()
    {
        return '{{%hot_kw}}';
    }

    public function attributeLabels()
    {
        $attributeLabels = parent::attributeLabels();
        return array_merge($attributeLabels,[
            'keyword'   => '关键字',
            'sort'   => '排序',
            'status'   => '状态',
        ]);
    }


    public function rules()
    {
        return [
            [['keyword'], 'required','message'=>'{attribute}必须输入'],
            [['keyword'], 'string','length'=>[1,10],'tooLong'=>'{attribute}不得超过{max}个字符','tooShort'=>'{attribute}不得低于{min}个字符'],
            ['sort','number','min'=>0,'max'=>100,'tooSmall'=>'{attribute}不得低于{min}','tooBig'=>'{attribute}不得高于{max}','message'=>'{attribute}必须是数字'],
            //默认值
            [['sort'],'default', 'value' => 100],
            [['status'],'default', 'value' => 1],
        ];
    }


    /*
     * 日志记录
     * */
    public function getLogIntro(\yii\base\Event $event)
    {
        $content = '';
        $object = $event->sender;
        if($event->name==ActiveRecord::EVENT_AFTER_INSERT){
            $content = '新增关键字:'.$object['keyword'];
        }elseif ($event->name==ActiveRecord::EVENT_BEFORE_DELETE){
            $content = '删除关键字:'.$object['keyword'];
        }elseif ($event->name==ActiveRecord::EVENT_AFTER_UPDATE){
            $content = '更新关键字:'.$object['keyword'];
        }
        return $content;
    }
}