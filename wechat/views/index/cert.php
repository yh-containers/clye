<?php
$this->title='药品证书';
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
            <h4><?=$this->title?></h4>
        </div>
        <div class="text_wrap">
            <ul class="honor" id="layer-photos-demo">
                <?php foreach($list as $vo) {?>
                <li>
                    <a class="fancybox" rel="gallery" href="javascript:;">
                        <img src="<?=$vo['img']?>" alt="<?=$vo['title']?>" />
                        <h5><?=$vo['title']?></h5>
                    </a>
                </li>
                <?php }?>
            </ul>
        </div>
    </div>
</div>

<?php $this->endBlock()?>

<?php $this->beginBlock('script')?>


<script>

    $(function(){
        layui.use(['layer'], function(){
            var layer = layui.layer;
            layer.photos({
                photos: '#layer-photos-demo'
                ,anim: 5 //0-6的选择，指定弹出图片动画类型，默认随机（请注意，3.0之前的版本用shift参数）
            });
        });
    })
</script>
<?php $this->endBlock()?>
