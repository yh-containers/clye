<?php

$this->params = [
    'crumb'          => ['会员管理','用户列表'],
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
                    <th width="120">用户名</th>
                    <th width="100">用户类型</th>
                    <th width="100">手机号</th>
                    <th width="100">邮箱</th>
                    <th width="60">性别</th>
                    <th width="200">所属地区</th>
                    <th width="200">公司名称</th>
                    <th width="80">联系人</th>
                    <th width="80">创建时间</th>
                    <th width="40">状态</th>
                    <th width="80">操作</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($list as $key=>$vo) {?>
                    <tr>
                        <td><?=$key+1?></td>
                        <td><?=$vo['username']?></td>
                        <td><?=$vo['type']?></td>
                        <td><?=$vo['phone']?></td>
                        <td><?=$vo['email']?></td>
                        <td><?=\common\models\User::getSexInfo($vo['type']) ?></td>
                        <td>
                            <p>行政区:<?=$vo['linkAreaInfo']['name']?> </p>
                            <p>地区:<?=$vo['linkProvince']['name'].' '.$vo['linkCity']['name']?> </p>
                        </td>

                        <td><?=$vo['company_name']?> </td>
                        <td><?=$vo['contacts']?> </td>
                        <td><?=$vo['create_time']?date('Y-m-d',$vo['create_time']):''?></td>
                        <td><?=\common\models\Goods::getStatusName($vo['status'])?></td>
                        <td>
                            <a href="<?=\yii\helpers\Url::to(['add','id'=>$vo['id']])?>">编辑</a>
                            <a href="<?=\yii\helpers\Url::to(['detail','id'=>$vo['id']])?>">查看</a>
                            <a  href="javascript:;" onclick="$.common.del('<?= \yii\helpers\Url::to(['del','id'=>$vo['id']])?>','删除')" class="ml-5">  删除</a>
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