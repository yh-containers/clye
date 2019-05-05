<?php
$this->title='基本资料';
$this->params = array_merge($this->params,[
]);

?>

<?php $this->beginBlock('content')?>
<div class="header">
    <a class="back" href="javascript:history.go(-1)"></a>
    <h4><?=$this->title?></h4>
</div>

<div class="main">
    <div class="user_con infor">
        <div class="infor_avatar">
            <div id="changeAvatar" class="upload">
                <label>修改头像</label>
                <img id="preview" src="<?=$user_model['face']?>">
            </div>
        </div>

        <div class="infor_list">
            <form id="form">
                <input type="hidden" name="<?=\Yii::$app->request->csrfParam?>" value="<?= \Yii::$app->request->csrfToken ?>"/>
            <ul>
                <li>
                    <label>昵称</label>
                    <div class="name">
                        <input type="text" name="username" value="<?=$user_model['username']?>" placeholder="请输入您的昵称" autocomplete="off">
                    </div>
                </li>
                <li>
                    <label>性別</label>
                    <div class="con">
                        <select name="sex">
                            <option value="2" <?=$user_model['sex']==2?'selected':''?> >女性</option>
                            <option value="1" <?=$user_model['sex']==1?'selected':''?> >男性</option>
                            <option value="3" <?=$user_model['sex']==3?'selected':''?> >保密</option>
                        </select>
                    </div>
                </li>
                <li>
                    <label>所属地区</label>
                    <div class="con">
                        <select name="area_id">
                            <option value="请选择">请选择</option>
                            <?php foreach($area as $vo){?>
                                <option value="<?=$vo['id']?>" <?=$user_model['area_id']==$vo['id']?'selected':''?>><?=$vo['name']?></option>
                            <?php }?>
                        </select>
                    </div>
                </li>
                <li>
                    <label>公司名称</label>
                    <div class="name">
                        <input type="text" name="company_name" value="<?=$user_model['company_name']?>" placeholder="请输入公司名称" autocomplete="off">
                    </div>
                </li>
                <li>
                    <label>联系人</label>
                    <div class="name">
                        <input type="text" name="contacts" value="<?=$user_model['contacts']?>" placeholder="请输入联系人" autocomplete="off">
                    </div>
                </li>
                <li>
                    <label>手机号</label>
                    <div class="name"><?=$user_model['phone']?></div>
                </li>
                <li>
                    <label>邮箱</label>
                    <div class="name">
                        <input type="text" name="email" value="<?=$user_model['email']?>" placeholder="请输入邮箱地址" autocomplete="off">
                    </div>
                </li>
            </ul>
            </form>
        </div>

        <a class="btn" href="javascript:;" id="submit">保存</a>
    </div>
</div>

<?php $this->endBlock()?>

<?php $this->beginBlock('script')?>
<script>
$(function(){
    var upload_url = '<?=\yii\helpers\Url::to(['mine/mod-face','type'=>'header'])?>'
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
                layer.msg(res.msg)
            }
            ,error: function(){
                layer.closeAll('loading'); //关闭loading
                layer.msg('上传异常');
            }
        });
    });


    $("#submit").click(function(){
        var index = layer.load(3)
        $.post($("#form").attr('action'),$("#form").serialize(),function(result){
            layer.close(index)
            layer.msg(result.msg)
            if(result.code==1){
                window.history.back()
            }
        })
    })
})
</script>

<?php $this->endBlock()?>
