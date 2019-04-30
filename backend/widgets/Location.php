<?php
namespace backend\widgets;

use yii\base\Widget;

class Location extends Widget
{
    public $province;
    public $city;
    public $area;

    public function init()
    {
        parent::init();

    }

    public function run()
    {
        $city_list = [];
        //省份
        $province_list = \common\models\SysLocation::find()->where(['type'=>1])->orderBy('sort asc')->all();
        //城市
        $province = $this->province?$this->province:(isset($province_list[0])?$province_list[0]['id']:0);
        $city_list = \common\models\SysLocation::find()->where(['pid'=>$province])->orderBy('sort asc')->all();

        return $this->render('location',[
            'province_list'=>$province_list,
            'city_list'=>$city_list,
            'province'=>$this->province,
            'city'=>$this->city,
            'area'=>$this->area,
        ]);
    }
}