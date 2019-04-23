<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/19
 * Time: 9:46
 */

namespace backend\components;

use yii\base\Event;
use yii\db\ActiveRecord;
use yii\base\Component;

class RecordOptLog extends Component
{

    //是否已记录状态--同一次动作只会记录一次
    public static $is_record=false;
    //记录属性
    const EVENT_PROP_FIELD = 'is_record_log';
    const IGNORE_TABLE = 'sys_opt_logs,';
    const IGNORE_EVENT = 'afterFind,';
    public function init()
    {
        parent::init();

        Event::on(ActiveRecord::className(), ActiveRecord::EVENT_BEFORE_DELETE, [$this,'handleEvent']);
        Event::on(ActiveRecord::className(), ActiveRecord::EVENT_AFTER_UPDATE, [$this,'handleEvent']);
        Event::on(ActiveRecord::className(), ActiveRecord::EVENT_AFTER_INSERT, [$this,'handleEvent']);
    }

    //记录操作日志
    public function handleEvent($event)
    {
        if(!self::$is_record){
            //记录类型
            $logs_index =  \common\models\SysOptLogs::getTypeIntro(null,null,$event->name);
            $object = $event->sender;
            if(!property_exists($object, self::EVENT_PROP_FIELD) || (property_exists($object, self::EVENT_PROP_FIELD) && $object[self::EVENT_PROP_FIELD])){
                $table_name = $object::tableName();
                if(strpos(self::IGNORE_TABLE,$table_name)===false){
                    //说明
                    $intro = property_exists($object,'opt_log_intro') && !empty($object::$opt_log_intro) ? $object::$opt_log_intro : null;
                    //处理说明
                    $intro_name = \common\models\SysOptLogs::getTypeIntro($logs_index,'name');
                    $log_intro = method_exists($object,'getLogIntro')?$object->getLogIntro($event):null;
                    $content = !empty($log_intro) ? $log_intro : $intro_name;

                    if(!empty($intro)){
                        $content = $intro;
                    }
                    //修改属性opt_log_intro_extra
                    $mod_info = property_exists($object,'opt_log_intro_extra') && $object::$opt_log_intro_extra ? $object::$opt_log_intro_extra:(property_exists($event,'changedAttributes')?$event->changedAttributes:[]);
                    //记录操作日志
                    \common\models\SysOptLogs::recordData($logs_index?$logs_index:-1,$content,$mod_info);
                }
            }
        }
        self::$is_record=true;
    }
}