<?php
namespace wechat\controllers;


class CartController extends CommonController
{

    /**/
    public function actionIndex()
    {
        $data = \common\models\UserCart::find()
            ->joinWith(['linkGoods'],true,'RIGHT JOIN')
            ->where([
                \common\models\UserCart::tableName().'.uid'=>$this->user_id,
                \common\models\Goods::tableName().'.status'=>1
            ])
            ->all();
        return $this->render('index',[
            'data' => $data,
            'user_model' => \common\models\User::findOne($this->user_id),
        ]);
    }


}