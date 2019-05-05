<?php

$this->params = [
    'crumb'          => ['系统设置','模版管理','消息模版'],
];
?>
<?php $this->beginBlock('content')?>

    <div class="box">
        <div class="box-header with-border">
            所属行业:
            <?php if(!empty($industry)) foreach($industry as $vo) {?>
                <?=$vo['first_class'].'/'.$vo['second_class']?>;
            <?php }?>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <table class="table table-bordered">

                <thead>
                <tr>
                    <th width="120">模版id</th>
                    <th width="120">标题</th>
                    <th width="60">一级行业</th>
                    <th width="120">二级行业</th>
                    <th width="400">内容</th>
                </tr>
                </thead>
                <tbody>
                    <?php foreach($temp as $vo){?>
                        <tr>
                            <td><?=$vo['template_id']?></td>
                            <td><?=$vo['title']?></td>
                            <td><?=$vo['primary_industry']?></td>
                            <td><?=$vo['deputy_industry']?></td>
                            <td><?=$vo['content']?></td>
                        </tr>
                    <?php }?>

                </tbody>
            </table>
        </div>
        <!-- /.box-body -->

    </div>

<?php $this->endBlock()?>