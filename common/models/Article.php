<?php
namespace common\models;

use common\models\use_traits\SoftDelete;

class Article extends BaseModel
{
    use SoftDelete;
    public static function tableName()
    {
        return '{{%article}}';
    }

    public function attributeLabels()
    {
        return [
            'title'         =>  '标题',
            'author'        =>  '作者',
            'img'           =>  '封面图',
            'show_time'     =>  '发布时间',
            'intro'         =>  '简介',
            'content'       =>  '内容',
        ];
    }

    public static function getTypeInfo($type=null,$field=null)
    {
        //不要调整数组顺序谢谢
        $data = [
            ['name'=>'企业新闻','is_more'=>true,'page'=>'news'],
            ['name'=>'行业新闻','is_more'=>true,'page'=>'news'],
            ['name'=>'公司简介','is_more'=>false,'page'=>'intro','type'=>'company_intro'],
            ['name'=>'企业文化','is_more'=>false,'page'=>'intro','type'=>'company_edu'],
            ['name'=>'精彩瞬间','is_more'=>true,'page'=>'news'],
            ['name'=>'宣传资料','is_more'=>true,'page'=>'news'],
            ['name'=>'药品证书','is_more'=>false,'page'=>'intro','type'=>'company_cert'],
        ];
        if(is_null($type)){
            return $data;
        }else{
            $type_info = isset($data[$type])?$data[$type]:[];
            if(is_null($field)){
                return $type_info;
            }else{
                return isset($type_info[$field])?$type_info[$field]:'';
            }
        }
    }





    public function rules()
    {
        return [
            [['title','author','show_time','intro','content'],'required','message'=>'{attribute}必须输入'],
            [['img'],'required','message'=>'{attribute}必须上传'],
            ['sort','number','min'=>0,'max'=>100,'tooSmall'=>'{attribute}不得低于{min}','tooBig'=>'{attribute}不得高于{max}','message'=>'{attribute}必须是数字'],
            ['status','default','value'=>1],
            ['type','default','value'=>0],
            ['sort','default','value'=>100],
        ];
    }


    /*
     * 日志记录
     * */
    public function getLogIntro(\yii\base\Event $event)
    {
        $content = '';
        $object = $event->sender;
        if($event->name==self::EVENT_AFTER_INSERT){
            $content = '新增文章:'.$object['title'];
        }elseif ($event->name==self::EVENT_BEFORE_DELETE){
            $content = '删除文章:'.$object['title'];
        }elseif ($event->name==self::EVENT_AFTER_UPDATE){
            $content = '更新文章:'.$object['title'];
        }
        return $content;
    }
}