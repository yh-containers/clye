
<div class="footer">
    <div class="footer_bar">
        <div class="item <?=$current_active=='index'?'active':''?>">
            <a href="<?=\yii\helpers\Url::to(['index/index'])?>">
                <div class="icon sy-icon"></div>
                <div class="tab-label">首页</div>
            </a>
        </div>
        <div class="item <?=$current_active=='goods'?'active':''?>">
            <a href="<?=\yii\helpers\Url::to(['goods/index'])?>">
                <div class="icon sort-icon"></div>
                <div class="tab-label">商品</div>
            </a>
        </div>
        <div class="item <?=$current_active=='cart'?'active':''?>">
            <a href="<?=\yii\helpers\Url::to(['cart/index'])?>">
                <div class="icon cart-icon"></div>
                <div class="tab-label">购物车</div>
                <?=$cart_num?'<span>'.$cart_num.'</span>':''?>
            </a>
        </div>
        <div class="item <?=$current_active=='mine'?'active':''?>">
            <a href="<?=\yii\helpers\Url::to(['mine/index'])?>">
                <div class="icon my-icon"></div>
                <div class="tab-label">我的</div>
            </a>
        </div>
    </div>
</div>