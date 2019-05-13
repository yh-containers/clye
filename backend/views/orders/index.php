<?php

$this->params = [
    'crumb'          => ['订单管理','订单列表'],
];
?>
<?php $this->beginBlock('content')?>



    <div class="box">
        <div class="box-header with-border">
            <div class="row">
                <div class="col-sm-4">
                    <div class="btn-group margin">
                        <a href="<?=\yii\helpers\Url::to([''])?>" class="btn  <?=empty($state)?'btn-primary':'btn-default'?>">全部</a>
                        <a href="<?=\yii\helpers\Url::to(['','state'=>1])?>" class="btn <?=$state==1?'btn-primary':'btn-default'?> ">未支付</a>
                        <a href="<?=\yii\helpers\Url::to(['','state'=>4])?>" class="btn <?=$state==4?'btn-primary':'btn-default'?>">生产中</a>
                        <a href="<?=\yii\helpers\Url::to(['','state'=>6])?>" class="btn <?=$state==6?'btn-primary':'btn-default'?>">待发货</a>
                        <a href="<?=\yii\helpers\Url::to(['','state'=>8])?>" class="btn <?=$state==8?'btn-primary':'btn-default'?>">待收货</a>
                        <a href="<?=\yii\helpers\Url::to(['','state'=>10])?>" class="btn <?=$state==10?'btn-primary':'btn-default'?>">已完成</a>
                    </div>                    
                </div>
                <div class="col-sm-8">
                    <form class="col-sm-6">
                        <div class="input-group col-sm-12 margin">
                            <input type="text" name="keyword" value="<?=$keyword?>" placeholder="订单号" class="form-control">
                            <span class="input-group-btn">
                              <button type="submit" class="btn btn-primary btn-flat">搜索</button>
                            </span>
                        </div>
                    </form>
                    <a href="<?=\yii\helpers\Url::to(['orders/export-excel','state'=>$state,'keyword'=>$keyword])?>" class="btn btn-warning margin" style="float:right"><i class="fa fa-cloud-download" aria-hidden="true" style="font-size:18px;float:left;margin:2px 5px 0px 0px;"></i> 导出excel</a>
                </div>

            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <table class="table table-bordered">

                <thead>
                <tr>
                    <th width="20">#</th>
                    <th width="120">订单号</th>
                    <th width="60">用户名</th>
                    <th width="60">订单金额</th>
                    <th width="60">支付金额</th>
                    <th width="60">运费</th>
                    <th width="60">税费</th>
                    <th width="120">创建时间</th>
                    <th width="80">行政区</th>
                    <th width="80">跟进人</th>
                    <th width="80">状态(流程)</th>
                    <th width="80">操作</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($list as $key=>$vo) {?>
                    <tr>
                        <td><?=$key+1?></td>
                        <td>
                            <?=$vo['no']?>
                        </td>
                        <td><a href="<?=\yii\helpers\Url::to(['user/detail','id'=>$vo['uid']])?>"><?=$vo['linkUser']['username']?></a></td>
                        <td><?=$vo['money']?> </td>
                        <td><?=$vo['pay_money']?> </td>
                        <td><?=$vo['freight_money']?> </td>
                        <td><?=$vo['taxation_money']?> </td>
                        <td><?=$vo['createTime']?></td>
                        <td><?=$vo['linkLocationArea']['name']?></td>
                        <td><?=$vo['linkFlowManager']['name']?></td>
                        <td>
                            <?php
                            $state_info = $vo->getOrderStatusInfo();
                            echo $state_info['name'].'('.\common\models\Order::getStepFlowInfo($vo['step_flow'],'name').')';
                            ?>
                        </td>
                        <td>
                            <a class="layui-btn layui-btn-sm" href="<?=\yii\helpers\Url::to(['detail','id'=>$vo['id']])?>">查看</a>
                            <a class="layui-btn layui-btn-normal layui-btn-sm point-manager" href="javascript:;" data-id="<?=$vo['id']?>">分配跟进人</a>
                        </td>
                    </tr>
                <?php }?>
                </tbody>
            </table>
        </div>
        <!-- /.box-body -->
        <div class="box-footer clearfix">
            <?= \yii\widgets\LinkPager::widget(['pagination'=>$pagination])?>
        </div>
    </div>

<div id="point-manager" style="display: none;">
    <div class="form-group">
        <label for="inputPassword3" class="col-sm-3 control-label">选择员工:</label>
        <div class="col-sm-8 margin-bottom">
            <select name="manage_uid"  class="form-control">
                <?php foreach($manager as $vo){?>
                    <option value="<?=$vo['id']?>"><?=$vo['name']?></option>
                <?php }?>
            </select>
        </div>
    </div>
</div>

<?php $this->endBlock()?>

<?php $this->beginBlock('script')?>
<script>
    $(function(){
        $(".point-manager").click(function(){
            var req_data = {}
            req_data.id=$(this).data('id')
            layer.open({
                type:1
                ,title:'指派员工'
                ,btn: ['确认', '取消']
                ,area:['400px','300px']
                ,content:$("#point-manager")
                ,yes: function(index, layero){
                    //按钮【按钮一】的回调
                    //员工id
                    req_data.uid = $("select[name='manage_uid']").val()
                    console.log(req_data);
                    //请求数据
                    reqInfo("<?=\yii\helpers\Url::to(['point-manager'])?>",req_data,'是否指派该员工')
                }
            })
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
