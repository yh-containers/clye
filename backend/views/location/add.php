<?php
    //用于显示左侧栏目选中状态
    $this->params = [
        'crumb'          => ['系统设置','地区管理','地区操作'],
    ];
?>
<?php $this->beginBlock('content'); ?>
<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">地区操作</h3>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <form class="form-horizontal" id="form">
        <input name="_csrf" type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
        <input name="id" type="hidden" value="<?= $model['id'] ?>">
        <div class="box-body">

            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">省份</label>

                <div class="col-sm-10">
                    <select class="form-control" name="pid">
                        <option value="0">请选择省份</option>
                        <?php foreach ($province as $vo){?>
                            <option value="<?=$vo['id']?>" <?=$vo['id']==$model['pid']?'selected':''?>><?=$vo['name']?></option>

                        <?php }?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">城市</label>

                <div class="col-sm-10">
                    <input type="text" maxlength="15" class="form-control" name="name" value="<?= $model['name'] ?>" placeholder="城市">
                </div>
            </div>


            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">排序</label>

                <div class="col-sm-10">
                    <input type="number" class="form-control" name="sort" value="<?= $model['sort']?$model['sort']:100 ?>" >
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

