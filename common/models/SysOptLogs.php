<?php
namespace common\models;
use yii\db\ActiveRecord;
class SysOptLogs extends BaseModel
{
    public static $is_ignore=true;//忽略日志记录
    //不记录操作日志
    public $is_record_log=false;

    public static function tableName()
    {
        return 'sys_opt_logs';
    }

    //获取类型
    public static function getTypeIntro($type=null,$field=null,$action=null)
    {
        $data = [
            ['name'=>'其它','action'=>''],
            ['name'=>'数据更新','action'=>ActiveRecord::EVENT_AFTER_UPDATE],
            ['name'=>'数据写入','action'=>ActiveRecord::EVENT_AFTER_INSERT],
            ['name'=>'数据删除','action'=>ActiveRecord::EVENT_BEFORE_DELETE],
        ];
        if(!is_null($action)){
            $action_column = array_column($data,'action');
            return !empty($action)?array_search($action,$action_column):false;
        }


        if(is_null($type)){
            return $data;
        }else{
            $info = isset($data[$type])?$data[$type]:[];
            if(is_null($field)){
                return $info;
            }else{
                return isset($info[$field])?$info[$field]:'';
            }
        }
    }

    //写入数据
    public static function recordData($type,$content,$mod_info=[],$opt_user_id=null)
    {
        $insert_data = [
            'type'          => $type,
            'uid'           => is_null($opt_user_id)?(property_exists(\Yii::$app->controller,'user_id')?\Yii::$app->controller->user_id:0):$opt_user_id,
            'content'       => $content,
            'mod_info'      => json_encode($mod_info),
            'create_time'   => date('Y-m-d H:i:s'),
        ];
        \Yii::$app->db->createCommand()->insert(self::tableName(), $insert_data)->execute();
    }

    //关联管理员
    public function getLinkManager()
    {
        return $this->hasOne(SysManager::className(),['id'=>'uid']);
    }
}