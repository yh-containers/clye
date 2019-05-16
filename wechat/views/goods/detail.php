<?php
$this->title='商品详情';
$this->params = array_merge($this->params,[
    
]);

?>

<?php $this->beginBlock('content')?>
<div class="header">
    <a class="back" href="javascript:history.go(-1)"></a>
    <div class="header_bar">
        <div class="item active">商品</div>
        <div class="item">详情</div>
    </div>
</div>

<div class="main">
    <div class="swiper-container shop-slide detailTab">
        <div class="swiper-wrapper">
            <?php $img=$model['img']?explode(',',$model['img']):[]; foreach ($img as $vo){?>
                <div class="swiper-slide"><img src="<?=$vo?>"></div>
            <?php }?>
        </div>
        <div class="swiper-pagination"></div>
    </div>
    <div class="pro_details">
        <div class="goods_info">
            <h1 class="goods_name">
                <?=$model['name']?>
            </h1>
            <p class="desc"><?=$model['intro']?></p>
            <div class="price_wrap">
                <span class="price">
                    <?php
                        if(!empty($model)) {
                            $price=$model->getUserPrice($model_user);
                            echo !is_null($price)?'￥'.$price:'';
                        }
                    ?></span>

                <span class="sales">销量 <?=$model['sold_num']?>件</span>
            </div>
        </div>

        <div class="group-warp">
            <div class="specification">
                <ul class="clearfix">
                    <?php foreach($model['linkSpu'] as $vo){?>
                        <li><?=$vo['name']?>：<?=$vo['val']?></li>
                    <?php }?>
                    <li>批号优于：<?=$model['p_no']?></li>
                </ul>
            </div>
            <div class="number clearfix">
                <span class="num_tip">数量选择</span>
                <div class="shop-arithmetic">
                    <div class="mui-numbox" data-numbox-min="1">
                        <button class="mui-btn mui-btn-numbox-minus" type="button"></button>
                        <input class="mui-input-numbox" type="number" id="buy-num" />
                        <button class="mui-btn mui-btn-numbox-plus" type="button"></button>
                    </div>
                    <!-- <span class="unit">公斤</span> -->
                </div>
            </div>
        </div>

        <div class="pull-detail detailTab">
            <div class="mod_tit_line">
                <h3>商品详情</h3>
            </div>
            <div class="lazyimg">
               <?=$model['content']?>
            </div>
        </div>

    </div>
</div>

<div class="footer">
    <div class="shop-bar-tab">
        <div class="bar-tab-item text-default" >
            <a href="<?=\yii\helpers\Url::to(['cart/index'])?>">
                <i class="iconfont icon-cart"></i>
                <div class="bar-tab-label">购物车</div>
            </a>
        </div>
        <div class="bar-tab-item text-default <?=$is_col?'checked':''?>"
             id="collect"
             onclick="$.common.reqInfo(this)"
             data-conf="{url:'<?=\yii\helpers\Url::to(['mine/goods-col'])?>',data:{gid:<?=$model['id']?$model['id']:0?>},success:handleCol}"
        >
            <i class="iconfont icon-star"></i>
            <div class="bar-tab-label">收藏</div>
        </div>
        <a class="bar-tab-item bg-warning" href="javascript:;" id="buy-buy" data-href="<?=\yii\helpers\Url::to(['order/info','gid'=>$model['id']])?>">立即下单</a>
        <div class="bar-tab-item bg-danger" onclick="$.common.reqInfo(this)" data-conf="{url:'<?=\yii\helpers\Url::to(['mine/add-cart'])?>',data:{gid:<?=$model['id']?$model['id']:0?>}}">加入购物车</div>
    </div>
</div>


<?php $this->endBlock()?>

<?php $this->beginBlock('script')?>
<link rel="stylesheet" type="text/css" href="<?=\Yii::getAlias('@assets')?>/assets/css/swiper.min.css" />
<script type="text/javascript" src="<?=\Yii::getAlias('@assets')?>/assets/js/swiper.min.js"></script>
<script>
    //收藏动作
    function handleCol(res){
        layui.layer.msg(res.msg)
        if(res.hasOwnProperty('is_col')){
            res.is_col?$("#collect").addClass('checked'):$("#collect").removeClass('checked')
        }
    }
    $(function(){
        var swiper = new Swiper('.swiper-container', {
            nextButton: '.swiper-button-next',
            prevButton: '.swiper-button-prev',
            pagination: '.swiper-pagination',
        });

        $('.header_bar .item').click(function() {
            $('html,body').animate({
                scrollTop: $('.detailTab').eq($(this).index()).offset().top - 50
            }, 500);
        });
        //滚动条移动到对应的位置，对应的标签高亮显示
        $(window.document).scroll(function() {
            for (var i = 0; i < $(".detailTab").length; i++) {
                if ($(window).scrollTop() > $(".detailTab").eq(i).offset().top - 51) {
                    $(".header_bar .item").eq(i).addClass("active").siblings(".header_bar .item").removeClass("active");
                }
            };
        });

        $("#buy-buy").click(function(){
            var href=$(this).data("href")
            var num = $("#buy-num").val()
            window.location.href=href+(href.indexOf('?')===-1?'?':'&')+'num='+num
        })
    })


</script>

<?php $this->endBlock()?>
