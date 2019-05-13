<?php
$this->title='修改密码';
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
        <div class="part1 modify-box">
            <div class="item mui-input-row">
                <input class="phone mui-input-clear" id="phone" type="tel" name="phone" value="<?=$user_model['phone']?>" maxlength="11" disabled placeholder="请输入手机号码">
            </div>
            <div class="item">
                <div class="mui-input-row" style="margin-right:90px;"><input class="phonecode mui-input-clear" name="verify" type="tel" placeholder="请输入短信验证码"></div>
                <a href="javascript:;" class="code" onclick="$.common.sendVerify(this,3,$('#phone'))">获取验证码</a>
            </div>
            <div class="item mui-input-row">
                <input class="password mui-input-clear" type="password" name="password" placeholder="请设置新密码">
            </div>
            <div class="item mui-input-row">
                <input class="password mui-input-clear" type="password" name="re_password" placeholder="请确认新密码">
            </div>

            <div class="modify-opbtns">
                <a href="javascript:;" class="btn" id="btn_part1">确定</a>
            </div>
        </div>
    </form>

</div>

<?php $this->endBlock()?>

<?php $this->beginBlock('script')?>
<script>
    $(function(){
        $("#btn_part1").click(function(){
            layer.confirm("确定修改密码?",function(){
                var index=layer.load(3)
                $.post($("#form").attr('action'),$("#form").serialize(),function(result){
                    layer.close(index)
                    layer.msg(result.msg)
                    if(result.code==1){
                        window.location.href="<?=\yii\helpers\Url::to(['mine/index'])?>"
                    }
                })
            })

        })
    })
</script>

<?php $this->endBlock()?>
