<?php
$this->title=$model['title'];
$this->params = array_merge($this->params,[
    'body_class' => 'bg'
]);
?>

<?php $this->beginBlock('content')?>
<div class="header">
    <a class="back" href="javascript:history.go(-1)"></a>
</div>

<div class="main">
    <div class="text_wrap">
        <h1><?=$model['title']?></h1>
        <div class="info">
            <span>时间：<?=substr($model['show_time'],0,10)?></span>
        </div>
        <?=$model['content']?>

        <div class="article-page">
            <p>上一篇：<a href="<?\yii\helpers\Url::to(['','id'=>$model_up['id']])?>"><?=$model_up['title']?></a></p>
            <p>下一篇：<a href="<?\yii\helpers\Url::to(['','id'=>$model_down['id']])?>"><?=$model_down['title']?></a></p>
        </div>
    </div>
</div>

<?php $this->endBlock()?>

<?php $this->beginBlock('script')?>


<?php $this->endBlock()?>
