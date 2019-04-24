<?php
$this->title=$title;
?>

<?php $this->beginBlock('content')?>
<div class="header">
    <a class="back" href="javascript:history.go(-1)"></a>
    <h4></h4>
</div>

<div class="main">
    <div class="about_head">
        <h4><?=$this->title?></h4>
    </div>
    <div class="text_wrap">
        <?=$content?>
    </div>
</div>

<?php $this->endBlock()?>

<?php $this->beginBlock('script')?>


<?php $this->endBlock()?>
