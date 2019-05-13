<?php

$this->params = [
    'crumb'          => ['系统设置','热门词'],
];
?>
<?php $this->beginBlock('content')?>



    <div class="box">
        <div class="box-header with-border">
            <a href="<?=\yii\helpers\Url::to(['hot-kw-add'])?>" class="btn bg-olive margin">新增</a>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <table class="table table-bordered" id="layer-photos-demo">
                <colgroup>
                    <col width="80">
                    <col width="200">
                    <col width="120">
                    <col width="80">
                    <col width="80">
                </colgroup>
                <thead>
                <tr>
                    <th>#</th>
                    <th>关键字</th>
                    <th>状态</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($list as $key=>$vo){?>
                    <tr>
                        <td><?=$key+1?></a></td>
                        <td><?=$vo['keyword']?></a></td>
                        <td><?=\common\models\HotKw::getStatusName($vo['status'])?></td>
                        <td>
                            <a class="layui-btn layui-btn-sm" href="<?=\yii\helpers\Url::to(['hot-kw-add','id'=>$vo['id']])?>">编辑</a>
                            <a class="layui-btn layui-btn-danger  layui-btn-sm"  href="javascript:;" onclick="$.common.del('<?= \yii\helpers\Url::to(['hot-kw-del','id'=>$vo['id']])?>','删除')" class="ml-5">  删除</a>
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

<?php $this->beginBlock('script');?>

<script>

    $(function(){
        layui.use(['layer'], function(){
            var layer = layui.layer;
            layer.photos({
                photos: '#layer-photos-demo'
                ,anim: 5 //0-6的选择，指定弹出图片动画类型，默认随机（请注意，3.0之前的版本用shift参数）
            });
        });
    })
</script>
<?php $this->endBlock();?>
