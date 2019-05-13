<?php
namespace wechat\controllers;


class IndexController extends CommonController
{
    /*
     * 操作异常
     * */
    public function actionError()
    {
        $exception = \Yii::$app->errorHandler->exception;
        if ($exception !== null) {
            if($exception instanceof \yii\base\UserException){
                //状态码
                \Yii::$app->response->statusCode=200;
                if($this->request->isAjax){
                    return $this->asJson(['code'=>0,'msg'=>$exception->getMessage()]);
                }
            }
            $this->layout='main';
            return $this->render('/site/error', ['exception' => $exception,'message'=>$exception->getMessage()]);
        }
    }

    public function actionIndex()
    {
        //轮播图
        $images = \common\models\Images::find()->where(['type'=>0,'status'=>1])->all();
        //产品分类
        $goods_cate = \common\models\GoodsCate::getGoodsCate(0);
        //商品
        $goods = \common\models\Goods::find()->where(['status'=>1])->orderBy('is_hot desc,sort asc')->limit(6)->all();

        return $this->render('index',[
            'images' => $images,
            'goods_cate'=>$goods_cate,
            'goods'=>$goods,
        ]);
    }

    public function actionLogin()
    {
        if($this->request->isAjax){
            $phone = $this->request->post('phone');
            $type = $this->request->post('type');//登录模式  1验证码登录
            $verify = $this->request->post('verify');//验证码
            $password = $this->request->post('password');
            if(empty($type)){
                if(empty($phone)) throw new \yii\base\UserException('请输入手机号');
                if(empty($password)) throw new \yii\base\UserException('请输入密码');

                $model_user = \common\models\User::find()->where(['phone'=>$phone])->one();
                if(empty($model_user))  throw new \yii\base\UserException('账户不存在');
                $current_pwd = \common\models\User::generatePwd($password,$model_user['salt']);
                if($current_pwd!=$model_user['password'])  throw new \yii\base\UserException('用户名或密码错误');
            }else{

                try{
                    //直接创建用户
                    $model_user =  \common\models\User::handleVerifyLogin($phone,$verify);
                }catch(\Exception $e){
                    throw new \yii\base\UserException($e->getMessage());
                }
            }


            self::handleAction($model_user);

            return $this->asJson(['code'=>1,'msg'=>'登录成功','url'=>\yii\helpers\Url::to(['index'])]);
        }

        return $this->render('login',[

        ]);
    }

    //处理登录
    public static function handleAction(\common\models\User $model_user=null)
    {
        if($model_user){
            $session = \Yii::$app->session;
            $session->open();
            $session->set('user_info',[
                'user_id' => $model_user->id,
            ]);
        }

    }



    public function actionReg()
    {
        if($this->request->isAjax){
            $php_input  = $this->request->post();
            $model = new \common\models\User();
            $model->scenario=\common\models\User::SCENARIO_USER_WECHAT_REG;
            $result = $model->actionSave($php_input);
            return $this->asJson($result);
        }

        return $this->render('reg',[

        ]);
    }

    public function actionForget()
    {
        if($this->request->isAjax){
            $phone = $this->request->post('phone');
            $php_input  = $this->request->post();
            $model = \common\models\User::find()->where(['phone'=>$phone])->one();
            if(empty($model)){
                $model = new \common\models\User();
            }
            $model->scenario=\common\models\User::SCENARIO_USER_WECHAT_FORGET;
            $result = $model->actionSave($php_input);
            return $this->asJson($result);
        }
        return $this->render('forget',[

        ]);
    }

    //公司简介
    public function actionCompanyAbout()
    {
        return $this->_editPage('company_intro','公司介绍');
    }
    public function actionCompanyEdu()
    {
        return $this->_editPage('company_edu','企业文化');
    }
    //联系我们
    public function actionContact()
    {
        $content = \common\models\SysSetting::getContent('normal');
        $content = $content?json_decode($content, true):null;
        return $this->render('contact',[
            'content' => $content,
        ]);
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        $session = \yii::$app->session;
        $session->destroy();

        return $this->redirect('login');
    }

    //富文本页面
    private function _editPage($type,$title='')
    {
        $content = \common\models\SysSetting::getContent($type);
        return $this->render('editPage',[
            'content' => $content,
            'title'  => $title,
        ]);
    }

    //证书
    public function actionCert()
    {
        $list = \common\models\Cert::find()->where(['status'=>1])->orderBy('sort asc')->all();
        return $this->render('cert',[
            'list' => $list,
        ]);
    }


}