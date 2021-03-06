<?php
    //用于显示左侧栏目选中状态
    $this->params = [
        'crumb'          => ['文章管理','药品证书','新增/编辑操作'],
    ];
?>
<?php $this->beginBlock('content'); ?>
<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">药品证书操作</h3>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <form class="form-horizontal" id="form">
        <input name="_csrf" type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
        <input name="id" type="hidden" value="<?= $model['id'] ?>">
        <div class="box-body">

            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">标题</label>

                <div class="col-sm-10">
                    <input type="text" maxlength="150" class="form-control" name="title" value="<?= $model['title'] ?>" placeholder="标题">
                </div>
            </div>

            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">证书图</label>

                <div class="col-sm-10">
                    <input type="hidden" name="img" value="<?=$model['img']?>"/>
                    <button class="layui-btn" id="test1" type="button" lay-data="{ url: '<?= \yii\helpers\Url::to(['upload/upload','type'=>'cert'])?>',data:{_csrf:'<?= Yii::$app->request->csrfToken ?>'}}" >上传文件</button>
                    <img src="<?= $model['img'] ?>" alt="项目图片" class="radius" width="80" height="80">
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
<script>
    $(function(){
        layui.use(['upload'], function(){
            var upload = layui.upload;

            $.common.uploadFile(upload,'#test1')

        });
    })
</script>
<?php $this->endBlock();?>

