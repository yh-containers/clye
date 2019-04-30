<?php
$this->title='合同管理';
$this->params = array_merge($this->params,[
]);

?>
<?php $this->beginBlock('content')?>

<?=  $this->render('/mine/contractDetailTemp',['money'=>$model['money'],'contract_model'=>$model]) ?>

<?php $this->endBlock()?>
