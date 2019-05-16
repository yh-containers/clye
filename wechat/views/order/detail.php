<?php
$this->title='订单详情';
$this->params = array_merge($this->params,[
]);

?>

<?php $this->beginBlock('content')?>
<div class="header">
    <a class="back" href="<?=\yii\helpers\Url::to(['index'])?>"></a>
    <h4><?=$this->title?></h4>
</div>

<div class="main">
    <div class="payment_title">
        <div class="text">
            <p><?=$current_step_info['name']?></p>
            <span><?=$current_step_info['intro']?></span>
        </div>

    </div>
    <div class="dynamic-info">
        <?php if(isset($current_step_info['handle'])){
            //是否显示物流信息
            $is_logistics_bool = true;

            //为数组验证条件是否满足
             if(is_array($current_step_info['handle']) && array_key_exists('logistics',$current_step_info['handle'])){
                 foreach ($current_step_info as $key=>$vo){
                     if(is_array($vo)){
                         foreach ($vo as $con_key=>$con_val){
                             foreach ($con_val as $attr_key=>$attr_val){
                                 if(empty($model[$attr_key]) || $model[$attr_key]!=$attr_val){
                                     $is_logistics_bool=false;
                                     break;
                                 }
                             }
                         }
                     }


                 }
             }elseif (!in_array('logistics',$current_step_info['handle'])){
                 $is_logistics_bool=false;
             }

             if($is_logistics_bool){
        ?>
        <div class="status-info">
            <a href="<?=\yii\helpers\Url::to(['logistics','id'=>$model['id']])?>">
                <i></i>
                <div class="con">
                    <p><?=$current_step_info['name']?></p>
                    <span>
                        <?php if($model['is_receive']){?>
                            <?=$model['receive_end_time']?date('Y-m-d H:i:s',$model['receive_end_time']):($model['receive_start_time']?date('Y-m-d H:i:s',$model['send_start_time']):'')?>
                        <?php }else{?>
                            <?=$model['send_end_time']?date('Y-m-d H:i:s',$model['send_end_time']):($model['send_start_time']?date('Y-m-d H:i:s',$model['send_start_time']):'')?>
                        <?php }?>


                    </span>
                </div>
            </a>
        </div>
        <?php } }?>
        <div class="address-info">
            <i></i>
            <div class="con">
                <p>
                    <font><?=$model['linkOrderAddr']['username']?></font><em><?=substr_replace($model['linkOrderAddr']['phone'],'****',3,4)?></em></p>
                <span><?=$model['linkOrderAddr']['addr'].'  '.$model['linkOrderAddr']['addr_extra']?> </span>
            </div>
        </div>
    </div>
    <div id="prod-detail">
        <div class="prod-info clearfix">
            <div class="shop-name">
                <h4>商品清单</h4>
            </div>
            <?php
            //商品金额
            $goods_money  = 0.00;
            if(!empty($model['linkOrderGoods']))
                foreach($model['linkOrderGoods'] as $vo){
                    $goods_money+=$vo['per_price']*$vo['num'];
            ?>
            <div class="row clearfix">
                <img src="<?=\common\models\Goods::getCoverImg($vo['img'])?>">
                <div class="prod-desc">
                    <span class="name"><?=$vo['name']?></span>
                    <span class="level clearfix">
                            <span class="price">¥<em><?=$vo['per_price']?></em></span>
                            <span class="mun">x<?=$vo['num']?></span>
                        </span>
                </div>
            </div>
            <?php }?>

        </div>
        <div class="choose-operate">
            <div class="oItem">
                <a href="<?=\yii\helpers\Url::to(['mine/contract-detail','id'=>$model['linkOrderContract']['id']])?>">
                    <div class="itemName">合同</div>
                    <div class="itemInfo oneLine">
                        <p class="iiItems selected">去查看</p>
                    </div>
                    <i class="oIcon oArrow"></i>
                </a>
            </div>
        </div>

        <ul class="order_pay_info">
            <li><span>商品金额</span><em>¥<i><?=sprintf('%.2f',$goods_money)?></i></em></li>
            <li><span>税费</span><em>¥<i><?=$model['taxation_money']?></i></em></li>

            <div class="total"><span>实付总额</span><em class="price">¥<i><?=$model['pay_money']?></i></em></div>
        </ul>
        <div class="orders_det_mode">
            <div class="row">
                <div class="item">
                    <label>订单编号：</label>
                    <span><?=$model['no']?></span>
                </div>
                <div class="item">
                    <label>合同编号：</label>
                    <span><?=$model['linkOrderContract']['no']?></span>
                </div>
                <div class="item">
                    <label>创建时间：</label>
                    <span><?=$model['createTime']?></span>
                </div>
                <?php if($model['pay_time']){?>
                <div class="item">
                    <label>付款时间：</label>
                    <span><?=date('Y-m-d H:i:s',$model['pay_time'])?></span>
                </div>
                <?php }?>
            </div>
        </div>

        <div class="footer">
            <div class="aui-bar-tab orders_tab_btn ">
                <?php if(isset($current_step_info['handle']) && (in_array('receive',$current_step_info['handle']))){?>
                    <a href="javascript:;" class="mod_btn bg_orange" id="confirm"
                       onclick="$.common.reqInfo(this,{confirm_title:'确定收货？'})"
                       data-conf="{url:'<?=\yii\helpers\Url::to(['order/receive'])?>',data:{id:<?=$model['id']?>},success:receive_success}" class="mod_btn bg_orange"
                    ">确认收货</a>
                <?php }?>
                <?php
                    $is_delete = false;
                if(isset($current_step_info['handle']) && in_array('delete', $current_step_info['handle']) ) {

                ?>
                    <a href="javascript:;" onclick="$.common.reqInfo(this,{confirm_title:'是否删除订单'})" data-conf="{url:'<?=\yii\helpers\Url::to(['order/del'])?>',data:{id:<?=$model['id']?>},success:del_order}" class="mod_btn bg_orange">删除订单</a>
                <?php }?>
                <?php if(isset($current_step_info['handle']) && (in_array('cancel_order',$current_step_info['handle']))){?>
                    <a href="javascript:;" onclick="$.common.reqInfo(this,{confirm_title:'是否取消订单'})" data-conf="{url:'<?=\yii\helpers\Url::to(['order/cancel-order'])?>',data:{id:<?=$model['id']?>},success:cancel_order}" class="mod_btn bg_orange">取消订单</a>
                <?php }?>
                <a href="tel:<?=\wechat\widgets\SysSetting::widget(['type'=>'normal','field'=>'kf_tel'])?>" class="mod_btn bg_border">联系客服</a>
            </div>
        </div>
    </div>
</div>
<?php $this->endBlock()?>

<?php $this->beginBlock('script')?>
<script>
    //取消订单刷新页面
    function cancel_order(res){
        layui.layer.msg(res.msg)
        if(res.code==1){
            setTimeout(function(){location.reload()},1000)
        }
    }
    //删除订单刷新页面
    function del_order(res){
        layui.layer.msg(res.msg)
        if(res.code==1){
            setTimeout(function(){window.history.back()},1000)
        }
    }
    //删除订单刷新页面
    function receive_success(res){
        layui.layer.msg(res.msg)
        if(res.code==1){
            setTimeout(function(){window.history.back()},1000)
        }
    }


</script>

<?php $this->endBlock()?>
