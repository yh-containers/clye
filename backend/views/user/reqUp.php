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
                    <th width="100">申请时间</th>
                    <th width="100">处理时间</th>
                    <th width="40">状态</th>
                    <th width="80">操作</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($list as $key=>$vo) {?>
                    <tr>
                        <td><?=$key+1?></td>
                        <td><a href="<?=\yii\helpers\Url::to(['detail','id'=>$vo['uid']])?>"><?=$vo['linkUser']['username']?></a></td>
                        <td><?=$vo['linkUser']['linkType']['name']?></td>

                        <td><?=$vo['create_time']?date('Y-m-d H:i:s',$vo['create_time']):''?></td>
                        <td><?=$vo['handle_time']?date('Y-m-d H:i:s',$vo['handle_time']):''?></td>
                        <td><?=\common\models\UserReqUp::getStatusName($vo['status'])?></td>
                        <td>
                            <?php if(!$vo['status']){?>
                            <a href="javascript:;" class="handle-status" data-id="<?=$vo['id']?>">处理</a>
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
<?php $this->beginBlock('script')?>
<script>
    var handle_url = "<?=\yii\helpers\Url::to(['handle-req'])?>"
    var _csrf = "<?= Yii::$app->request->csrfToken ?>"
    $(function(){
        $(".handle-status").click(function(){
            var id=$(this).data('id');
            layer.open({
                type:1
                ,title:'处理数据'
                ,content:'请选择要处理的方式'
                ,btn:['通过','拒绝','取消']
                ,yes: function(index, layero){
                    //do something
                    $.common.confirm(handle_url,{id:id,status:1,_csrf:_csrf},'是否通过?')
                    layer.close(index); //如果设定了yes回调，需进行手工关闭
                }
                ,btn2: function(index, layero){
                    //按钮【按钮二】的回调
                    $.common.confirm(handle_url,{id:id,status:2,_csrf:_csrf},'是否拒绝通过?')
                    return false
                }


            })

        })
    })
</script>
<?php $this->endBlock()?>
