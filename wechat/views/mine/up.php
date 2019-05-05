<?php
$this->title='申请升级';
$this->params = array_merge($this->params,[
]);

?>

<?php $this->beginBlock('content')?>
<div class="header">
    <a class="back" href="javascript:history.go(-1)"></a>
    <h4><?=$this->title?></h4>
</div>
<div class="main">
    <div class="user-info">
        <div class="user-bg">
            <div class="user-avatar">
                <div class="avatar-media"><img src="<?=$user_model['face']?>" /></div>
                <div class="name"><?=$user_model['username']?></div>
            </div>
        </div>
        <div class="user-desc">
            <div class="user-vip">
                <div class="grade">
                    <?=$user_type['name']?>
                </div>
                <a href="<?=\yii\helpers\Url::to(['info'])?>" class="apply">修改资料</a>
            </div>
            <div class="user-list">
                <p>·公司名称：<?=$user_model['company_name']?></p>
                <p>·联系人：<?=$user_model['contacts']?></p>
                <p>·手机号：<?=$user_model['phone']?></p>
                <p>·邮箱：<?=$user_model['email']?></p>
                <p>·所属区域：<?=$area_info['name']?></p>

                <div class="btn">
                    <font>*下一等级将享受更多优惠</font>
                    <a href="<?=\yii\helpers\Url::to(['up-page'])?>">申请升级</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->endBlock()?>

<?php $this->beginBlock('script')?>
<script>
</script>

<?php $this->endBlock()?>
