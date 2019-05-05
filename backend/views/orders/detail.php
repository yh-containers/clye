<?php
    $this->title = '订单管理';
    $this->params = [
            'crumb'          => ['订单管理','订单详情'],
    ];
?>
<?php $this->beginBlock('style')?>
    <style>
        .layui-table[lay-size=lg] td{padding: 9px 15px; }
    </style>
<?php $this->endBlock()?>
<?php $this->beginBlock('content')?>



         <div class="col-sm-9">

             <div class="box">
                     <div class="box-header with-border">
                         <h3>订单基本信息</h3>
                     </div>
                     <div class="box-body">
                         <table class="layui-table"  lay-size="lg">
                             <colgroup>
                                 <col width="170">
                                 <col width="190">
                                 <col width="170">
                                 <col width="190">
                                 <col width="170">
                                 <col width="190">
                                 <col width="170">
                                 <col width="190">
                             </colgroup>

                             <tbody>
                             <tr>
                                 <td>订单号</td>
                                 <td><?=$model['no']?></td>
                                 <td>用户名</td>
                                 <td><?=$model['linkUser']['username']?></td>
                                 <td>状态</td>

                                 <td colspan="3"><?=
                                     \common\models\Order::getStatusInfo($model['status'],'name').','.
                                     \common\models\Order::getProduceInfo($model['is_produce'],'name').','.
                                     \common\models\Order::getSendInfo($model['is_send'],'name').','.
                                     \common\models\Order::getReceiveInfo($model['is_receive'],'name')
                                     ?></td>
                             </tr>
                             <tr>
                                 <td>订单金额</td>
                                 <td><?=$model['money']?></td>
                                 <td>支付</td>
                                 <td><?=$model['pay_money']?></td>
                                 <td>运费</td>
                                 <td><?=$model['freight_money']?></td>
                                 <td>税费</td>
                                 <td><?=$model['taxation_money']?></td>
                             </tr>

                             <tr>
                                 <td>创建时间</td>
                                 <td><?=$model['createTime']?></td>
                                 <td>生产时间</td>
                                 <td><?=$model['pro_start_time']?date('Y-m-d H:i:s',$model['pro_start_time']):''?></td>
                                 <td>生产完成时间</td>
                                 <td><?=$model['pro_end_time']?date('Y-m-d H:i:s',$model['pro_end_time']):''?></td>
                                 <td>发货时间</td>
                                 <td><?=$model['send_end_time']?date('Y-m-d H:i:s',$model['send_end_time']):''?></td>
                             </tr>
                             <tr>
                                 <td>收货时间</td>
                                 <td><?=$model['receive_end_time']?date('Y-m-d H:i:s',$model['receive_end_time']):''?></td>
                                 <td></td>
                                 <td></td>
                                 <td></td>
                                 <td></td>
                                 <td>完成时间</td>
                                 <td><?=$model['complete_time']?></td>
                             </tr>
                             <tr style="background: #f5f5f5;font-weight: bold">
                                 <td colspan="8">收货地址</td>
                             </tr>
                             <tr>
                                 <td>收货人:</td>
                                 <td><?=$model['linkOrderAddr']['username']?></td>
                                 <td>手机号码</td>
                                 <td><?=$model['linkOrderAddr']['phone']?></td>
                                 <td>地址</td>
                                 <td colspan="3"><?=$model['linkOrderAddr']['addr'].'  '.$model['linkOrderAddr']['addr_extra']?></td>
                             </tr>
                             <tr style="background: #f5f5f5; font-weight: bold">
                                 <td colspan="8">发货信息</td>
                             </tr>
                             <tr>
                                 <td>物流公司:</td>
                                 <td><?=$model['linkOrderLogistics']['company']?></td>
                                 <td>物流单号</td>
                                 <td><?=$model['linkOrderLogistics']['no']?></td>
                                 <td>物流价格</td>
                                 <td><?=$model['linkOrderLogistics']['money']?></td>
                                 <td></td>
                                 <td></td>
                             </tr>

                             </tbody>
                         </table>
                     </div>

            </div>
             <div class="box">
                 <div class="box-header with-border">
                     <h3>订单商品</h3>
                 </div>
                 <div class="box-body">
                     <table class="layui-table"  lay-size="lg">
                         <thead>
                         <tr>
                             <th>商品名称</th>
                             <th>商品价格</th>
                             <th>手续费</th>
                             <th>手续费后价格</th>
                             <th>税费</th>
                             <th>运费</th>
                             <th>数量</th>
                         </tr>
                         </thead>

                         <tbody>
                         <?php if(!empty($model)) foreach($model['linkOrderGoods'] as $vo){?>
                             <tr>
                                 <td><?=$vo['name']?></td>
                                 <td><?=$vo['price']?></td>
                                 <td><?=$vo['per']?></td>
                                 <td><?=$vo['per_price']?></td>
                                 <td><?=$vo['freight_money']?></td>
                                 <td><?=$vo['taxation_money']?></td>
                                 <td><?=$vo['num']?></td>
                             </tr>
                         <?php }?>
                         </tbody>
                     </table>
                 </div>

             </div>

         </div>

        <div class="col-sm-3">
            <div class="box">
                <div class="box-header with-border">
                    <h3>订单操作</h3>
                </div>
                <div class="box-body">
                    <?php if(in_array('sure_pay',$opt_handle)){?>
                        <a href="javascript:;" class="btn btn-default opt-order" data-href="<?=\yii\helpers\Url::to(['sure-pay'])?>" data-confirm_title="确定已收到付款?" data-req_data="{id:<?=$model['id']?>}" >确认付款</a>
                    <?php }?>
                    <?php if(in_array('product_up',$opt_handle)){?>
                        <a href="javascript:;" class="btn btn-default opt-order" data-href="<?=\yii\helpers\Url::to(['product-up'])?>" data-confirm_title="是否开始生产?" data-req_data="{id:<?=$model['id']?>}" >开始生产</a>
                    <?php }?>
                    <?php if(in_array('product_down',$opt_handle)){?>
                        <a href="javascript:;" class="btn btn-default opt-order"  data-href="<?=\yii\helpers\Url::to(['product-down'])?>" data-confirm_title="生产结束?" data-req_data="{id:<?=$model['id']?>}">生产结束</a>
                    <?php }?>
                    <?php if(in_array('send_up',$opt_handle)){?>
                        <a href="javascript:;" class="btn btn-default opt-order"  data-href="<?=\yii\helpers\Url::to(['send-up'])?>" data-confirm_title="是否准备发货?" data-req_data="{id:<?=$model['id']?>}">准备发货</a>
                    <?php }?>
                    <?php if(in_array('send_down',$opt_handle)){?>

                        <a href="javascript:;" class="btn btn-default opt-order send-order"  data-href="<?=\yii\helpers\Url::to(['send-down'])?>" data-confirm_title="确定已发货？" data-req_data="{id:<?=$model['id']?>}" >发货完成</a>
                    <?php }?>
                    <a href="<?=\yii\helpers\Url::to(['contract','oid'=>$model['id']])?>" target="_blank" class="btn btn-default">查看合同信息</a>

                </div>

            </div>
            <div class="box">
                <div class="box-header with-border">
                    <h3>操作日志</h3>
                </div>
                <div class="box-body">
                    <ul class="layui-timeline">
                        <?php if(!empty($model)) foreach($model['linkOrderLogs'] as $vo){?>
                            <li class="layui-timeline-item">
                                <i class="layui-icon layui-timeline-axis">&#xe63f;</i>
                                <div class="layui-timeline-content layui-text">
                                    <h3 class="layui-timeline-title"><?=$vo['create_time']?></h3>
                                    <p>
                                        <?=$vo['intro']?>
                                    </p>
                                </div>
                            </li>
                        <?php }?>
                    </ul>
                </div>

            </div>
        </div>

<!--发货-->
<div id="send-order" style="display: none;">
    <div class="form-group">
        <label for="inputPassword3" class="col-sm-3 control-label">发货单号:</label>
        <div class="col-sm-8 margin-bottom">
            <input type="text" maxlength="100" class="form-control" name="no"  placeholder="发货单号">
        </div>
    </div>
    <div class="form-group">
        <label for="inputPassword3" class="col-sm-3 control-label">物流公司:</label>
        <div class="col-sm-8 margin-bottom">
            <input type="text" maxlength="100" class="form-control" name="company"  placeholder="物流公司">
        </div>
    </div>
    <div class="form-group">
        <label for="inputPassword3" class="col-sm-3 control-label">运费:</label>
        <div class="col-sm-8 margin-bottom">
            <input type="number"  class="form-control" name="money"  placeholder="0.00">
        </div>
    </div>
</div>



<?php $this->endBlock()?>

<?php $this->beginBlock('script')?>
<script>
    $(function(){
        $(".opt-order").click(function(){
            var href = $(this).data('href')
            var req_data = $(this).data('req_data')
            var confirm_title = $(this).data('confirm_title')
            req_data = eval('('+req_data+')')

            //订单发货
            if($(this).hasClass('send-order')){
                layer.open({
                    type:1
                    ,title:'填写发货信息'
                    ,btn: ['确认', '取消']
                    ,area:['400px','300px']
                    ,content:$("#send-order")
                    ,yes: function(index, layero){
                    //按钮【按钮一】的回调
                        req_data.logistics={}
                        req_data['logistics']['no']=$("#send-order input[name='no']").val()
                        req_data['logistics']['company']=$("#send-order input[name='company']").val()
                        req_data['logistics']['money']=$("#send-order input[name='money']").val()
                        //请求数据
                        reqInfo(href,req_data,confirm_title)
                    }
                })
            }else{
                //请求数据
                reqInfo(href,req_data,confirm_title)
            }
        })

        function reqInfo(href,req_data,confirm_title){
            layer.confirm(confirm_title,function(){
                var index = layer.load(3)
                $.get(href,req_data,function(result){
                    layer.close(index)
                    layer.msg(result.msg)
                    if(result.code==1){
                        setTimeout(function(){location.reload()},1000)
                    }
                })
            })
        }
    })
</script>
<?php $this->endBlock()?>
