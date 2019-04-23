<?php

namespace backend\controllers;

use yii\helpers\Url;
use yii\web\Controller;
use yii\web\UploadedFile;


class UploadController extends CommonController
{

    public $root_path='';
    public $root_dir='/assets';
    public function behaviors()
    {
        $behaviors = parent::behaviors(); // TODO: Change the autogenerated stub

        //应用根目录
        $this->root_path = \Yii::getAlias('@resource_root');
        return $behaviors;
    }

    public function actionUpload($type='images',$is_return=false)
    {
//        $request = \Yii::$app->request;
//        $up_type = $request->post('type','images');
        $upload = new UploadedFile();
        $file = $upload->getInstanceByName(key($_FILES));
        //相对路径
        $relative_path =  $this->root_dir.'/uploads/'.$type.'/'.date('Y-m-d').'/';
        //保存目录
        $path = ($is_return?'.':'').$this->root_path.$relative_path;
        if (!file_exists($path)) {
            $this->createDir($path);
        }
        //保存文件名
        $save_name = md5(time().$file->baseName) . '.' . $file->extension;
        //保存绝对路径
        $save_path = $path . $save_name;
        //保存图片
        $file->saveAs($save_path);
        if($file->hasError){
            $result = ['code'=>0,'msg'=>'上传异常:'];
        }else{
            $result = ['code'=>1,'msg'=>'上传成功','path'=>$relative_path.$save_name];
        }
        if($is_return){
            return $result;
        }else{
            $this->asJson($result);
        }
    }

    /**
     * 递归：生成目录
     */
    private function createDir($str)
    {
        $arr = explode('/', $str);
        if(!empty($arr))
        {
            $path = '';
            foreach($arr as $k=>$v)
            {
                $path .= $v.'/';
                if (!file_exists($path)) {
                    mkdir($path, 0777);
                    chmod($path, 0777);
                }
            }
        }
    }

}