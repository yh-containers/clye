<?php
$this->title='修改手机号码';
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
                <input class="phone mui-input-clear" id="phone" type="tel" value="<?=$user_model['phone']?>" disabled >
            </div>
            <div class="item">
                <div class="mui-input-row" style="margin-right:90px;"><input class="phonecode mui-input-clear" type="tel" name="verify" placeholder="请输入短信验证码"></div>
                <a href="javascript:;" class="code"   onclick="$.common.sendVerify(this,4,$('#phone'))">获取验证码</a>
            </div>


            <div class="modify-opbtns">
                <a href="javascript:;" class="btn" id="btn_part1">下一步</a>
            </div>
        </div>
        <div class="part2 modify-box">
            <div class="item mui-input-row">
                <input class="phone mui-input-clear" type="tel" maxlength="11" id="mod-phone" name="new_phone" value="" placeholder="请输入新绑定的手机号" >
            </div>
            <div class="item">
                <div class="mui-input-row" style="margin-right:90px;"><input class="phonecode mui-input-clear" name="new_verify" type="tel" placeholder="请输入短信验证码"></div>
                <a href="javascript:;" class="code" onclick="$.common.sendVerify(this,5,$('#mod-phone'))">获取验证码</a>
            </div>


            <div class="modify-opbtns">
                <a href="javascript:;" class="btn" id="btn_part2">确定</a>
            </div>
        </div>
    </form>

</div>

<?php $this->endBlock()?>

<?php $this->beginBlock('script')?>
<script>
    $(function(){
        //第一页的确定按钮
        $("#btn_part1").click(function(){
            $(".part1").hide();
            $(".part2").show();
            $(".step li").eq(1).addClass("on");
        });
        //第二页的确定按钮
        $("#btn_part2").click(function(){
            layer.confirm("确定更换手机号码?",function(){
                var index=layer.load(3)
                $.post($("#form").attr('action'),$("#form").serialize(),function(result){
                    layer.close(index)
                    layer.msg(result.msg)
                    if(result.code==1){
                        window.location.href="<?=\yii\helpers\Url::to(['mine/index'])?>"
                    }
                })
            })
        });
    });
</script>

<?php $this->endBlock()?>
