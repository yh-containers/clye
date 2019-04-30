<?php
namespace common\models;

use common\models\use_traits\SoftDelete;

//合同模版
class UserContract extends BaseModel
{
    use SoftDelete;

    public static function tableName()
    {
        return '{{%user_contract}}';
    }

    public static function getTempVar($type=null,$field=null)
    {
        $data = [
            '{name}'      =>['name'=>'公司名'],
            '{addr}'      =>['name'=>'地址'],
            '{f_name}'    =>['name'=>'法人代表'],
            '{w_name}'    =>['name'=>'委托代理人'],
            '{pay_way}'   =>['name'=>'付款方式','value'=>Order::getPayWay()],
            '{money}'     =>['name'=>'合同金额'],
            '{date}'      =>['name'=>'日期','value'=>date('Y-m-d')],
            '{date_expire}'=>['name'=>'有效期','temp'=>'{start_time|date#Y年m月d日}至{end_time|date#Y年m月d日}','field'=>['start_time'=>time(),'end_time'=>strtotime('+6 month',time())]],
            '{chapter}'   =>['name'=>'印章','value'=>'<img src="/assets/chapter.png"/>'],
        ];
        if(is_null($type)){
            return $data;
        }else{
            $info = isset($data[$type])?$data[$type]:[];
            if(is_null($field)){
                return $info;
            }else{
                return isset($info[$field])?$info[$field]:'';
            }
        }

    }


    public static function setTempContent($data=[])
    {
        $content = \common\models\SysSetting::getContent('contract_temp');
        $temp_var = self::getTempVar();
        $temp_data = [];
        $bind_data = [];
        foreach($temp_var as $key=>$vo)
        {
            $field = substr($key,1,-1);
            if(isset($vo['field']) && isset($vo['temp'])){
                $replace_temp_arr = [];
                preg_match_all('/\{[^\}]+\}/',$vo['temp'],$match);
                $match_subject = $match[0];
                if(!empty($match_subject)){
                    foreach($match_subject as $item){
                        $subject_str = substr($item,1,-1);
                        $arr = explode('|',$subject_str);
                        if(count($arr)==2){
                            $field_key = $arr[0];
                            if(isset($vo['field'][$field_key])){
                                $field_method = $arr[1];
                                $method_arr = explode('#',$field_method);
                                if(count($method_arr)==2){
                                    $replace_temp_arr[$item] = $method_arr[0]($method_arr[1],$vo['field'][$field_key]);
                                    $bind_data[$field_key]=$vo['field'][$field_key];
                                }else{
                                    $replace_temp_arr[$item] = $method_arr[0]($vo['field'][$field_key]);
                                    $bind_data[$field_key]=$vo['field'][$field_key];
                                }
                            }
                        }else{
                            $replace_str = $vo['temp'];
                            if(isset($vo['field'][$subject_str])){
                                $replace_str = $vo['field'][$subject_str];
                                $bind_data[$subject_str]=$vo['field'][$subject_str];
                            }
                            $replace_temp_arr[$item] = $replace_str;
                        }

                    }
                }

                $temp_data[$key] = str_replace(array_keys($replace_temp_arr),array_values($replace_temp_arr),$vo['temp'],$replace_count);

            }else{
                if(isset($vo['value'])){
                    $value = '';
                    if(is_array($vo['value'])){
                        if(isset($data[$field]) && isset($vo['value'][$data[$field]])){
                            if(isset($vo['value'][$data[$field]]['name'])){
                                $value = $vo['value'][$data[$field]]['name'];
                            }else{
                                $value = $vo['value'][$data[$field]];
                            }
                        }
                    }else{
                        $value = $vo['value'];
                    }
                    $temp_data[$key] = $value;
                }else{
                    $temp_data[$key] = (isset($data[$field])?$data[$field]:'');
                }
            }
        }
        $change_content = str_replace(array_keys($temp_data),array_values($temp_data),$content,$replace_count);
//        var_dump($bind_data);exit;
        return [$change_content,$bind_data];
    }

    /*
     * 模版魔术变量替换
     * */
    public static function changeContent(ProductReq $req,$img_show_path='')
    {
        $temp_var = [
            '{COMPANY_NAME}'    => '公司名',
            '{USER_NAME}'       => empty($req['name'])?'':$req['name'],
            '{PHONE}'           => empty($req['phone'])?'':$req['phone'],
            '{DATE_TIME}'       => date('Y-m-d H:i:s'),
            '{DATE}'            => date('Y-m-d'),
            '{ZHZ_CHAPTER}'         => '<div style="width:160px;height: 160px;"><img style="width: inherit;height: inherit;border-radius: 50%" src="'.$img_show_path.'static/images/01.png"/></div>',
            '{SJSY_CHAPTER}'         => '<div style="width:160px;height: 160px;"><img style="width: inherit;height: inherit;border-radius: 50%" src="'.$img_show_path.'static/images/02.png"/></div>',
        ];
        //模版内容
        $content = (new \app\common\model\Setting())->getContent('contract_temp');
        $change_content = str_replace(array_keys($temp_var),array_values($temp_var),$content,$replace_count);
        return $change_content;

    }
}