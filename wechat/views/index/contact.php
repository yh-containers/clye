<?php
$this->title='联系我们';
?>

<?php $this->beginBlock('content')?>
<div class="header">
    <a class="back" href="javascript:history.go(-1)"></a>
    <h4></h4>
</div>

<div class="main">
    <div class="header">
        <a class="back" href="javascript:history.go(-1)"></a>
    </div>

    <div class="main">
        <div class="about_head">
            <h4>联系我们</h4>
        </div>
        <div class="text_wrap">
            <p>公司地址：<?=!empty($content['addr'])?$content['addr']:''?></p>

            <p>联系人：<?=!empty($content['contacts'])?$content['contacts']:''?></p>

            <p>联系电话：<?=!empty($content['tel'])?$content['tel']:''?></p>

            <p>QQ：<?=!empty($content['qq'])?$content['qq']:''?></p>
            <p><br /></p>

            <p style="text-align:right"><img src="<?=\Yii::getAlias('@assets')?>/assets/images/contact_img.jpg" style="max-width:300px;width: 240px;display: inline-block;" /></p>
        </div>
    </div>
</div>

<?php $this->endBlock()?>

<?php $this->beginBlock('script')?>


<?php $this->endBlock()?>
