<?php
namespace common\components;

use common\models\Order;
use common\models\SysManager;
use yii\base\Behavior;
use yii\db\ActiveRecord;

class OrderBehavior extends Behavior
{
    // 其它代码

    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'handleLogs',
            ActiveRecord::EVENT_AFTER_UPDATE => 'handleLogs',
        ];
    }

    public function handleLogs($event)
    {

        $changedAttributes = empty($event->changedAttributes)?[]:$event->changedAttributes;
        if(!empty($event->sender)){
            $object = $event->sender;

            // 处理器方法逻辑
            if($event->name==ActiveRecord::EVENT_AFTER_INSERT){
                //新增
                \common\models\UserOrderLogs::recordLog($object->id,'创建订单','创建订单',0,[],1);

                //发送创建订单模版消息
                \common\models\WxTempMsg::sendMessage($object->uid,
                    'tJe0VtJVLbyqHySkElPk6JA9DhJHOr-WI6qu2W9y4Po',
                    [
                        'first' => '您的订单已提交',
                        'keyword1' => $object->getAttribute('no'),
                        'keyword2' => $object->getAttribute('create_time')?date('Y-m-d H:i:s',$object->getAttribute('create_time')):'',
                        'keyword3' => $object->getAttribute('pay_money'),
                        'remark'   => '您已提交订单,我们会尽快处理',
                    ],
                    \Yii::$app->urlManagerWx->createAbsoluteUrl(['order/detail','id'=>$object->id],true)
                );

            }elseif ($event->name==ActiveRecord::EVENT_BEFORE_DELETE){
                //删除
                \common\models\UserOrderLogs::recordLog($object->id,'删除订单','删除订单',0,[],1);
            }elseif ($event->name==ActiveRecord::EVENT_AFTER_UPDATE){


                if(array_key_exists('is_produce',$changedAttributes)){
                    $name = \common\models\Order::getProduceInfo($object->is_produce,'name');
                    \common\models\UserOrderLogs::recordLog($object->id,'调整订单信息',''.$name,0,[],1);
                    if($object->is_produce==2){
                        //订单已生产完成
                        \common\models\WxTempMsg::sendMessage($object->uid,
                            'tJe0VtJVLbyqHySkElPk6JA9DhJHOr-WI6qu2W9y4Po',
                            [
                                'first' => '您的订单生产完成',
                                'keyword1' => $object->getAttribute('no'),
                                'keyword2' => $object->getAttribute('pro_end_time')?date('Y-m-d H:i:s',$object->getAttribute('pro_end_time')):'',
                                'keyword3' => $object->getAttribute('pay_money'),
                                'remark'   => '订单已生产完成',
                            ],
                            \Yii::$app->urlManagerWx->createAbsoluteUrl(['order/detail','id'=>$object->id],true)
                        );
                    }
                }

                if(array_key_exists('is_send',$changedAttributes)){
                    $name = \common\models\Order::getSendInfo($object->is_send,'name');
                    \common\models\UserOrderLogs::recordLog($object->id,'调整订单信息',''.$name,0,[],1);

                    if($object->is_send==2){
                        //查询订单物流信息
                        $logistics_info = $object->linkOrderLogistics;
                        //已完成发货
                        \common\models\WxTempMsg::sendMessage($object->uid,
                            'QdWO7qYVi5KBQTe1uuR5005ZWaWOsuCgAEFJ1yF6ohQ',
                            [
                                'first' => '您的订单已发货',
                                'keyword1' => $object->getAttribute('no'),
                                'keyword2' => $logistics_info['company'],
                                'keyword3' => $logistics_info['no'],
                                'keyword4' => $object->getAttribute('send_end_time')?date('Y-m-d H:i:s',$object->getAttribute('send_end_time')):'',
                                'remark'   => '订单已发货,请耐心等待',
                            ],
                            \Yii::$app->urlManagerWx->createAbsoluteUrl(['order/detail','id'=>$object->id],true)
                        );
                    }
                }

                if(array_key_exists('is_receive',$changedAttributes)){
                    $name = \common\models\Order::getReceiveInfo($object->is_receive,'name');
                    \common\models\UserOrderLogs::recordLog($object->id,'调整订单信息',''.$name,0,[],1);

                    if($object->is_receive==2){
                        //签收完成
                        \common\models\WxTempMsg::sendMessage($object->uid,
                            'tJe0VtJVLbyqHySkElPk6JA9DhJHOr-WI6qu2W9y4Po',
                            [
                                'first' => '已签收',
                                'keyword1' => $object->getAttribute('no'),
                                'keyword2' => $object->getAttribute('receive_end_time')?date('Y-m-d H:i:s',$object->getAttribute('receive_end_time')):'',
                                'keyword3' => $object->getAttribute('pay_money'),
                                'remark'   => '订单已签收',
                            ],
                            \Yii::$app->urlManagerWx->createAbsoluteUrl(['order/detail','id'=>$object->id],true)
                        );
                    }
                }
                //更新
                if(array_key_exists('status',$changedAttributes)){
                    $name = \common\models\Order::getStatusInfo($object->status,'name');
                    \common\models\UserOrderLogs::recordLog($object->id,'调整订单信息',''.$name,0,[],1);

                    if($object->status==2){
                        //交易失败
                        \common\models\WxTempMsg::sendMessage($object->uid,
                            'tJe0VtJVLbyqHySkElPk6JA9DhJHOr-WI6qu2W9y4Po',
                            [
                                'first' => '订单被取消',
                                'keyword1' => $object->getAttribute('no'),
                                'keyword2' => $object->getAttribute('create_time')?date('Y-m-d H:i:s',$object->getAttribute('create_time')):'',
                                'keyword3' => $object->getAttribute('pay_money'),
                                'remark'   => '订单已被取消',
                            ],
                            \Yii::$app->urlManagerWx->createAbsoluteUrl(['order/detail','id'=>$object->id],true)
                        );
                    }
                }

                //指派管理员
                if(array_key_exists('m_uid',$changedAttributes)){
                    $manager_info =  \common\models\SysManager::findOne($object->m_uid);
                    \common\models\UserOrderLogs::recordLog($object->id,'调整订单跟进人员',''.$manager_info['name']);
                }

            }
        }

    }
}