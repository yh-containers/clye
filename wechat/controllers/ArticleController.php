<?php
namespace wechat\controllers;


class ArticleController extends CommonController
{
    //宣传资料
    public function actionIndex()
    {
        $type = $this->request->get('type',5);

        return $this->render('index',[
            'type'=>$type
        ]);
    }
    //宣传资料
    public function actionTip()
    {
        $type = $this->request->get('type',4);

        return $this->render('tip',[
            'type'=>$type
        ]);
    }

    //新闻
    public function actionNews()
    {
        $type = $this->request->get('type',0);

        return $this->render('news',[
            'type'=>$type
        ]);
    }

    //常见问题
    public function actionProblem()
    {

        return $this->render('problem',[
        ]);
    }

    //文章加载
    public function actionShowList()
    {
        $type = $this->request->get('type',0);
        $query = \common\models\Article::find()->where(['type'=>$type,'status'=>1]);
        $count = $query->count();
        $pagination = \Yii::createObject(array_merge(\Yii::$app->components['pagination'],['totalCount' => $count]));
        $list = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->orderBy('sort asc')
            ->all();

        $data = [];
        foreach($list as $vo){
            $data[] = [
                'id'         =>  $vo['id'],
                'type'       =>  $vo['type'],
                'title'      =>  $vo['title'],
                'img'        =>  $vo['img'],
                'intro'      =>  $vo['intro'],
            ];
        }

        return $this->asJson(['code'=>1,'msg'=>'获取成功','data'=>$data,'page'=>$pagination->pageCount]);
    }
    //常见问题
    public function actionProblemList()
    {
        $query = \common\models\Problem::find()->where(['status'=>1]);
        $count = $query->count();
        $pagination = \Yii::createObject(array_merge(\Yii::$app->components['pagination'],['totalCount' => $count]));
        $list = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->orderBy('sort asc')
            ->all();

        $data = [];
        foreach($list as $vo){
            $data[] = [
                'id'         =>  $vo['id'],
                'title'      =>  $vo['title'],
                'content'    =>  $vo['content'],
            ];
        }

        return $this->asJson(['code'=>1,'msg'=>'获取成功','data'=>$data,'page'=>$pagination->pageCount]);
    }

    //详情
    public function actionDetail()
    {
        $id = $this->request->get('id');
        $model = \common\models\Article::findOne($id);
        !empty($model) &&  $model->updateCounters(['views'=>1]);
        //上一篇
        $model_up = \common\models\Article::find()->where(['type'=>$model['type']])->andWhere(['<','id',$id])->limit(1)->one();
        $model_down = \common\models\Article::find()->where(['type'=>$model['type']])->andWhere(['>','id',$id])->limit(1)->one();

        return $this->render('detail',[
            'model' => $model,
            'model_up' => $model_up,
            'model_down' => $model_down,
        ]);
    }
}