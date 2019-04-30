<?php

namespace backend\controllers;

use yii\helpers\Url;
use yii\web\Controller;
use yii\web\UploadedFile;


class ContractController extends CommonController
{

    //合同模版
    public function actionTemp()
    {
        $content = \common\models\SysSetting::getContent('contract_temp');
        //模版变量
        return $this->render('temp',[
            'content'  =>  $content,
            'temp_var' => \common\models\UserContract::getTempVar(),
        ]);
    }
}
