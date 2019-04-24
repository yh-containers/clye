<?php
namespace backend\controllers;


class ArticleController extends CommonController
{
    //企业新闻
    public function actionNewsCompany()
    {
        return $this->_renderNews(0);
    }
    //行业新闻
    public function actionNewsTrade()
    {

        return $this->_renderNews(1);
    }
    //宣传资料
    public function actionNewsSpread()
    {

        return $this->_renderNews(5);
    }
    //公司简介
    public function actionCompany()
    {
        return $this->_renderNews(2);
    }
    //企业文化
    public function actionEdu()
    {
        return $this->_renderNews(3);
    }
    //精彩瞬间
    public function actionNewsTip()
    {
        return $this->_renderNews(4);
    }
    //药品证书
    public function actionCert()
    {
        $query = \common\models\Cert::find();
        $count = $query->count();
        $pagination = \Yii::createObject(array_merge(\Yii::$app->components['pagination'],['totalCount'=>$count]));
        $list = $query->offset($pagination->offset)->limit($pagination->limit)->orderBy('sort asc')->all();

        return $this->render('cert',[
            'list'  => $list,
            'pagination' => $pagination
        ]);
    }

    //常见问题
    public function actionCertAdd()
    {

        $id = $this->request->get('id',0);
        $model = new \common\models\Cert();
        if($this->request->isAjax){
            $php_input = $this->request->post();
            $result = $model->actionSave($php_input);
            return $this->asJson($result);
        }

        $model = $model::findOne($id);

        return $this->render('certAdd',[
            'model' => $model,
        ]);


    }


    //-删除
    public function actionCertDel()
    {

        $id = $this->request->get('id');
        $model = new \common\models\Cert();
        $result = $model->actionDel(['id'=>$id]);
        return $this->asJson($result);
    }

    //常见问题
    public function actionProblem()
    {
        $query = \common\models\Problem::find();
        $count = $query->count();
        $pagination = \Yii::createObject(array_merge(\Yii::$app->components['pagination'],['totalCount'=>$count]));
        $list = $query->offset($pagination->offset)->limit($pagination->limit)->orderBy('sort asc')->all();
        return $this->render('problem',[
            'list'  => $list,
            'pagination' => $pagination
        ]);
    }
    //常见问题
    public function actionProblemAdd()
    {

        $id = $this->request->get('id',0);
        $model = new \common\models\Problem();
        if($this->request->isAjax){
            $php_input = $this->request->post();
            $result = $model->actionSave($php_input);
            return $this->asJson($result);
        }

        $model = $model::findOne($id);

        return $this->render('problemAdd',[
            'model' => $model,
        ]);


    }



    //-删除
    public function actionProblemDel()
    {

        $id = $this->request->get('id');
        $model = new \common\models\Problem();
        $result = $model->actionDel(['id'=>$id]);
        return $this->asJson($result);
    }


    public function actionNewsAdd()
    {
        $type= $this->request->get('type');
        $id = $this->request->get('id');
        $model = new \common\models\Article();
        if($this->request->isAjax){
            $php_input = $this->request->post();
            if(empty($php_input['password']))  unset($php_input['password']);

            $result = $model->actionSave($php_input);
            return $this->asJson($result);
        }
        $article_info =  \common\models\Article::getTypeInfo($type);
        $name = isset($article_info['name'])?$article_info['name']:'';
        $model = $model::findOne($id);
        return $this->render('newsAdd',[
            'name' => $name,
            'type' => $type,
            'model' => $model,
        ]);
    }


    //文章--删除
    public function actionNewsDel()
    {
        $id = $this->request->get('id');
        $type = $this->request->get('type');
        $cond['id'] = $id;
        if($type!=''){
            $cond['type']=$type;
        }
        $model = new \common\models\Article();
        $result = $model->actionDel($cond);
        return $this->asJson($result);
    }


    private function _renderNews($type)
    {
        $article_info =  \common\models\Article::getTypeInfo($type);
        $name = isset($article_info['name'])?$article_info['name']:'';
        $page = isset($article_info['page'])?$article_info['page']:'news';
        $is_more = isset($article_info['is_more'])?$article_info['is_more']:false;
        $setting_type = isset($article_info['type'])?$article_info['type']:'';

        $data = [
            'name' => $name,
            'type' => $type,
        ];
        if($is_more){
            $query = \common\models\Article::find()->where(['type'=>$type]);
            $count = $query->count();
            $pagination = \Yii::createObject(array_merge(\Yii::$app->components['pagination'],['totalCount'=>$count]));
            $list = $query->offset($pagination->offset)->limit($pagination->limit)->orderBy('sort desc,show_time desc')->all();
            $data = array_merge($data,[
                'list'  =>  $list,
                'pagination' => $pagination
            ]);
        }else{
            $content = \common\models\SysSetting::getContent($setting_type);
            $data = array_merge($data,[
                'content'  =>  $content,
                'setting_type'  =>  $setting_type,
            ]);
        }
        return $this->render($page,$data);
    }
}
