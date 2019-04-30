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
        <?php if(empty($data)){?>
            <div class="data-tips">
                <div class="ico"><img src="<?=\Yii::getAlias('@assets')?>/assets/images/no-cart.png" alt=""></div>
                <p>您的购物车还没有任何商品<br><span>快去逛逛吧~</span></p>
                <div class="btn">
                    <a href="<?=\yii\helpers\Url::to(['index/index'])?>">去首页</a>
                </div>
            </div>
        <?php }else{?>

            <div class="shopping" style="display:block;">
                <div class="shop-group-item">
                    <ul>
                        <?php foreach($data as $vo){?>
                            <li>
                                <div class="shop-info">
                                    <input type="checkbox" class="check goods-check goodsCheck" data-cart_id="<?=$vo['id']?>" data-gid="<?=$vo['gid']?>" <?=$vo['is_checked']?'checked':''?>>
                                    <div class="shop-info-img">
                                        <a href="<?=\yii\helpers\Url::to(['goods/detail','id'=>$vo['gid']])?>"><img src="<?=\common\models\Goods::getCoverImg($vo['linkGoods']['img'])?>"></a>
                                    </div>
                                    <div class="shop-info-text">
                                        <h4><?=$vo['linkGoods']['name']?></h4>
                                        <p class="desc"><?=$vo['linkGoods']['intro']?></p>
                                        <div class="shop-price">
                                            <div class="shop-pices">¥<span class="price"><?=$vo['linkGoods']['price']?></span></div>
                                            <div class="shop-arithmetic">
                                                <a href="javascript:;" data-gid="<?=$vo['gid']?>" class="minus"> </a>
                                                <span class="num"><?=$vo['num']?></span>
                                                <a href="javascript:;" data-gid="<?=$vo['gid']?>" class="plus"> </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        <?php }?>
                    </ul>
                    <div class="shopPrice" style="display:none;"><span class="shop-total-amount ShopTotal">0.00</span></div>
                </div>
            </div>
        <?php }?>



        <div class="payment-bar" style="display:block;">
            <div class="all-checkbox"><label for="AllCheck"><input type="checkbox" class="check goods-check" id="AllCheck">全选</label></div>
            <div class="shop-total">
                <strong>总价：<span class="price">¥<i class="price" id="AllTotal">0.00</i></span></strong>
            </div>
            <a class="settlement del-cart" id="delete" style="display: none;">删除</a>
            <a class="add_favorites" id="collect" style="display: none;">移入收藏</a>
            <a href="javascript:;" data-href="<?=\yii\helpers\Url::to(['order/info','channel'=>'cart'])?>" id="settlement" class="settlement">去结算</a>
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
    var goods_check_url = "<?=\yii\helpers\Url::to(['mine/cart-choose'])?>";
    var goods_cart_url = "<?=\yii\helpers\Url::to(['mine/add-cart'])?>";
    var cart_del_url = "<?=\yii\helpers\Url::to(['mine/cart-del'])?>";
    var goods_col_url = "<?=\yii\helpers\Url::to(['mine/goods-col'])?>";
    var is_edit=false;
    function changeState(e) {
        var state = $(e).attr("data-change");
        if (state == 1) {
            is_edit=true;
            $(e).html("完成");
            $(e).attr("data-change", 0);
            $(".shop-total").css("display", "none");
            $("#settlement").css("display", "none");
            $("#delete").css("display", "block");
            $("#collect").css("display", "block");
        } else {
            is_edit=false;
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
            var gid = $(this).data('gid')
            var num =parseInt(t.text()) - 1;
            if(num>1){
                $.common.reqInfo({url:goods_cart_url,data:{gid:gid,num:-1},success:function(){
                        t.text(num);
                    }})
                if (t.text() <= 1) {
                    t.text(1);
                }
                TotalPrice();
            }

        });
        // 数量加
        $(".plus").click(function() {
            var t = $(this).parent().find('.num');
            var gid = $(this).data('gid')
            console.log(2);
            var num =parseInt(t.text()) + 1;
            $.common.reqInfo({url:goods_cart_url,data:{gid:gid},success:function(){
                    t.text(num);
            }})
            if (t.text() <= 1) {
                t.text(1);
            }
            TotalPrice();
        });
        //删除购物车
        $(".del-cart").click(function(){
            var c_ids=[]
            $(".shop-group-item input[type='checkbox']:checked").each(function(){
                c_ids.push($(this).data('cart_id'))
            })
            if(!c_ids.length){
                layui.layer.msg('请选择要删除的对象')
                return false;
            }
            layui.layer.confirm('是否删除选中数据',function(){
                $.common.reqInfo({url:cart_del_url,data:{c_ids:c_ids},success:function(res){
                    layui.layer.msg(res.msg)
                    if(res.code==1){
                        $(".shop-group-item input[type='checkbox']:checked").each(function(){
                            $(this).parents('li').remove()
                        })
                        //无数据刷新页面
                        if($(".shop-group-item input[type='checkbox']").length===0){
                            setTimeout(function(){location.reload()},1000)
                        }
                    }
                }})
            })
        })

        //添加收藏
        $("#collect").click(function(){
            var gid=[]
            $(".shop-group-item input[type='checkbox']:checked").each(function(){
                gid.push($(this).data('gid'))
            })
            if(!gid.length){
                layui.layer.msg('请选择要收藏的对象')
                return false;
            }
            layui.layer.confirm('是否收藏选中数据',function(){
                $.common.reqInfo({url:goods_col_url,data:{gid:gid},success:function(res){
                    layui.layer.msg(res.msg)
                }})
            })
        })

        /******------------分割线-----------------******/
        // 点击商品按钮
        $(".goodsCheck").click(function() {
            var $this=$(this);
            var cart_id = $this.data('cart_id');
            reqCheckInfo({cart_id:cart_id},function(res){
                if(res.hasOwnProperty('is_checked')){
                    $this.prop('checked',res.is_checked?true:false)
                }
                //计算价格
                TotalPrice();
                //判断全选
                fullChoose();
            })
        });
        //请求信息
        function reqCheckInfo(data,func) {
            var data = data?data:{}
            //ajax请求事件
            //非编辑状态
            $.common.reqInfo({url:goods_check_url,data:data,success:func})
        }

        //计算价格
        TotalPrice();
        //全选按钮
        fullChoose()
        function fullChoose() {
            if($(".shop-group-item input[type='checkbox']").length===$(".shop-group-item input[type='checkbox']:checked").length){
                $("#AllCheck").prop('checked', true);
            }else{
                $("#AllCheck").prop('checked', false);
            }
        }


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
        //全选按钮
        $("#AllCheck").click(function() {
            var is_checked = $(this).prop("checked");
            reqCheckInfo({is_checked:(is_checked?1:0)},function(res){
                if(res.hasOwnProperty('is_checked')){
                    $(".goods-check").prop('checked', res.is_checked?true:false);
                    //计算价格
                    TotalPrice();
                }
            })


        });

        //去结算
        $("#settlement").click(function(){
            if(!$(".shop-group-item input[type='checkbox']:checked").length){
                layui.layer.msg('请选择需要购买的商品')
                return false;
            }
            var href = $(this).data('href');
            window.location.href=href
        })

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
