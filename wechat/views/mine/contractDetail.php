<?php
$this->title='合同管理';
$this->params = array_merge($this->params,[
]);

?>
<?php $this->beginBlock('content')?>
<div class="header">
    <a class="back" href="javascript:history.go(-1)"></a>
    <h4><?=$this->title?></h4>
</div>
<div class="main">
    <form id="form">
        <input type="hidden" name="<?=\Yii::$app->request->csrfParam?>" value="<?= \Yii::$app->request->csrfToken ?>"/>
        <input type="hidden" name="id" value="<?=$model['id']?>" >
        <div class="contract">
            <div class="contract_table">
                <div class="item">
                    <div class="label">标题</div>
                    <div class="con"><?=$model['name']?></div>
                </div>
                <div class="item">
                    <div class="label">合同编号</div>
                    <div class="con"><?=$model['no']?></div>
                </div>
                <div class="item">
                    <div class="label">创建时间</div>
                    <div class="con"><?=$model['create_time']?></div>
                </div>
                <div class="item">
                    <div class="label">相关订单</div>
                    <div class="con"><?=$model['create_time']?></div>
                </div>
                <div class="item">
                    <div class="label">合同内容</div>
                    <div class="con"><a href="<?=\yii\helpers\Url::to(['mine/contract-content','id'=>$model['id']])?>">查看合同</a></div>
                </div>
                <div class="item">
                    <div class="label">合同金额</div>
                    <div class="con"><font>¥<?=$model['money']?></font></div>
                </div>
                <div class="item">
                    <div class="label">合同附件</div>
                    <div class="con">
                        <a href="javascript:;" class="upload">上传附件</a>
                        <a href="javascript:;" class="view" <?=empty($model['file'])?'':'style="display:block"'?>>查看附件</a>
                        <div id="preview">
                            <?php if($model['file']){?>
                            <img src="<?=$model['file']?>"><input type="hidden" name="file" value="<?=$model['file']?>"/>
                            <?php }?>
                        </div>
                    </div>
                </div>
                <div class="item">
                    <div class="label">有效期</div>
                    <div class="con"><font><?=$model['start_time']?date('Y-m-d',$model['start_time']):''?></font> 至 <font><?=$model['end_time']?date('Y-m-d',$model['end_time']):''?></font></div>
                </div>
            </div>
            <a href="javascript:;" class="btn" id="submit">保存</a>
        </div>
    </form>
</div>
<?php $this->endBlock()?>
<?php $this->beginBlock('script')?>
<script>
    $(function(){
        var upload_url = '<?=\yii\helpers\Url::to(['upload/upload','type'=>'contract','user_id'=>$model['uid']])?>'
        var csrf_key = '<?=\Yii::$app->request->csrfParam?>'
        var csrf_token = '<?=\Yii::$app->request->csrfToken?>'
        var upoad_data = {}
        upoad_data[csrf_key]=csrf_token
        layui.use('upload', function(){
            var upload = layui.upload;

            //执行实例
            var uploadInst = upload.render({
                elem: '.upload' //绑定元素
                ,url: upload_url //上传接口
                ,data:upoad_data
                ,before: function(obj){ //obj参数包含的信息，跟 choose回调完全一致，可参见上文。
                    layer.load(); //上传loading
                }
                ,done: function(res){
                    var item = this.item;
                    $(item).find('img').attr('src',res.path)
                    layer.closeAll('loading'); //关闭loading
                    if(res.code==1){
                        $("a.view").css("display","block");
                        $("#preview").html('<img src="'+res.path+'"><input type="hidden" name="file" value="'+res.path+'"/>');
                    }else{
                        layer.msg(res.msg)
                    }
                }
                ,error: function(){
                    layer.closeAll('loading'); //关闭loading
                    layer.msg('上传异常');
                }
            });
        });

        $("a.view").click(function(){
            $("#preview").addClass("fixed");
        });
        $("#preview").click(function(){
            $("#preview").removeClass("fixed");
        });
        $("#submit").click(function(){
            var index=layui.layer.load(3)
            $.post($("#form").attr('action'),$("#form").serialize(),function(result){
                layer.close(index)
                layer.msg(result.msg)
                if(result.code==1){
                    setTimeout(function(){window.history.go(-1)},1000)
                }
            })
        })
    })

</script>
<?php $this->endBlock()?>
