<?php
namespace backend\controllers;

class GoodsController extends CommonController
{

    public function actionCate()
    {
        $data = \common\models\GoodsCate::find()->with('linkChild')->asArray()->where(['pid'=>0])->orderBy('sort asc')->all();
        return $this->render('cate',[
            'data'  => $data,
        ]);
    }
    public function actionCateAdd()
    {
        $id = $this->request->get('id');
        $model = new \common\models\GoodsCate();
        if($this->request->isAjax){
            $php_input = $this->request->post();
            $result = $model->actionSave($php_input);
            return $this->asJson($result);
        }
        $model = $model::findOne($id);
        $top_cate = \common\models\GoodsCate::find()->where(['pid'=>0])->all();
        return $this->render('cateAdd',[
            'model'=>$model,
            'top_cate'=>$top_cate,
        ]);
    }

    public function actionCateDel()
    {
        $id = $this->request->get('id');
        $model = new \common\models\GoodsCate();
        $result = $model->actionDel(['id'=>$id]);
        return $this->asJson($result);
    }

    //列表
    public function actionIndex()
    {
        $keyword = trim($this->request->get('keyword'));
        $cid = trim($this->request->get('cid'));

        $query = \common\models\Goods::find();

        //关键字搜索
        !empty($keyword)  && $query = $query->andWhere(['like','name',$keyword]);
        //分类搜索
        if($cid){
            $cate_info = \common\models\GoodsCate::find()->asArray()->where(['pid'=>$cid])->all();
            $cate_cid = $cate_info?array_column($cate_info,'id'):[];
            array_push($cate_cid,$cid);
            $query = $query->andWhere(['cid'=>$cate_cid]);

        }
        $count = $query->count();
        $pagination = \Yii::createObject(array_merge(\Yii::$app->components['pagination'],['totalCount'=>$count]));
        $list = $query->with('linkCate')->offset($pagination->offset)->limit($pagination->limit)->orderBy('is_hot desc,sort desc')->all();
        $top_cate = \common\models\GoodsCate::find()->asArray()->where(['pid'=>0])->all();
        return $this->render('index',[
            'keyword'  =>  $keyword,
            'cid'  =>  $cid,
            'list'  =>  $list,
            'pagination' => $pagination,
            'top_cate' => $top_cate,
        ]);
    }

    public function actionAdd()
    {
        $id = $this->request->get('id');
        $model = new \common\models\Goods();
        if($this->request->isAjax){
            $php_input = $this->request->post();
            !empty($php_input['img']) && $php_input['img']=implode(',',$php_input['img']);
            $php_input['is_hot'] = empty($php_input['is_hot'])?0:1;
            $result = $model->actionSave($php_input);
            return $this->asJson($result);
        }
        $model = $model::findOne($id);
        $cate = \common\models\GoodsCate::find()->with('linkChild')->all();
        return $this->render('add',[
            'model'=>$model,
            'cate'=>$cate,
        ]);
    }

    public function actionDel()
    {
        $id = $this->request->get('id');
        $model = new \common\models\Goods();
        $result = $model->actionDel(['id'=>$id]);
        return $this->asJson($result);
    }
}
