<?php
$this->title='用户注册';
?>

<?php $this->beginBlock('content')?>

<div class="header">
    <a class="back" href="javascript:history.go(-1)"></a>
    <h4><?=$this->title?></h4>
</div>
<div class="main">
    <div class="login_body">
        <div class="login_logo">
            <img src="<?=\Yii::$app->params['logo_file']?>" />
        </div>
        <form id="form">
            <input type="hidden" name="<?=\Yii::$app->request->csrfParam?>" value="<?= \Yii::$app->request->csrfToken ?>"/>
            <div class="login_wrap">
                <div class="item">
                    <div class="label">手机号</div>
                    <div class="mui-input-row">
                        <input class="phone mui-input-clear" type="tel" id="phone" name="phone" value=""/>
                    </div>
                    <div class="icon icon-user"></div>
                </div>
                <div class="item">
                    <div class="mui-input-row" style="margin-left:0px;"><input class="mui-input-clear"  type="number" name="verify" value="" placeholder="请输入短信验证码" /></div>
                    <a href="javascript:;" class="code" onclick="$.common.sendVerify(this,0,$('#phone'))" >获取验证码</a>
                </div>
                <div class="item">
                    <div class="label">设置密码</div>
                    <div class="mui-input-row">
                        <input class="password mui-input-clear" type="password" name="password" value="" />
                    </div>
                    <div class="icon icon-pass"></div>
                </div>
                <div class="item">
                    <div class="label">确认密码</div>
                    <div class="mui-input-row">
                        <input class="password mui-input-clear" type="password" name="qr_password" value="" />
                    </div>
                    <div class="icon icon-pass"></div>
                </div>
            </div>
            <div class="login_footer">
                <a href="javascript:;" id="submit" class="btn">立即注册</a>
            </div>
        </form>
    </div>
</div>

<?php $this->endBlock()?>

<?php $this->beginBlock('script')?>
<script>
    $(function(){
        $("#submit").click(function(){
            $.post($("#form").attr('action'),$("#form").serialize(),function(result){
                layer.msg(result.msg)
                if(result.code==1){
                    setTimeout(function(){
                        window.location.href='<?=\yii\helpers\Url::to(["index/login"])?>'
                    },1000)
                }
            })
        })
    })
</script>

<?php $this->endBlock()?>
