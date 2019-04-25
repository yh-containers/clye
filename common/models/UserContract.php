<?php
namespace common\models;

use common\models\use_traits\SoftDelete;

class UserContract extends BaseModel
{
    use SoftDelete;

    public static function tableName()
    {
        return '{{%user_contract}}';
    }



}