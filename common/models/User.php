<?php
namespace common\models;

use common\models\use_traits\SoftDelete;

class User extends BaseModel
{
    use SoftDelete;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
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
