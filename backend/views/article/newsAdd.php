<?php
    //用于显示左侧栏目选中状态
    $this->params = [
        'crumb'          => ['文章管理','帮助中心','新增/编辑操作'],
    ];
?>
<?php $this->beginBlock('content'); ?>
<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title"><?=$name?>--操作</h3>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <form class="form-horizontal" id="form">
        <input name="_csrf" type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
        <input name="id" type="hidden" value="<?= $model['id'] ?>">
        <input name="type" type="hidden" value="<?= $type ?>">
        <div class="box-body">

            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">标题</label>

                <div class="col-sm-10">
                    <input type="text" maxlength="150" class="form-control" name="title" value="<?= $model['title'] ?>" placeholder="标题">
                </div>
            </div>

            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">封面图</label>

                <div class="col-sm-10">
                    <input type="hidden" name="img" value="<?=$model['img']?>"/>
                    <button class="layui-btn" id="test1" type="button" lay-data="{ url: '<?= \yii\helpers\Url::to(['upload/upload','type'=>'article'])?>',data:{_csrf:'<?= Yii::$app->request->csrfToken ?>'}}" >上传文件</button>
                    <img src="<?= $model['img'] ?>" alt="项目图片" class="radius" width="80" height="80">
                </div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">作者</label>

                <div class="col-sm-10">
                    <input type="text" maxlength="50" class="form-control" name="author" value="<?= $model['author'] ?>" placeholder="作者">
                </div>
            </div>

            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">发布时间</label>

                <div class="col-sm-10">
                    <input type="text" maxlength="50" class="form-control layui-time" name="show_time" value="<?= $model['show_time']?$model['show_time']:date('Y-m-d H:i:s') ?>" placeholder="作者">
                </div>
            </div>

            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">简介</label>

                <div class="col-sm-10">
                    <textarea class="form-control" name="intro" placeholder="简介"><?=$model['intro']?></textarea>
                </div>
            </div>

            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">排序</label>

                <div class="col-sm-10">
                    <input type="number" class="form-control" name="sort" value="<?= $model['sort']?$model['sort']:100 ?>">
                </div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">状态</label>

                <div class="col-sm-10">
                    <div class="radio">
                        <label>
                            <input type="radio" name="status"  value="1" <?= $model['status']!=2?'checked':'' ?>>
                            正常
                        </label>
                        <label>
                            <input type="radio" name="status" value="2" <?= $model['status']==2?'checked':'' ?>>
                            关闭
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">内容</label>

                <div class="col-sm-10">
                    <!-- 加载编辑器的容器 -->
                    <script id="container" name="content" type="text/plain"><?=$model['content']?></script>
                </div>
            </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <button type="button" class="btn btn-info col-sm-offset-2 " id="submit"  onclick="$.common.formSubmit()">保存</button>
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
    $(function(){
        layui.use(['laydate','upload'], function(){
            var laydate = layui.laydate;
            var upload = layui.upload;

            $.common.uploadFile(upload,'#test1')
            //执行一个laydate实例
            laydate.render({
                elem: '.layui-time' //指定元素
                ,type: 'datetime' //类型
                ,value: new Date() //参数即为：2018-08-20 20:08:08 的时间戳
                ,format:'yyyy-MM-dd HH:mm:ss'
            });
        });
    })
</script>
<?php $this->endBlock();?>

