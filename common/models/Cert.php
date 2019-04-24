<?php
namespace common\models;

use common\models\use_traits\SoftDelete;

class Cert extends BaseModel
{
    use SoftDelete;
    public static function tableName()
    {
        return 'sys_cert_img';
    }

    public function attributeLabels()
    {
        return [
            'title'         =>  '标题',
            'img'           =>  '证书',
            'sort'          =>  '排序',
            'status'        =>  '状态',
        ];
    }






    public function rules()
    {
        return [
            [['title','img'],'required','message'=>'{attribute}必须输入'],
            ['sort','number','min'=>0,'max'=>100,'tooSmall'=>'{attribute}不得低于{min}','tooBig'=>'{attribute}不得高于{max}','message'=>'{attribute}必须是数字'],
            ['status','default','value'=>1],
            ['sort','default','value'=>100],
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
            $content = '新增证书:'.$object['title'];
        }elseif ($event->name==self::EVENT_BEFORE_DELETE){
            $content = '删除证书:'.$object['title'];
        }elseif ($event->name==self::EVENT_AFTER_UPDATE){
            $content = '更新证书:'.$object['title'];
        }
        return $content;
    }
}