<?php
namespace common\models;


class SysLocationArea extends BaseModel
{
    const CACHE_SETTING_SYS_LOCATION_AREA='CACHE_SETTING_SYS_LOCATION_AREA';
    protected $use_create_time=false;

    public static function tableName()
    {
        return 'sys_location_area';
    }

    /**
     * 获取
     * */
    public static function getCacheData($is_flush=0)
    {
//        $cache_name = 'setting_SysLocationArea';
        $cache = \Yii::$app->cache;

        if($is_flush){
            //清空缓存
            $cache->delete(self::CACHE_SETTING_SYS_LOCATION_AREA);
        }

        $data = $cache->getOrSet(self::CACHE_SETTING_SYS_LOCATION_AREA, function () {
            $data = self::find()->asArray()->orderBy('sort asc')->all();
            return $data;
        });
        return $data;
    }

    /*
     * 日志记录
     * */
    public function getLogIntro(\yii\base\Event $event)
    {
        $content = '';
        $object = $event->sender;
        if($event->name==self::EVENT_AFTER_INSERT){
            $content = '系统设置/地区管理/行政区域-新增:'.$object['name'];
        }elseif ($event->name==self::EVENT_BEFORE_DELETE){
            $content = '系统设置/地区管理/行政区域-删除:'.$object['name'];
        }elseif ($event->name==self::EVENT_AFTER_UPDATE){
            $content = '系统设置/地区管理/行政区域-更新:'.$object['name'];
        }
        \Yii::$app->cache->delete(self::CACHE_SETTING_SYS_LOCATION_AREA);
        return $content;
    }

    public function getLinkAreaCity()
    {
        return $this->hasMany(SysLocation::className(),['aid'=>'id']);
    }
}