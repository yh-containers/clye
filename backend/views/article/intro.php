<?php
    //用于显示左侧栏目选中状态
    $this->params = [
        'crumb'          => ['文章管理','公司简介'],
    ];
?>
<?php $this->beginBlock('content'); ?>
<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title"><?=$name?></h3>
        <button type="button" class="btn btn-info marign" id="submit"  onclick="$.common.formSubmit(false,1)">保存</button>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <form action="<?=\yii\helpers\Url::to(['system/setting-save'])?>" class="form-horizontal" id="form">
        <input name="_csrf" type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
        <input name="type" type="hidden" value="<?=$setting_type?>">
        <div class="box-body">

            <div class="form-group">

                <div class="col-sm-12">
                    <!-- 加载编辑器的容器 -->
                    <script id="container" name="content" type="text/plain"><?=$content?></script>
                </div>
            </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">

        </div>
        <!-- /.box-footer -->
    </form>
</div>


<?php $this->endBlock(); ?>

<?php $this->beginBlock('script');?>
<!-- 配置文件 -->
<script type="text/javascript" src="/admin/assets/ueditor1_4_3_3/ueditor.config.js"></script>
<!-- 编辑器源码文件 -->
<script type="text/javascript" src="/admin/assets/ueditor1_4_3_3/ueditor.all.js"></script>

<script>
    var ue = UE.getEditor('container',{
        toolbars: [
            ['fullscreen', 'source', 'undo', 'redo','inserttable', 'deletetable', 'insertparagraphbeforetable', 'insertrow', 'deleterow', 'insertcol', 'deletecol', 'mergecells', 'mergeright', 'mergedown', 'splittocells', 'splittorows', 'splittocols', 'charts','|','simpleupload'],
            ['lineheight','|','customstyle', 'paragraph', 'fontfamily', 'fontsize', '|','directionalityltr', 'directionalityrtl', 'indent', '|','justifyleft', 'justifycenter', 'justifyright', 'justifyjustify'],
            ['bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'superscript', 'subscript', 'removeformat', 'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|', 'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist', 'selectall', 'cleardoc']
        ]
    });

</script>
<?php $this->endBlock();?>

