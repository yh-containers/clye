<?php
$this->title = '首页';
?>

<?php $this->beginBlock('content')?>

<div class="header">
    <div class="header-container">
        <a class="logo" href="<?=\yii\helpers\Url::to(['index/index'])?>"><img src="<?=\Yii::$app->params['logo_file']?>" /></a>
        <div class="search-wrap clearfix">
            <a class="search" href="<?=\yii\helpers\Url::to(['goods/search'])?>" title="请输入关键字搜索">请输入关键字搜索</a>
        </div>
        <a class="icon right sort" href="<?=\yii\helpers\Url::to(['goods/cate'])?>"><span>检索</span></a>
    </div>
</div>

<div class="banner slider-wrap">
    <div class="swiper-container swiper-container-banner">
        <div class="swiper-wrapper">
            <?php foreach($images as $vo){?>
                <div class="swiper-slide">
                    <a href="javascript:;"><img src="<?=$vo['img']?>" /></a>
                </div>
            <?php }?>
        </div>
        <div class="pagination banner-pagination"></div>
    </div>
</div>

<div class="nav_list">
    <?php foreach($goods_cate as $vo){?>
        <a href="<?=\yii\helpers\Url::to(['goods/index','cid'=>$vo['id']])?>" class="item"><img src="<?=$vo['img']?>"><span><?=$vo['name']?></span></a>
    <?php }?>
</div>

<div class="container">
    <div class="container-floor">
        <div class="title_wrap clearfix">
            <img src="<?=\Yii::getAlias('@assets')?>/assets/images/title-1.png" />
        </div>
        <div class="goods_list itemList">
            <ul>
                <?php foreach($goods as $vo){?>
                <li class="good_item">
                    <a href="<?=\yii\helpers\Url::to(['goods/detail','id'=>$vo['id']])?>">
                        <div class="img">
                            <img class="lazy" src="<?=\common\models\Goods::getCoverImg($vo['img'])?>" alt="">
                        </div>
                        <div class="prolist_info">
                            <div class="name"><?=$vo['name']?></div>
                            <div class="desc"><?=$vo['intro']?></div>
                        </div>
                    </a>
                </li>
                <?php }?>
            </ul>
        </div>
    </div>
</div>

<div class="container-floor">
    <div class="title_wrap clearfix">
        <img src="<?=\Yii::getAlias('@assets')?>/assets/images/title-2.png" />
    </div>
    <div class="nav_list col-4">
        <a href="<?=\yii\helpers\Url::to(['index/company-about'])?>" class="item"><img src="<?=\Yii::getAlias('@assets')?>/assets/images/nav-11.png"><span>公司简介</span></a>
        <a href="<?=\yii\helpers\Url::to(['index/company-edu'])?>" class="item"><img src="<?=\Yii::getAlias('@assets')?>/assets/images/nav-12.png"><span>企业文化</span></a>
        <a href="<?=\yii\helpers\Url::to(['article/tip'])?>" class="item"><img src="<?=\Yii::getAlias('@assets')?>/assets/images/nav-14.png"><span>精彩瞬间</span></a>
        <a href="<?=\yii\helpers\Url::to(['article/index'])?>" class="item"><img src="<?=\Yii::getAlias('@assets')?>/assets/images/nav-15.png"><span>宣传资料</span></a>
        <a href="<?=\yii\helpers\Url::to(['article/news'])?>" class="item"><img src="<?=\Yii::getAlias('@assets')?>/assets/images/nav-17.png"><span>新闻资讯</span></a>
        <a href="<?=\yii\helpers\Url::to(['index/cert'])?>" class="item"><img src="<?=\Yii::getAlias('@assets')?>/assets/images/nav-18.png"><span>药品证书</span></a>
        <a href="<?=\yii\helpers\Url::to(['index/contact'])?>" class="item"><img src="<?=\Yii::getAlias('@assets')?>/assets/images/nav-19.png"><span>联系我们</span></a>
        <a href="<?=\yii\helpers\Url::to(['article/problem'])?>" class="item"><img src="<?=\Yii::getAlias('@assets')?>/assets/images/nav-20.png"><span>常见问题</span></a>
    </div>
</div>

<div class="suspension">
    <a href="javascript:;" id="BackToTop">
        <span class="mui-icon-extra mui-icon-extra-top"></span>
    </a>
</div>

<?=\wechat\widgets\Footer::widget(['current_active'=>'index'])?>


<?php $this->endBlock()?>

<?php $this->beginBlock('script')?>
<link rel="stylesheet" type="text/css" href="<?=\Yii::getAlias('@assets')?>/assets/css/swiper.min.css" />
<script type="text/javascript" src="<?=\Yii::getAlias('@assets')?>/assets/js/swiper.min.js"></script>
<script>
    // banner
    var bannerSwiper = new Swiper('.swiper-container-banner', {
        pagination: '.banner-pagination',
        autoplay: 5000,
        loop: true,
        spaceBetween: 0
    });
</script>
<?php $this->endBlock()?>
