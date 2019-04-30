<?php
    //用于显示左侧栏目选中状态
    $this->params = [
        'crumb'          => ['用户管理','用户类型操作'],
    ];
?>
<?php $this->beginBlock('content'); ?>
<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">用户类型操作</h3>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <form class="form-horizontal" id="form">
        <input name="_csrf" type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
        <input name="id" type="hidden" value="<?= $model['id'] ?>">
        <div class="box-body">

            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">类型名</label>

                <div class="col-sm-10">
                    <input type="text" maxlength="15" class="form-control" name="name" value="<?= $model['name'] ?>" placeholder="用户名">
                </div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">比例</label>

                <div class="col-sm-10">
                    <input type="number" maxlength="15" class="form-control" name="per" value="<?= $model['per']?$model['per']:'0.00' ?>" placeholder="account">
                    <div class="help-block">
                        比例只能设置为0-1之间
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">排序</label>

                <div class="col-sm-10">
                    <input type="number" class="form-control" name="sort" value="<?= $model['sort']?$model['sort']:100 ?>" placeholder="">
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

    })
</script>
<?php $this->endBlock();?>

