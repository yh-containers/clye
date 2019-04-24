<?php
$this->title='用户注册';
?>

<?php $this->beginBlock('style')?>
<style>
    body {background-color: #fff;}
</style>
<?php $this->endBlock()?>
<?php $this->beginBlock('content')?>
<div class="login_body">
    <div class="login_logo">
        <img src="<?=\Yii::$app->params['logo_file']?>" />
    </div>
    <form id="pwd_login">
        <input type="hidden" name="<?=\Yii::$app->request->csrfParam?>" value="<?= \Yii::$app->request->csrfToken ?>"/>
    <div class="login_wrap" id="namelogin">
        <div class="item">
            <div class="label">手机号</div>
            <div class="mui-input-row">
                <input class="phone mui-input-clear" type="tel" name="phone" maxlength="11"/>
            </div>
            <div class="icon icon-user"></div>
        </div>
        <div class="item">
            <div class="label">密　码</div>
            <div class="mui-input-row">
                <input class="password mui-input-clear" type="password" name="password" maxlength="20" />
            </div>
            <div class="icon icon-pass"></div>
        </div>
    </div>
    </form>

    <form id="verify_login">
        <input type="hidden" name="<?=\Yii::$app->request->csrfParam?>" value="<?= \Yii::$app->request->csrfToken ?>"/>
        <input type="hidden" name="type" value="1"/>
    <div class="login_wrap" id="codelogin">
        <div class="item">
            <div class="label">手机号</div>
            <div class="mui-input-row">
                <input class="phone mui-input-clear" type="tel" name="phone" />
            </div>
            <div class="icon icon-user"></div>
        </div>
        <div class="item">
            <div class="mui-input-row" style="margin-left:0px;"><input class="mui-input-clear" type="tel" name="verify" placeholder="请输入短信验证码" /></div>
            <a href="javascript:;" class="code">获取验证码</a>
        </div>
    </div>
    </form>
    <div class="login_footer">
        <div class="info clearfix">
            <a href="javascript:;" class="fl" onclick="changeState(this)" id data-change="1">短信验证码登录</a>
            <a href="<?=\yii\helpers\Url::to(['forget'])?>" class="findpwd fr">忘记密码？</a>
        </div>
        <a href="javascript:;" id="loginBtn" class="btn">登 录</a>
    </div>

    <div class="login_other">
        <a href="javascript:;">
            <span>其他登录方式</span>
            <i class="icon"></i>
        </a>
        <div class="reg">
            <a href="<?=\yii\helpers\Url::to(['reg'])?>" >还没有账号？<font>注册</font></a>
        </div>
    </div>
</div>

<?php $this->endBlock()?>

<?php $this->beginBlock('script')?>
<script>
    var state=0;
    function changeState(e) {
        state = $(e).data("change");
        if (state == 1) {
            $(e).html("账号密码登录");
            $(e).data("change", 0);
            $("#namelogin").css("display", "none");
            $("#codelogin").css("display", "block");
            $(".findpwd").css("display", "none");
        } else {
            $(e).html("短信验证码登录");
            $(e).data("change", 1);
            $("#namelogin").css("display", "block");
            $("#codelogin").css("display", "none");
            $(".findpwd").css("display", "block");
        }
    };

    $(function(){
        $("#loginBtn").click(function(){
            var index = layer.load(3)
            var form=$("#pwd_login");
            if(state){
                form=$("#verify_login");
            }
            $.post(form.attr('action'),form.serialize(),function(result){
                layer.close(index)
                console.log(result)
                layer.msg(result.msg)
                if(result.hasOwnProperty('url')){
                    window.location.href=result.url
                }
            })
        })
    })



</script>

<?php $this->endBlock()?>
