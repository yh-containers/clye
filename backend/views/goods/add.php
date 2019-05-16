<?php
    //用于显示左侧栏目选中状态
    $this->params = [
        'crumb'          => ['商品管理','商品操作'],
    ];
?>

<?php $this->beginBlock('style'); ?>
<style>
    #goods-img .item{position: relative; display: inline-block}
    #goods-img .item i{right: 0px;position: absolute;z-index: 999;font-size: 24px;color: red;cursor: pointer}
    #add-spu-block input{width: 160px;display: inline-block}
    #add-spu-block .fa-close{color: red}
</style>
<?php $this->endBlock();?>


<?php $this->beginBlock('content'); ?>
<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">商品操作</h3>
        <button type="button" class="btn btn-info marign" id="submit"  onclick="$.common.formSubmit()">保存</button>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <form class="form-horizontal" id="form">
        <input name="_csrf" type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
        <input name="id" type="hidden" value="<?=$model['id']?>">
        <div class="box-body">
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">分类</label>

                <div class="col-sm-10">
                    <select name="cid" class="form-control">
                        <option value="">请选择分类</option>
                        <?php foreach($cate as $vo) {?>
                            <option value="<?=$vo['id']?>" <?=$vo['id']==$model['cid']?'selected':''?>><?=$vo['name']?></option>
                            <?php foreach($vo['linkChild'] as $item) {?>
                                <option value="<?=$item['id']?>" <?=$item['id']==$model['cid']?'selected':''?>> &nbsp;&nbsp;&nbsp;<?=$item['name']?></option>
                            <?php }?>
                        <?php }?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">商品名称</label>

                <div class="col-sm-10">
                    <input type="text" maxlength="255" class="form-control" name="name" value="<?=$model['name']?>" placeholder="">
                </div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">批号</label>

                <div class="col-sm-10">
                    <input type="text" maxlength="255" class="form-control" name="p_no" value="<?=$model['p_no']?>" placeholder="">
                </div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">商品图片</label>

                <div class="col-sm-10 margin-bottom">
                    <button class="layui-btn" id="test1" type="button" lay-data="{ url: '<?= \yii\helpers\Url::to(['upload/upload','type'=>'goods'])?>',data:{_csrf:'<?= Yii::$app->request->csrfToken ?>'}}" >上传文件</button>
                </div>
                <div class="col-sm-10 col-sm-offset-2" id="goods-img">
                    <?php $img = $model['img']?explode(',',$model['img']):[]; foreach ($img as $vo){?>
                        <div class="item">
                            <i class="fa fa-fw fa-close"></i>
                            <img src="<?=$vo?>" width="120" height="120"/>
                            <input type="hidden" name="img[]" value="<?=$vo?>"/>
                        </div>
                    <?php }?>
                </div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">spu</label>

                <div class="col-sm-10 margin-bottom">
                    <button class="layui-btn" id="add-spu" type="button">增加一项</button>
                </div>
                <div class="col-sm-10 col-sm-offset-2" id="add-spu-block">
                    <?php if(!empty($model)) foreach($model['linkSpu'] as $spu){?>
                       <div class="item">
                           <i class="fa fa-fw fa-close"></i>

                           <input type="text" name="spu_name[]" value="<?=$spu['name']?>" class="form-control"/>:
                           <input  type="text"  name="spu_val[]" value="<?=$spu['val']?>" class="form-control">
                           <input  type="hidden"  name="spu_index[]" value="<?=$spu['id']?>" class="form-control">
                       </div>
                    <?php }?>
                </div>
            </div>

            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">价格<span style="color: red">(<?=\common\models\User::getUserTypePoint(null,'name',(int)\Yii::$app->controller->is_super_manager)?>)</span></label>

                <div class="col-sm-10">
                    <input type="number"  class="form-control" name="price" value="<?=empty($model)? 0.00:$model->getUserPrice(\Yii::$app->controller->user_model,\Yii::$app->controller->is_super_manager)?>" placeholder="">
                </div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">税费</label>

                <div class="col-sm-10">
                    <input type="number"  class="form-control" name="taxation_money" value="<?=$model['taxation_money']?$model['taxation_money']:0.00?>" placeholder="">
                </div>
            </div>

            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">排序</label>

                <div class="col-sm-10">
                    <input type="number"  class="form-control" name="sort" value="<?=$model['sort']?$model['sort']:100?>" placeholder="">
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
                <label for="inputPassword3" class="col-sm-2 control-label"></label>
                <div class="col-sm-10">
                    <label>
                        <input type="checkbox" name="is_hot" value="1" <?=$model['is_hot']==1?'checked':''?> >热门
                    </label>
                </div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">简介</label>

                <div class="col-sm-10">
                    <textarea name="intro" class="form-control" id=""  rows="5"><?=$model['intro']?></textarea>
                </div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">详细内容</label>
                <div class="col-sm-10">
                    <!-- 加载编辑器的容器 -->
                    <script id="container" name="content" type="text/plain"><?=$model['content']?></script>
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
    $(function(){
        layui.use(['upload'], function(){
            var upload = layui.upload;

            $.common.uploadFile(upload,'#test1',(res,item)=>{
                $("#goods-img").append('<div class="item">\n' +
                    '<i class="fa fa-fw fa-close"></i>\n' +
                    '<img src="'+res.path+'" width="120" height="120"/>\n' +
                    '<input type="hidden" name="img[]" value="'+res.path+'"/>'+
                    '</div>')
            })

        });

        $("#add-spu").click(function(){
            var html = '<div class="item">\n' +
                '                       <i class="fa fa-fw fa-close"></i>\n' +
                '                       <input type="text" name="spu_name[]" class="form-control"/>:<input  type="text"  name="spu_val[]" class="form-control">\n' +
                '                   </div>'
            $("#add-spu-block").append(html);
        })
        $("#add-spu-block").on('click','.fa-close',function(){
            $(this).parent().remove()
        })


        $("#goods-img").on('click','.item i',function(){
            $(this).parent().remove()
        })
    })
</script>
<?php $this->endBlock();?>

