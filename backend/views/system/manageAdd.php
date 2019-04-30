<?php
    //用于显示左侧栏目选中状态
    $this->params = [
        'crumb'          => ['系统设置','管理员管理','管理员操作'],
    ];
?>
<?php $this->beginBlock('content'); ?>
<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">管理员操作</h3>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <form class="form-horizontal" id="form">
        <input name="_csrf" type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
        <input name="id" type="hidden" value="<?= $model['id'] ?>">
        <div class="box-body">

            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">用户名</label>

                <div class="col-sm-10">
                    <input type="text" maxlength="15" class="form-control" name="name" value="<?= $model['name'] ?>" placeholder="用户名">
                </div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">帐号</label>

                <div class="col-sm-10">
                    <input type="text" maxlength="15" class="form-control" name="account" value="<?= $model['account'] ?>" placeholder="account">
                </div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">管理行政区</label>

                <div class="col-sm-10">
                    <select class="form-control" name="area_id">
                        <?php foreach ($area as $vo){?>
                            <option value="<?=$vo['id']?>" <?=$vo['id']==$model['area_id']?'selected':''?>><?=$vo['name']?></option>
                        <?php }?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">角色</label>

                <div class="col-sm-10">
                    <select class="form-control" name="rid">
                        <?php foreach ($roles as $vo){?>
                        <option value="<?=$vo['id']?>" <?=$vo['id']==$model['rid']?'selected':''?>><?=$vo['name']?></option>
                            <?php foreach ($vo['linkRoles'] as $item){?>
                                <option value="<?=$item['id']?>"  <?=$item['id']==$model['rid']?'selected':''?> >&nbsp;&nbsp;&nbsp;<?=$item['name']?></option>
                            <?php }?>
                        <?php }?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">密码</label>

                <div class="col-sm-10">
                    <input type="password" class="form-control" name="password" value=""  placeholder="****" maxlength="30">
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

    })
</script>
<?php $this->endBlock();?>

