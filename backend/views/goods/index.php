<?php

$this->params = [
    'crumb'          => ['商品管理','商品列表'],
];
?>
<?php $this->beginBlock('content')?>



    <div class="box">
        <div class="box-header with-border">
            <a href="<?=\yii\helpers\Url::to(['add'])?>" class="btn bg-olive margin">新增</a>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <table class="table table-bordered">

                <thead>
                <tr>
                    <th width="20">#</th>
                    <th width="200">商品名</th>
                    <th width="60">分类名</th>
                    <th width="60">价格</th>
                    <th width="60">库存</th>
                    <th width="120">更新时间</th>
                    <th width="80">状态</th>
                    <th width="80">操作</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($list as $key=>$vo) {?>
                    <tr>
                        <td><?=$key+1?></td>
                        <td title="<?=$vo['name']?>">
                            <a href="<?=\yii\helpers\Url::to(['add','id'=>$vo['id']])?>"> <?=mb_strlen($vo['name'],'utf-8')>20?mb_substr($vo['name'],0,20).'...':$vo['name']?></a>
                        </td>
                        <td><?=$vo['linkCate']['name']?></td>
                        <td><?=$vo['price']?> </td>
                        <td><?=$vo['stock']?> </td>
                        <td><?=$vo['updateTime']?></td>
                        <td><?=\common\models\Goods::getStatusName($vo['status'])?></td>
                        <td>
                            <a href="<?=\yii\helpers\Url::to(['add','id'=>$vo['id']])?>">编辑</a>
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