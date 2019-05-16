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
//        var_dump($list);exit;
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
        //商品id
        $id = $this->request->isGet?$this->request->get('id'):$this->request->post('id');
        $model = new \common\models\Goods();
        if($this->request->isAjax){
            $php_input = $this->request->post();
            $goods_spu_index = $this->request->post('spu_index');//商品spu index
            $goods_spu_name = $this->request->post('spu_name');//商品spu key
            $goods_spu_val = $this->request->post('spu_val');//商品spu value

            $price = (float)$this->request->post('price','0.00');
            unset($php_input['price']);
            if($price<=0) throw new \yii\base\UserException('商品价格必须大于0');

            !empty($php_input['img']) && $php_input['img']=implode(',',$php_input['img']);
            $php_input['is_hot'] = empty($php_input['is_hot'])?0:1;
            if(!empty($php_input['id'])){
                $model = \common\models\Goods::findOne($php_input['id']);
                if(empty($model)) throw new \yii\base\UserException('编辑的商品不存在');
            }
            $transaction = \Yii::$app->db->beginTransaction();

            try{
                $model->attributes = $php_input;
                if(!$this->is_super_manager){
                    //管理员省份
                    $province = $this->user_model->getProvince();

                }else{
                    //A类用户商品价格
                    $model->setAttribute('price',$price);
                }
                $model->save();


                //商品spu
                $goods_spu_data  = [];
                $goods_spu_insert_filed = ['gid','name','val'];
                if($id && !empty($goods_spu_index)){
                    //编辑状态-删除无用的spu
                    \common\models\GoodsSpu::deleteAll(['and',['gid'=>$id],['not in','id',$goods_spu_index]]);
                }
                if(is_array($goods_spu_name) && !empty($goods_spu_name)){
                    foreach ($goods_spu_name as $key=>$vo){
                        if(!empty($vo)){
                            $val = empty($goods_spu_val[$key])?'':$goods_spu_val[$key];
                            if(!empty($goods_spu_index[$key])){
                                //更新数据
                                \common\models\GoodsSpu::updateAll(['name'=>$vo,'val'=>$val],['gid'=>$model->id,'id'=>$goods_spu_index[$key]]);
                            }else{
                                $goods_spu_data[]= [
                                    $model->id,
                                    $vo,
                                    $val
                                ];
                            }


                        }
                    }
                }

//                var_dump($goods_spu_insert_filed);var_dump($goods_spu_data);exit;
                if($goods_spu_data){
                    //执行批量添加
                    \Yii::$app->db->createCommand()->batchInsert(\common\models\GoodsSpu::tableName(), $goods_spu_insert_filed, $goods_spu_data)->execute();
                }


                if($model->hasErrors()){
                    $error_msg = $model->getFirstErrors();
                    return $this->asJson(['code'=>0,'msg'=>$error_msg[key($error_msg)]]);
                }

                if(!$this->is_super_manager && isset($province)){
                    //按省份算价
                    $model_goods_pri = \common\models\GoodsProvince::find()->where(['gid'=>$id,'province'=>$province])->one();
                    if(empty($model_goods_pri)){
                        $model_goods_pri = new  \common\models\GoodsProvince();
                        $model_goods_pri->province = $province;
                        $model_goods_pri->gid = $model->id;
                    }
                    $model_goods_pri->price = $price;
                    $model_goods_pri->save(false);

                }
                $transaction->commit();
            }catch (\Exception $e){
                $transaction->rollBack();
                throw new \yii\base\UserException($e->getMessage());
            }
            return $this->asJson(['code'=>1,'msg'=>'操作成功']);
        }

        $model = $model::find()->with(['linkSpu'])->where(['id'=>$id])->one();

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
