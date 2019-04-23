<?php
namespace common\models;

use common\models\use_traits\SoftDelete;

class SysLocation extends BaseModel
{
    use SoftDelete;
    protected $use_create_time = false;
    public static function tableName()
    {
        return 'sys_location';
    }

    public function attributeLabels()
    {
        return [
        ];
    }

    public function getLinkChild()
    {
        return $this->hasMany(self::className(),['pid'=>'id'])->orderBy('sort asc');
    }


    public function rules()
    {
        return [
            [['name'], 'required','message'=>'城市名必须输入'],
            [['pid','aid','name','type','sort'],'safe']
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
            $content = '新增地区:'.$object['name'];
        }elseif ($event->name==self::EVENT_BEFORE_DELETE){
            $content = '删除地区:'.$object['name'];
        }elseif ($event->name==self::EVENT_AFTER_UPDATE){
            $content = '更新地区:'.$object['name'];
        }
        return $content;
    }
}