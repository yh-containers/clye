<?php
namespace common\models;

class SysSetting extends BaseModel
{
    protected $use_create_time=false;

    public static function tableName()
    {
        return 'sys_setting';
    }
    public static function getTypeInfo($type=null,$field=null)
    {
        $data = [
            'company_intro' => ['name'=>'文章管理/公司简介'],
            'company_edu'   => ['name'=>'文章管理/企业文化'],
            'company_tip'   => ['name'=>'文章管理/精彩瞬间'],
            'company_cert'  => ['name'=>'文章管理/药品证书'],
            'normal'        => ['name'=>'系统管理/常规设置'],
            'problem'        => ['name'=>'系统管理/常见问题'],
        ];
        if(is_null($type)){
            return $data;
        }else{
            $type_info = isset($data[$type])?$data[$type]:[];

            if(is_null($field)){
                return $type_info;
            }else{
                return isset($type_info[$field])?$type_info[$field]:'';
            }
        }
    }


    public static function getContent($type)
    {
        $cache_name = 'setting_'.$type;
        $cache = \Yii::$app->cache;
        $data = $cache->getOrSet($cache_name, function ()use($type) {
            $data = self::findOne($type);
            return $data?$data['content']:'';
        });

        return $data;
    }


    public static function setContent($type,$content)
    {
        //删除缓存
        $cache_name = 'setting_'.$type;
        $cache = \Yii::$app->cache;
        $cache->delete($cache_name);
        $model = self::findOne($type);
        $model->content = $content;
        return $model->save();
    }



    /*
     * 日志记录
     * */
    public function getLogIntro(\yii\base\Event $event)
    {
        $content = '';
        $object = $event->sender;
        if($event->name==self::EVENT_AFTER_INSERT){
            $content = '新增:'.self::getTypeInfo($object['type'],'name');
        }elseif ($event->name==self::EVENT_BEFORE_DELETE){
            $content = '删除:'.self::getTypeInfo($object['type'],'name');
        }elseif ($event->name==self::EVENT_AFTER_UPDATE){
            $content = '更新:'.self::getTypeInfo($object['type'],'name');
        }
        return $content;
    }
}