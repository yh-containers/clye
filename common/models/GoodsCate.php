<?php
namespace common\models;

use common\models\use_traits\SoftDelete;

class GoodsCate extends BaseModel
{
    use SoftDelete;
    public static function tableName()
    {
        return '{{%goods_cate}}';
    }

    public function attributeLabels()
    {
        return [
            'name'      => '分类名',
            'pid'       => '分类等级',
            'icon'      => '封面图',
        ];
    }




    public function rules()
    {
        return [
            [['name','pid'], 'required','message'=>'{attribute}不能为空'],
            ['sort','default','value'=>100],
            ['status','default','value'=>1],
            [['pid','img','name'],'safe']
        ];
    }
    public function getLinkChild()
    {
        return $this->hasMany(self::className(),['pid'=>'id'])->orderBy('sort asc');
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