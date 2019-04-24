<?php
$this->title='检索商品';
$this->params = array_merge($this->params,[
    'body_class' => 'bg'
]);

?>

<?php $this->beginBlock('content')?>
<div class="header">
    <a class="back" href="javascript:history.go(-1)"></a>
    <div class="header-container">
        <div class="search-wrap search-right clearfix">
            <a class="search" href="<?=\yii\helpers\Url::to(['goods/search'])?>" title="请输入关键字搜索">请输入关键字搜索</a>
        </div>
    </div>
</div>

<div class="main">
    <div class="sort">
        <ul class="list">
            <?php foreach($list as $vo){?>
            <li class="list-item mui-navigate-right">
                <a href="<?=\yii\helpers\Url::to(['goods/index','cid'=>$vo['id']])?>"><?=$vo['name']?></a>
            </li>
            <?php }?>
        </ul>
    </div>
</div>



<?=\wechat\widgets\Footer::widget(['current_active'=>'goods'])?>
<?php $this->endBlock()?>

<?php $this->beginBlock('script')?>
<script>


</script>

<?php $this->endBlock()?>