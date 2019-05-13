<?php

$this->params = [
    'crumb'          => ['会员管理','用户类型'],
];
?>
<?php $this->beginBlock('content')?>



    <div class="box">
        <div class="box-header with-border">
            <a href="<?=\yii\helpers\Url::to(['type-add'])?>" class="btn bg-olive margin">新增</a>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <table class="table table-bordered">

                <thead>
                <tr>
                    <th width="20">#</th>
                    <th width="120">用户名</th>
                    <th width="100">购买比例</th>
                    <th width="80">操作</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($list as $key=>$vo) {?>
                    <tr>
                        <td><?=$key+1?></td>
                        <td><?=$vo['name']?></td>
                        <td><?=$vo['per']?></td>

                        <td>
                            <a href="<?=\yii\helpers\Url::to(['type-add','id'=>$vo['id']])?>">编辑</a>
                            <?php if($vo['id']!=1){?>
                                <a href="javascript:;" onclick="$.common.del('<?=\yii\helpers\Url::to(['type-del','id'=>$vo['id']])?>')">删除</a>
                            <?php }?>
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