<?php
namespace backend\controllers;


use yii\base\UserException;

class LocationController extends CommonController
{

    public function actionIndex()
    {
        $type= $this->request->get('type');

        $data = \common\models\SysLocation::find()->asArray()->with('linkChild')->where(['type'=>1])->orderBy('sort asc')->all();
        return $this->render('index',[
            'data'=>$data,
            'type' => $type
        ]);
    }

    public function actionAdd()
    {
        $id = $this->request->get('id');
        $model = new \common\models\SysLocation();
        if($this->request->isAjax){
            $php_input = $this->request->post();
            $result = $model->actionSave($php_input);
            return $this->asJson($result);
        }
        $model = $model::findOne($id);
        //获取所有省
        $province = \common\models\SysLocation::find()->asArray()->where(['type'=>1])->all();
        //区块-华东-华南。。。。之类
        $block_area = \common\models\SysLocationArea::getCacheData();
        return $this->render('add',[
            'model'=>$model,
            'province'=>$province,
            'block_area'=>$block_area,
        ]);
    }


    //轮播图--删除
    public function actionDel()
    {
        $id = $this->request->get('id');
        $model = new \common\models\SysLocation();
        $result = $model->actionDel(['id'=>$id]);
        return $this->asJson($result);
    }

    //行政区
    public function actionArea()
    {
        $data = \common\models\SysLocationArea::find()->with('linkAreaCity')->asArray()->orderBy('sort asc')->all();
        return $this->render('area',[
            'data' => $data
        ]);
    }


    public function actionAreaAdd()
    {
        $id = $this->request->get('id');
        $model = new \common\models\SysLocationArea();
        if($this->request->isAjax){

            $id = $this->request->post('id');
            $aid = $this->request->post('aid');
            $name = $this->request->post('name');
            $sort = (int)$this->request->post('sort',100);
            $sort = $sort>0?$sort:100;
            if(empty($name)) throw new UserException('请输入行政区名称');
            if(!empty($id)){
                $model_edit = $model::findOne($id);
                $model_edit && $model = $model_edit;
            }
            $db = \Yii::$app->db;
            $transaction = $db->beginTransaction();
            try{
                $model->name=$name;
                $model->sort=$sort;
                $model->save();

                //保存行政区
                if(count($aid)>0){

                    \common\models\SysLocation::updateAll([
                        'aid'=>null
                    ],['aid'=>$id]);

                    \common\models\SysLocation::updateAll([
                        'aid'=>$model->id
                    ],['in','id',$aid]);
                }
                $transaction->commit();

                return $this->asJson(['code'=>1,'msg'=>'操作成功']);
            }catch (\Exception $e){
                $transaction->rollBack();
                return $this->asJson(['code'=>0,'msg'=>'操作异常:'.$e->getMessage()]);

            }




        }
        $model = $model::find()->where(['id'=>$id])->one();
        //获取所有省
        $province = \common\models\SysLocation::find()->with('linkChild')->asArray()->where(['type'=>1])->orderBy('sort asc')->all();
        return $this->render('areaAdd',[
            'model'=>$model,
            'province'=>$province,
        ]);
    }
}
