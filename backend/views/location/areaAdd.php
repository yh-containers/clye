<?php
    //用于显示左侧栏目选中状态
    $this->params = [
        'crumb'          => ['系统设置','地区管理','行政地区操作'],
    ];
?>
<?php $this->beginBlock('content'); ?>
<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">行政地区</h3>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <form class="form-horizontal" id="form">
        <input name="_csrf" type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
        <input name="id" type="hidden" value="<?= $model['id'] ?>">
        <div class="box-body">

            <div class="form-group">
                <button type="button" class="btn btn-info col-sm-offset-2 " id="submit"  onclick="$.common.formSubmit()">保存</button>
            </div>


            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">行政区名</label>

                <div class="col-sm-10">
                    <input type="text" maxlength="15" class="form-control" name="name" value="<?= $model['name'] ?>" placeholder="行政区名">
                </div>
            </div>


            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">排序</label>

                <div class="col-sm-10">
                    <input type="number" class="form-control" name="sort" value="<?= $model['sort']?$model['sort']:100 ?>" >
                </div>
            </div>


            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">行政区</label>
                <div class="col-sm-10">
                    <table class="table table-bordered" id="layer-photos-demo">
                        <thead>
                        <tr>
                            <th>省份</th>
                            <th>城市</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($province as $key=>$vo){?>
                            <tr>
                                <td>
                                    <label>
                                        <input type="checkbox" name="aid[]" value="<?=$vo['id']?>" <?=$model['id']==$vo['aid']?'checked':''?> ><?=$vo['name']?>
                                    </label>
                                </td>
                                <td>
                                    <?php foreach($vo['linkChild'] as $item){?>
                                        <label>
                                            <input type="checkbox" name="aid[]" value="<?=$item['id']?>" <?=$model['id']==$item['aid']?'checked':''?> ><?=$item['name']?>
                                        </label>

                                    <?php }?>
                                </td>

                            </tr>

                        <?php }?>
                        </tbody>
                    </table>
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

