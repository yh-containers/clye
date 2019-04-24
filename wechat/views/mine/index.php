<?php
$this->title='购物车';
$this->params = array_merge($this->params,[
]);

?>

<?php $this->beginBlock('content')?>
<div class="user-info">
    <div class="user-bg">
        <div class="list-item-middle">
            <div class="media-list-item-inner">
                <a href="personal.html" class="clearfix">
                    <div class="list-item-media">
                        <img src="<?=$user_model['face']?>" >
                    </div>
                    <div class="list-item-text">
                        <div class="name"><?=$user_model['username']?></div>
                        <div class="info"><?=substr_replace($user_model['phone'],'****',3,4)?></div>
                    </div>
                </a>
            </div>
        </div>
        <div class="list-item-nav">
            <div class="user-nav clearfix">
                <div class="col-xs-4">
                    <a href="collect.html">
                        <div class="num">16</div>
                        <div class="label">收藏</div>
                    </a>
                </div>
                <div class="col-xs-4">
                    <a href="address.html">
                        <div class="icon icon-add"></div>
                        <div class="label">地址管理</div>
                    </a>
                </div>
                <div class="col-xs-4">
                    <a href="personal.html">
                        <div class="icon icon-edit"></div>
                        <div class="label">编辑资料</div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="user-vip">
        <div class="grade">
            游客
        </div>
        <a href="user_apply.html" class="apply">点击申请升级</a>
    </div>
</div>
<div class="container">
    <section class="content-grid margin-b-15">
        <div class="user-title">
            <h4>我的订单</h4>
            <a href="order_list.html" class="mui-push-right">全部订单</a>
        </div>
        <div class="row user-list">
            <div class="col-xs-3">
                <a href="order_list.html">
                    <div class="icon icon-1"><span class="badge">1</span></div>
                    <div class="gird-lable">待付款</div>
                </a>
            </div>
            <div class="col-xs-3">
                <a href="order_list.html">
                    <div class="icon icon-2"></div>
                    <div class="gird-lable">生产中</div>
                </a>
            </div>
            <div class="col-xs-3">
                <a href="order_list.html">
                    <div class="icon icon-3"></div>
                    <div class="gird-lable">待发货</div>
                </a>
            </div>
            <div class="col-xs-3">
                <a href="evaluation_list.html">
                    <div class="icon icon-4"></div>
                    <div class="gird-lable">待收货</div>
                </a>
            </div>
        </div>
    </section>
    <section class="content-grid margin-b-15">
        <div class="row user-list">
            <a class="mui-navigate-right" href="user_info.html">
                <div class="icon icon-5"></div>
                <div class="gird-lable">会员信息查看</div>
            </a>
            <a class="mui-navigate-right" href="user_apply.html">
                <div class="icon icon-6"></div>
                <div class="gird-lable">会员升级申请</div>
            </a>
            <a class="mui-navigate-right" href="contract-list.html">
                <div class="icon icon-7"></div>
                <div class="gird-lable">合同管理</div>
            </a>
            <a class="mui-navigate-right" href="faq.html">
                <div class="icon icon-8"></div>
                <div class="gird-lable">常见问题</div>
            </a>

        </div>
    </section>
</div>

<div class="footer">
    <div class="footer_bar">
        <div class="item">
            <a href="index.html">
                <div class="icon sy-icon"></div>
                <div class="tab-label">首页</div>
            </a>
        </div>
        <div class="item">
            <a href="products.html">
                <div class="icon sort-icon"></div>
                <div class="tab-label">商品</div>
            </a>
        </div>
        <div class="item">
            <a href="cart.html">
                <div class="icon cart-icon"></div>
                <div class="tab-label">购物车</div>
                <span>2</span>
            </a>
        </div>
        <div class="item active">
            <a href="member.html">
                <div class="icon my-icon"></div>
                <div class="tab-label">我的</div>
            </a>
        </div>
    </div>
</div>

<?=\wechat\widgets\Footer::widget(['current_active'=>'mine'])?>
<?php $this->endBlock()?>

<?php $this->beginBlock('script')?>
<script>

</script>

<?php $this->endBlock()?>
