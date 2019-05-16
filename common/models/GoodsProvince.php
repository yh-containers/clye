<?php
namespace common\models;



class GoodsProvince extends BaseModel
{
    public $use_create_time = false;

    public static function tableName()
    {
        return '{{%goods_province}}';
    }



}