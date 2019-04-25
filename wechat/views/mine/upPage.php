<?php
$this->title='申请升级';
$this->params = array_merge($this->params,[
]);

?>

<?php $this->beginBlock('content')?>
<div class="data-tips">
    <img src="<?=\Yii::getAlias('@assets')?>/assets/images/data-tips.png" alt="" />
    <p>您的升级申请已提交<br />请耐心等待处理</p>
    <div class="btn"><a href="<?=\yii\helpers\Url::to(['index'])?>">返回</a></div>
</div>
<?php $this->endBlock()?>

<?php $this->beginBlock('script')?>
<script>
</script>

<?php $this->endBlock()?>
