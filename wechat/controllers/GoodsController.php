<?php
namespace wechat\controllers;


class GoodsController extends CommonController
{

    public function actionIndex()
    {
        $cid = $this->request->get('cid');

        return $this->render('index',[
            'cid' =>$cid,
        ]);
    }

    //检索
    public function actionSearch()
    {
        $keyword = $this->request->get('keyword');
        $cid = $this->request->get('cid');
        return $this->render('search',[
            'keyword' =>$keyword,
            'cid' =>$cid,
        ]);
    }
    //分类
    public function actionCate()
    {
        $list = \common\models\GoodsCate::find()->where(['status'=>1,'pid'=>0])->orderBy('sort asc')->all();
        return $this->render('cate',[
            'list' => $list,
        ]);
    }
    //分类
    public function actionDetail()
    {
        $id= $this->request->get('id');
        $model = \common\models\Goods::findOne($id);
        //验证用户是否收藏
        $col_info = \common\models\UserCol::find()->where(['uid'=>$this->user_id,'gid'=>$id])->one();
        $is_col = empty($col_info)?0:1;
        return $this->render('detail',[
            'model'=>$model,
            'is_col'=>$is_col,
        ]);
    }

    public function actionShowList()
    {
        $keyword = $this->request->get('keyword');
        $keyword = trim($keyword);
        $cid = (int)$this->request->get('cid'); //分类id
        $query = \common\models\Goods::find()->where(['status'=>1]);
        //关键字
        !empty($keyword) && $query->andWhere(['like','name',$keyword]);
        //分类
        if(!empty($cid)){
            $cate_info = \common\models\GoodsCate::find()->asArray()->where(['pid'=>$cid])->all();
            $cate_ids = array_column($cate_info,'id');
            //加入分类id
            array_push($cate_ids,$cid);
            $query->andWhere(['in','cid',$cate_ids]);
        }
        $count = $query->count();
        $pagination = \Yii::createObject(array_merge(\Yii::$app->components['pagination'],['totalCount' => $count]));
        $list = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->orderBy('is_hot desc,sort asc')
            ->all();

        $data = [];
        foreach($list as $vo){
            $data[] = [
                'id'         =>  $vo['id'],
                'name'       =>  $vo['name'],
                'price'      =>  $vo['price'],
                'cover_img'  =>  \common\models\Goods::getCoverImg($vo['img']),
                'intro'      =>  $vo['intro'],
            ];
        }

        return $this->asJson(['code'=>1,'msg'=>'获取成功','data'=>$data,'page'=>$pagination->pageCount]);
    }



}