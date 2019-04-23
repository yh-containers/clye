<?php
namespace common\models;

use common\models\use_traits\SoftDelete;

class Goods extends BaseModel
{
    use SoftDelete;
    public static function tableName()
    {
        return '{{%goods}}';
    }

    public function attributeLabels()
    {
        return [
            'name'      => '商品名',
            'cid'       => '分类',
            'img'       => '图片',
            'intro'     => '简介',
            'content'   => '详细',
        ];
    }



    public function rules()
    {
        return [
            [['name','cid','img','intro','content'], 'required','message'=>'{attribute}不能为空'],
            ['name','string','length'=>[1,255],'tooLong'=>'{attribute}不得超过{max}个字符','tooShort'=>'{attribute}不得低于{min}个字符'],
            ['price','number','min'=>0.01,'max'=>99999999,'tooSmall'=>'{attribute}不得低于{min}','tooBig'=>'{attribute}不得高于{max}','message'=>'{attribute}必须是数字'],
            ['stock','number','min'=>0,'max'=>99999999,'tooSmall'=>'{attribute}不得低于{min}','tooBig'=>'{attribute}不得高于{max}','message'=>'{attribute}必须是数字'],
            ['sort','number','min'=>0,'max'=>100,'tooSmall'=>'{attribute}不得低于{min}','tooBig'=>'{attribute}不得高于{max}','message'=>'{attribute}必须是数字'],
            ['sort','default','value'=>100],
            ['is_hot','default','value'=>0],
            ['status','default','value'=>1],
            [['cid','img','name'],'safe']
        ];
    }


    public function getLinkCate()
    {
        return $this->hasOne(GoodsCate::className(),['id'=>'cid']);
    }


    /*
     * 日志记录
     * */
    public function getLogIntro(\yii\base\Event $event)
    {
        $content = '';
        $object = $event->sender;
        if($event->name==self::EVENT_AFTER_INSERT){
            $content = '新增商品分类:'.$object['name'];
        }elseif ($event->name==self::EVENT_BEFORE_DELETE){
            $content = '删除商品分类:'.$object['name'];
        }elseif ($event->name==self::EVENT_AFTER_UPDATE){
            $content = '更新商品分类:'.$object['name'];
        }
        return $content;
    }
}