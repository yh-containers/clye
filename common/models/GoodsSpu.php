<?php
namespace common\models;

class GoodsSpu extends BaseModel
{
    public static $is_ignore=true;//忽略日志记录

    protected $use_create_time = false;
    public static function tableName()
    {
        return '{{%goods_spu}}';
    }


    public function rules()
    {
        return ['id','gid','name','val','safe'];
    }

}