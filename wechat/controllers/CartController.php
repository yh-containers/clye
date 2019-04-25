<?php
namespace wechat\controllers;


class CartController extends CommonController
{

    /**/
    public function actionIndex()
    {
        $data = \common\models\UserCart::find()
            ->asArray()
            ->joinWith(['linkGoods'],true,'RIGHT JOIN')
            ->where([
                \common\models\UserCart::tableName().'.uid'=>$this->user_id
            ])
            ->all();
        return $this->render('index',[
            'data' => $data
        ]);
    }


}