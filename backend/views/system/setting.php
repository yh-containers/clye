<?php

//用于显示左侧栏目选中状态
$this->params = [
    'crumb'          => ['系统管理','常规设置'],
];
?>
<?php $this->beginBlock('style'); ?>
<style>
    .textarea-block{position: relative;}
    .textarea-block i{right: 0;position: absolute;z-index: 999;font-size: 24px;color: red;cursor: pointer}
</style>
<?php $this->endBlock();?>
<?php $this->beginBlock('content'); ?>

<div class="row">
    <div class="col-sm-6">
        <div class="box box-info">
            <form class="form-horizontal" action="<?= \yii\helpers\Url::to(['setting-save'])?>">
                <input name="_csrf" type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
                <input name="type" type="hidden" value="normal">
                <div class="box-header with-border">
                    <h3 class="box-title">常规设置</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-info btn-sm bg-yellow save-btn"  onclick="$.common.formSubmit($(this).parents('form'),1)">保存</button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">公司地址</label>
                        <div class="col-sm-10">
                            <input type="text" maxlength="150" class="form-control" name="content[addr]" value="<?= isset($normal_content['addr'])?$normal_content['addr']:''?>" placeholder="公司地址">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">联系人</label>
                        <div class="col-sm-10">
                            <input type="text" maxlength="50" class="form-control" name="content[contacts]" value="<?= isset($normal_content['contacts'])?$normal_content['contacts']:''?>" placeholder="联系人">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">联系电话</label>
                        <div class="col-sm-10">
                            <input type="text" maxlength="50" class="form-control" name="content[tel]" value="<?= isset($normal_content['tel'])?$normal_content['tel']:''?>" placeholder="联系电话">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">qq</label>
                        <div class="col-sm-10">
                            <input type="text" maxlength="50" class="form-control" name="content[qq]" value="<?= isset($normal_content['qq'])?$normal_content['qq']:''?>" placeholder="qq">
                        </div>
                    </div>

                </div>
            </form>
        </div>

    </div>
    <div class="col-sm-6" >

    </div>


</div>

<?php $this->endBlock(); ?>
<?php $this->beginBlock('script'); ?>
<script>
    <!-- 实例化编辑器 -->
    layui.use(['upload'], function(){
        var upload = layui.upload;

        $.common.uploadFile(upload,'.upload')

    });
    $(function(){
        $("#add-line").click(function(){
            $("#problem-block").append('<div class="textarea-block">\n' +
                '                                <i class="fa fa-fw fa-close"></i>\n' +
                '                                <textarea name="content[]" class="textarea margin-bottom layui-textarea"></textarea>\n' +
                '                            </div>');
        })
        $("#problem-block").on('click','.textarea-block i',function(){
            $(this).parent().remove()
        })
    })
</script>
<?php $this->endBlock(); ?>

