<?php
$this->title='账户与安全';
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
        <div class="account_list">
            <ul class="account_item">
                <li class="item">
                    <a href="<?=\yii\helpers\Url::to(['mod-pwd'])?>">
                        <span class="ico icon-1"></span>
                        <div class="name">
                            <p>修改密码</p>
                            <span>建议您定期更改密码以保护账户安全</span>
                        </div>
                    </a>
                </li>
                <li class="item">
                    <a href="<?=\yii\helpers\Url::to(['mod-phone'])?>">
                        <span class="ico icon-2"></span>
                        <div class="name">
                            <p>手机号</p>
                            <span>若手机更换请尽快修改</span>
                        </div>
                        <span class="value"><?=substr_replace($user_model['phone'],'****',3,4)?></span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>


<?php $this->endBlock()?>

<?php $this->beginBlock('script')?>
<script>

</script>

<?php $this->endBlock()?>
