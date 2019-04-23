<?php
    //用于显示左侧栏目选中状态
    $this->params = [
        'crumb'          => ['系统设置','管理员管理','角色操作'],
    ];
?>
<?php $this->beginBlock('content'); ?>
<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">角色操作</h3>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <form class="form-horizontal" id="form">
        <input name="_csrf" type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
        <input name="id" type="hidden" value="<?= $model['id'] ?>">
        <div class="box-header">
            <button type="button" class="btn btn-info  " id="submit"  onclick="$.common.formSubmit()">保存</button>
        </div>
        <div class="box-body">

            <div class="form-group">
                <label for="inputPassword3" class="col-sm-1 control-label">角色等级</label>

                <div class="col-sm-10">
                    <select name="pid" class="form-control">
                        <option value="0">一级角色</option>
                        <?php foreach($top_role as $vo) {?>
                            <option value="<?=$vo['id']?>" <?=$vo['id']==$model['pid']?'selected':''?>><?=$vo['name']?></option>
                        <?php }?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-1 control-label">名称</label>

                <div class="col-sm-10">
                    <input type="text" maxlength="25" class="form-control" name="name" value="<?= $model['name'] ?>" placeholder="角色名">
                </div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-1 control-label">排序</label>

                <div class="col-sm-10">
                    <input type="number" class="form-control" name="sort" value="<?= $model['sort']?$model['sort']:100 ?>" placeholder="">
                </div>
            </div>

            <div class="form-group">
                <label for="inputPassword3" class="col-sm-1 control-label">状态</label>

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
                <label for="inputPassword3" class="col-sm-1 control-label">权限</label>

                <div class="col-sm-10">
                    
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
<script>
    $(function(){

    })
</script>
<?php $this->endBlock();?>

