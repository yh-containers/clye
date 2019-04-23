<?php

    $this->params = [
            'crumb'          => ['系统设置','管理员列表'],
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
                    <th>#</th>
                    <th>类型</th>
                    <th>操作人</th>
                    <th>说明</th>
                    <th>操作时间</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($list as $key=>$vo) {?>
                    <tr>
                        <td><?=$key+1?></td>
                        <td><?=\common\models\SysOptLogs::getTypeIntro($vo['type'],'name')?></td>
                        <td><?=$vo['linkManager']['name']?></td>
                        <td><?=$vo['content']?> </td>
                        <td><?=$vo['create_time']?></td>

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