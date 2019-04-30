<?php
namespace common\models;

use common\models\use_traits\SoftDelete;
use phpDocumentor\Reflection\DocBlock\Tags\Throws;

class UserOrderLogs extends BaseModel
{
    //忽略日志记录
    public static $is_ignore=true;

    protected $use_create_time=false;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user_order_logs}}';
    }

//    public static function get

    /**
     * 新增日志
     * @param $oid int 订单id
     * @param $title string 标题
     * @param $intro string 具体内容
     * @param $info array 附加数据
     * @return void
     * */
    public static function recordLog($oid,$title,$intro='',$opt_mid=0,array $info=[])
    {
        $model = new self();
        $model->oid = $oid;
        $model->title = $title;
        $model->intro = $intro;
        $model->opt_mid = $opt_mid;
        $model->info = json_encode($info);
        $model->create_time = date('Y-m-d H:i:s');
        $model->save(false);
    }
}
