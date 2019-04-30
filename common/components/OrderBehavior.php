<?php
namespace common\components;

use common\models\Order;
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
                \common\models\UserOrderLogs::recordLog($object->id,'创建订单','创建订单');
            }elseif ($event->name==ActiveRecord::EVENT_BEFORE_DELETE){
                //删除
                \common\models\UserOrderLogs::recordLog($object->id,'删除订单','删除订单');
            }elseif ($event->name==ActiveRecord::EVENT_AFTER_UPDATE){


                if(array_key_exists('is_produce',$changedAttributes)){
                    $name = \common\models\Order::getProduceInfo($object->is_produce,'name');
                    \common\models\UserOrderLogs::recordLog($object->id,'调整订单信息','调整订单生成状态为:'.$name);
                }

                if(array_key_exists('is_send',$changedAttributes)){
                    $name = \common\models\Order::getSendInfo($object->is_send,'name');
                    \common\models\UserOrderLogs::recordLog($object->id,'调整订单信息','调整订单发货状态为:'.$name);
                }

                if(array_key_exists('is_receive',$changedAttributes)){
                    $name = \common\models\Order::getReceiveInfo($object->is_receive,'name');
                    \common\models\UserOrderLogs::recordLog($object->id,'调整订单信息','调整订单收货状态为:'.$name);
                }
                //更新
                if(array_key_exists('status',$changedAttributes)){
                    $name = \common\models\Order::getStatusInfo($object->status,'name');
                    \common\models\UserOrderLogs::recordLog($object->id,'调整订单信息','调整订单状态为:'.$name);
                }

            }
        }

    }
}