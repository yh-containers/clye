<?php
    //用于显示左侧栏目选中状态
    $this->params = [
        'crumb'          => ['文章管理','药品证书'],
    ];
?>
<?php $this->beginBlock('content'); ?>
<div class="box box-info">
    <!-- /.box-header -->
    <!-- form start -->
        <div class="box-body">
            <div class="box-header with-border">
                <a href="<?=\yii\helpers\Url::to(['cert-add'])?>" class="btn bg-olive margin">新增</a>
            </div>
            <table class="table table-bordered" id="layer-photos-demo">
                <colgroup>
                    <col width="40">
                    <col width="250">
                    <col width="120">
                    <col width="120">
                    <col width="120">
                    <col width="120">
                </colgroup>
                <thead>
                <tr>
                    <th>#</th>
                    <th>标题</th>
                    <th>图片</th>
                    <th>状态</th>
                    <th>更新时间</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($list as $key=>$vo) {?>
                    <tr>
                        <td><?=$key+1?></td>
                        <td title="<?=$vo['title']?>">
                            <a href="<?=\yii\helpers\Url::to(['cert-add','id'=>$vo['id']])?>">
                                <?=mb_strlen($vo['title'],'utf-8')>30?mb_substr($vo['title'],0,30,'utf-8').'...':$vo['title']?>
                            </a>
                        </td>
                        <td><img src="<?=$vo['img']?>" alt="<?=$vo['title']?>" width="80" height="80"/></td>
                        <td><?=\common\models\Cert::getStatusName($vo['status'])?></td>
                        <td><?=$vo->updateTime?></td>
                        <td>
                            <a href="<?=\yii\helpers\Url::to(['cert-add','id'=>$vo['id']])?>">编辑</a>
                            <a  href="javascript:;" onclick="$.common.del('<?= \yii\helpers\Url::to(['cert-del','id'=>$vo['id']])?>','删除')" class="ml-5">  删除</a>
                        </td>
                    </tr>
                <?php }?>
                </tbody>
            </table>

        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <?= \yii\widgets\LinkPager::widget(['pagination'=>$pagination])?>
        </div>
        <!-- /.box-footer -->
</div>


<?php $this->endBlock(); ?>

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

