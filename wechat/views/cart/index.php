<?php
$this->title='购物车';
$this->params = array_merge($this->params,[
]);

?>

<?php $this->beginBlock('content')?>
<div class="header">
    <h4>购物车</h4>
    <a class="right delete" onclick="changeState(this)" data-change="1">编辑</a>
</div>
<div class="main">
    <div class="shopping_main">
        <div class="data-tips" style="display:none;">
            <div class="ico"><img src="<?=\Yii::getAlias('@assets')?>/assets/images/no-cart.png" alt=""></div>
            <p>您的购物车还没有任何商品<br><span>快去逛逛吧~</span></p>
            <div class="btn">
                <a href="index.html">去首页</a>
            </div>
        </div>

        <div class="shopping" style="display:block;">
            <div class="shop-group-item">
                <ul>
                    <li>
                        <div class="shop-info">
                            <input type="checkbox" class="check goods-check goodsCheck">
                            <div class="shop-info-img">
                                <a href="products-det.html"><img src="<?=\Yii::getAlias('@assets')?>/assets/images/pro-1.jpg"></a>
                            </div>
                            <div class="shop-info-text">
                                <h4>三七超细粉</h4>
                                <p class="desc">散瘀止血，消肿止痛、益气活血</p>
                                <div class="shop-price">
                                    <div class="shop-pices">¥<span class="price">100.01</span></div>
                                    <div class="shop-arithmetic">
                                        <a href="javascript:;" class="minus"> </a>
                                        <span class="num">20</span>
                                        <a href="javascript:;" class="plus"> </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="shop-info">
                            <input type="checkbox" class="check goods-check goodsCheck">
                            <div class="shop-info-img">
                                <a href="products-det.html"><img src="<?=\Yii::getAlias('@assets')?>/assets/images/pro-2.jpg"></a>
                            </div>
                            <div class="shop-info-text">
                                <h4>三七超细粉</h4>
                                <p class="desc">散瘀止血，消肿止痛、益气活血</p>
                                <div class="shop-price">
                                    <div class="shop-pices">¥<span class="price">100.20</span></div>
                                    <div class="shop-arithmetic">
                                        <a href="javascript:;" class="minus"> </a>
                                        <span class="num">1</span>
                                        <a href="javascript:;" class="plus"> </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="shop-info">
                            <input type="checkbox" class="check goods-check goodsCheck">
                            <div class="shop-info-img">
                                <a href="products-det.html"><img src="<?=\Yii::getAlias('@assets')?>/assets/images/pro-3.jpg"></a>
                            </div>
                            <div class="shop-info-text">
                                <h4>三七超细粉</h4>
                                <p class="desc">散瘀止血，消肿止痛、益气活血</p>
                                <div class="shop-price">
                                    <div class="shop-pices">¥<span class="price">100.99</span></div>
                                    <div class="shop-arithmetic">
                                        <a href="javascript:;" class="minus"> </a>
                                        <span class="num">1</span>
                                        <a href="javascript:;" class="plus"> </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
                <div class="shopPrice" style="display:none;"><span class="shop-total-amount ShopTotal">0.00</span></div>
            </div>
        </div>

        <div class="payment-bar" style="display:block;">
            <div class="all-checkbox"><label for="AllCheck"><input type="checkbox" class="check goods-check" id="AllCheck">全选</label></div>
            <div class="shop-total">
                <strong>总价：<span class="price">¥<i class="price" id="AllTotal">0.00</i></span></strong>
            </div>
            <a class="settlement" id="delete" style="display: none;">删除</a>
            <a class="add_favorites" id="collect" style="display: none;">移入收藏</a>
            <a href="order_confirm.html" id="settlement" class="settlement">去结算</a>
        </div>
    </div>
</div>

<div id="delete" class="mui-popover mui-popover-action mui-popover-bottom">
    <ul class="mui-table-view">
        <li class="mui-table-view-cell">
            <a href="#" style="color: #FF3B30;">删除信息</a>
        </li>
    </ul>
    <ul class="mui-table-view">
        <li class="mui-table-view-cell">
            <a href="#delete"><b>取消</b></a>
        </li>
    </ul>
</div>

<?=\wechat\widgets\Footer::widget(['current_active'=>'cart'])?>
<?php $this->endBlock()?>

<?php $this->beginBlock('script')?>
<script>
    function changeState(e) {
        var state = $(e).attr("data-change");
        if (state == 1) {
            $(e).html("完成");
            $(e).attr("data-change", 0);
            $(".shop-total").css("display", "none");
            $("#settlement").css("display", "none");
            $("#delete").css("display", "block");
            $("#collect").css("display", "block");
        } else {
            $(e).html("编辑");
            $(e).attr("data-change", 1);
            $(".shop-total").css("display", "flex");
            $("#settlement").css("display", "block");
            $("#delete").css("display", "none");
            $("#collect").css("display", "none");
        }
    };

    $(function() {
        // 数量减
        $(".minus").click(function() {
            var t = $(this).parent().find('.num');
            console.log(1);
            t.text(parseInt(t.text()) - 1);
            if (t.text() <= 1) {
                t.text(1);
            }
            TotalPrice();
        });
        // 数量加
        $(".plus").click(function() {
            var t = $(this).parent().find('.num');
            console.log(2);
            t.text(parseInt(t.text()) + 1);
            if (t.text() <= 1) {
                t.text(1);
            }
            TotalPrice();
        });
        /******------------分割线-----------------******/
        // 点击商品按钮
        $(".goodsCheck").click(function() {
            var goods = $(this).closest(".shop-group-item").find(".goodsCheck");
            var goodsC = $(this).closest(".shop-group-item").find(".goodsCheck:checked");
            var Shops = $(this).closest(".shop-group-item").find(".shopCheck");
            if (goods.length == goodsC.length) {
                Shops.prop('checked', true);
                if ($(".shopCheck").length == $(".shopCheck:checked").length) {
                    $("#AllCheck").prop('checked', true);
                    TotalPrice();
                } else {
                    $("#AllCheck").prop('checked', false);
                    TotalPrice();
                }
            } else {
                Shops.prop('checked', false);
                $("#AllCheck").prop('checked', false);

                TotalPrice();

            }
        });

        $(".shopCheck").click(function() {
            if ($(this).prop("checked") == true) {
                $(this).parents(".shop-group-item").find(".goods-check").prop('checked', true);
                if ($(".shopCheck").length == $(".shopCheck:checked").length) {
                    $("#AllCheck").prop('checked', true);
                    TotalPrice();
                } else {
                    $("#AllCheck").prop('checked', false);
                    TotalPrice();
                }
            } else {
                $(this).parents(".shop-group-item").find(".goods-check").prop('checked', false);
                $("#AllCheck").prop('checked', false);
                TotalPrice();
            }
        });

        $("#AllCheck").click(function() {
            if ($(this).prop("checked") == true) {
                $(".goods-check").prop('checked', true);
                TotalPrice();
            } else {
                $(".goods-check").prop('checked', false);
                TotalPrice();
            }
            $(".shopCheck").change();
        });

        function TotalPrice() {
            var allprice = 0;
            $(".shop-group-item").each(function() {
                var oprice = 0;
                $(this).find(".goodsCheck").each(function() {
                    if ($(this).is(":checked")) {
                        var num = parseInt($(this).parents(".shop-info").find(".num").text());
                        var price = parseFloat($(this).parents(".shop-info").find(".price").text());
                        var total = price * num;
                        oprice += total;
                    }
                    $(this).closest(".shop-group-item").find(".ShopTotal").text(oprice.toFixed(2));
                });
                var oneprice = parseFloat($(this).find(".ShopTotal").text());
                allprice += oneprice;
            });
            $("#AllTotal").text(allprice.toFixed(2));
        }
    });

</script>

<?php $this->endBlock()?>
