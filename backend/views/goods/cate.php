<?php
$this->params = [
    'crumb' => ['商品管理','商品分类'],
];
?>
<?php $this->beginBlock('content')?>



    <div class="box">
        <div class="box-header with-border">
            <a href="<?=\yii\helpers\Url::to(['cate-add'])?>" class="btn bg-olive margin">新增</a>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <table class="table table-bordered" id="layer-photos-demo">
                <thead>
                <tr>
                    <th>一级分类</th>
                    <th>二级分类</th>
                    <th>状态</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($data as $key=>$vo){?>
                    <tr>
                        <td rowspan="<?=count($vo['linkChild'])+1?>"><?=$vo['name']?></td>
                        <td>--</td>
                        <td><?=\common\models\GoodsCate::getStatusName($vo['status'])?></td>
                        <td>
                            <a href="<?=\yii\helpers\Url::to(['add','id'=>$vo['id']])?>">编辑</a>
                            <a  href="javascript:;" onclick="$.common.del('<?= \yii\helpers\Url::to(['cate-del','id'=>$vo['id']])?>','删除')" class="ml-5">  删除</a>
                        </td>
                    </tr>
                    <?php foreach($vo['linkChild'] as $item){?>
                        <tr>
                            <td><?=$item['name']?></td>
                            <td><?=\common\models\GoodsCate::getStatusName($item['status'])?></td>
                            <td>
                                <a href="<?=\yii\helpers\Url::to(['cate-add','id'=>$item['id']])?>">编辑</a>
                                <a  href="javascript:;" onclick="$.common.del('<?= \yii\helpers\Url::to(['cate-del','id'=>$item['id']])?>','删除')" class="ml-5">  删除</a>
                            </td>
                        </tr>
                    <?php }?>
                <?php }?>
                </tbody>
            </table>
        </div>
        <!-- /.box-body -->

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
