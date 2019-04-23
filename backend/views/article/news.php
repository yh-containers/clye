<?php

    $this->params = [
            'crumb'          => ['系统设置','管理员列表'],
    ];
?>
<?php $this->beginBlock('content')?>



    <div class="box">
        <div class="box-header with-border">
            <a href="<?=\yii\helpers\Url::to(['news-add','type'=>$type])?>" class="btn bg-olive margin">新增(<?=$name?>)</a>
        </div>
        <!-- /.box-header -->
        <div class="box-body">

            <table class="table table-bordered" id="layer-photos-demo">
                <colgroup>
                    <col width="40">
                    <col width="250">
                    <col width="80">
                    <col width="80">
                    <col width="200">
                    <col width="120">
                    <col width="80">
                    <col width="120">
                </colgroup>
                <thead>
                <tr>
                    <th>#</th>
                    <th>标题</th>
                    <th>作者</th>
                    <th>封面图</th>
                    <th>简述</th>
                    <th>发布时间</th>
                    <th>状态</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($list as $key=>$vo) {?>
                    <tr>
                        <td><?=$key+1?></td>
                        <td title="<?=$vo['title']?>">
                            <a href="<?=\yii\helpers\Url::to(['news-add','id'=>$vo['id'],'type'=>$type])?>">
                                <?=mb_strlen($vo['title'],'utf-8')>20?mb_substr($vo['title'],0,20,'utf-8').'...':$vo['title']?>
                            </a>
                        </td>
                        <td><?=$vo['author']?></td>
                        <td><?=$vo['img']?'<img src="'.$vo['img'].'" alt="'.$vo['title'].'" width="80" height="80">':''?> </td>
                        <td title="<?=$vo['intro']?>"><?=mb_strlen($vo['intro'],'utf-8')>20?mb_substr($vo['intro'],0,20,'utf-8').'...':$vo['intro']?></td>
                        <td><?=$vo['show_time']?></td>
                        <td><?=\common\models\Article::getStatusName($vo['status'])?></td>
                        <td>
                            <a href="<?=\yii\helpers\Url::to(['news-add','id'=>$vo['id'],'type'=>$type])?>">编辑</a>
                            <a  href="javascript:;" onclick="$.common.del('<?= \yii\helpers\Url::to(['news-del','id'=>$vo['id'],'type'=>$type])?>','删除')" class="ml-5">  删除</a>
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