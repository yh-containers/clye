<?php
    //用于显示左侧栏目选中状态
    $this->params = [
        'crumb' => ['商品管理','商品分类操作'],
    ];
?>
<?php $this->beginBlock('content'); ?>
<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">分类操作</h3>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <form class="form-horizontal" id="form">
        <input name="_csrf" type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
        <input name="id" type="hidden" value="<?= $model['id'] ?>">
        <div class="box-body">

            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">分类等级</label>

                <div class="col-sm-10">
                    <select name="pid" class="form-control">
                        <option value="0">顶级分类</option>
                        <?php foreach($top_cate as $vo) {?>
                            <option value="<?=$vo['id']?>" <?=$vo['id']==$model['pid']?'selected':''?>><?=$vo['name']?></option>
                        <?php }?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">分类名</label>

                <div class="col-sm-10">
                    <input type="text" maxlength="20" class="form-control" name="name" value="<?=$model['name']?>" placeholder="">
                </div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">封面图</label>

                <div class="col-sm-10">
                    <input type="hidden" name="img" value="<?=$model['img']?>"/>
                    <button class="layui-btn" id="test1" type="button" lay-data="{ url: '<?= \yii\helpers\Url::to(['upload/upload','type'=>'goodsCate'])?>',data:{_csrf:'<?= Yii::$app->request->csrfToken ?>'}}" >上传文件</button>
                    <img src="<?= $model['img'] ?>" alt="封面图" class="radius" width="80" height="80">
                </div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">排序</label>

                <div class="col-sm-10">
                    <input type="number" class="form-control" name="sort" value="<?= $model['sort']?$model['sort']:100 ?>" placeholder="">
                </div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">特殊分类</label>
                <div class="col-sm-10">
                    <label>
                        <input type="radio" name="is_special"  value="1" <?= $model['is_special']==1?'checked':'' ?>>
                        是
                    </label>
                    <label>
                        <input type="radio" name="is_special" value="0" <?= empty($model['is_special'])?'checked':'' ?>>
                        否
                    </label>
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

