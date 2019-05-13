<?php
$this->title='设置';
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
        <div class="set_list">
            <div id="changeAvatar">
                <a href="personal.html">
                    <div id="photo">
                        <img src="<?=$user_model['face']?>" >
                    </div>
                    <div class="txt">
                        <p><?=$user_model['username']?></p>
                        <span><?=substr_replace($user_model['phone'],'****',3,4)?></span>
                    </div>
                </a>
            </div>
            <ul>
                <li>
                    <a href="<?=\yii\helpers\Url::to(['address'])?>">收货地址管理</a>
                </li>
            </ul>
            <ul class="margin-t-10">
                <li>
                    <a href="<?=\yii\helpers\Url::to(['account'])?>">账户安全</a>
                </li>
            </ul>
        </div>

        <a class="fix_btn signout" href="<?=\yii\helpers\Url::to(['index/logout'])?>" >退出登录</a>
    </div>
</div>

<?php $this->endBlock()?>

<?php $this->beginBlock('script')?>
<script>

</script>

<?php $this->endBlock()?>
