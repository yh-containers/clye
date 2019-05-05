<?php

$this->params = [
    'crumb'          => ['订单管理','订单列表'],
];
?>
<?php $this->beginBlock('content')?>



    <div class="box">
        <div class="box-header with-border">
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
                    <th width="80">状态</th>
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
                        <td><?=
                            \common\models\Order::getStepFlowInfo($vo['step_flow'],'name')
                            ?></td>
                        <td>
                            <a href="<?=\yii\helpers\Url::to(['detail','id'=>$vo['id']])?>">查看</a>
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


<?php $this->endBlock()?>