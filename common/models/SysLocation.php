<?php
namespace common\models;

use common\models\use_traits\SoftDelete;

class SysLocation extends BaseModel
{
    use SoftDelete;
    const CACHE_SETTING_SYS_LOCATION_PROVINCE = 'province';

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

    //获取省份
    public static function getCacheProvince($is_flush=false)
    {
        $cache = \Yii::$app->cache;

        if($is_flush){
            //清空缓存
            $cache->delete(self::CACHE_SETTING_SYS_LOCATION_PROVINCE);
        }

        $data = $cache->getOrSet(self::CACHE_SETTING_SYS_LOCATION_PROVINCE, function () {
            $data = self::find()->where(['pid'=>1])->asArray()->orderBy('sort asc')->all();
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
            $content = '新增地区:'.$object['name'];
        }elseif ($event->name==self::EVENT_BEFORE_DELETE){
            $content = '删除地区:'.$object['name'];
        }elseif ($event->name==self::EVENT_AFTER_UPDATE){
            $content = '更新地区:'.$object['name'];
        }
        return $content;
    }
}