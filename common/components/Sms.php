<?php
namespace common\components;

class Sms
{

    const USER_ID = '59258';
    const ACCOUNT = '17007087777';
    const PASSWORD = 'A93D10511AF0F361A1E5B72DFEF2';
    const URL = 'http://web.1xinxi.cn/asmx/smsservice.aspx';
    const SIGN = '【华祖百草通】';
    public static function send($content,$mobile)
    {

//        $url="http://web.1xinxi.cn/asmx/smsservice.aspx?name=".self::ACCOUNT."&pwd=".self::PASSWORD."&sign=【中翰哲】&content=".$content."&mobile=".$mobile."&stime=&type=pt&extno=";
//        $url='http://web.1xinxi.cn/asmx/smsservice.aspx?'.
//        'name='.self::ACCOUNT.
//        '&pwd='.self::PASSWORD.
//        '&sign='.
//        '&mobile='.$mobile.
//        '&content='.$content.
//        '&stime=&type=pt&extno=';
//        echo ($url);

        $data = [
            'name'      => self::ACCOUNT,
            'pwd'       => self::PASSWORD,
            'sign'      => self::SIGN,
            'mobile'    => $mobile,
            'content'   => $content,
            'stime'     => '',
            'type'      => 'pt',
            'extno'     => '',

        ] ;

        return \common\components\HttpCurl::req(self::URL,$data);
    }
}