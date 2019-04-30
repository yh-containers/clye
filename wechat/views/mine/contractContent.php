<?php
$this->title='合同管理';
$this->params = array_merge($this->params,[
    'body_class' => 'bg'
]);

?>
<?php $this->beginBlock('content')?>
<div class="header">
    <a class="back" href="javascript:history.go(-1)"></a>
    <h4><?=$this->title?></h4>
</div>

<div class="main">
<?=$content?>
</div>
<?php $this->endBlock()?>
