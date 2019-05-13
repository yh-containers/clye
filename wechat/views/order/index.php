<?php
$this->title='我的订单';
$this->params = array_merge($this->params,[
]);

?>

<?php $this->beginBlock('content')?>
<div class="header">
    <a class="back" href="<?=\yii\helpers\Url::to(['mine/index'])?>"></a>
    <h4><?=$this->title?></h4>
</div>
<div class="my_nav">
    <ul class="my_nav_list">
        <li class="my_nav_list_item <?=$state?'':'active'?>"><a href="<?=\yii\helpers\Url::to(['order/index'])?>">全部</a></li>
        <li class="my_nav_list_item <?=$state==1?'active':''?>"><a href="<?=\yii\helpers\Url::to(['order/index','state'=>1])?>">待付款</a></li>
        <li class="my_nav_list_item <?=$state==2?'active':''?>"><a href="<?=\yii\helpers\Url::to(['order/index','state'=>2])?>">生产中</a></li>
        <li class="my_nav_list_item <?=$state==3?'active':''?>"><a href="<?=\yii\helpers\Url::to(['order/index','state'=>3])?>">待发货</a></li>
        <li class="my_nav_list_item <?=$state==4?'active':''?>"><a href="<?=\yii\helpers\Url::to(['order/index','state'=>4])?>">待收货</a></li>
        <li class="my_nav_list_item <?=$state==5?'active':''?>"><a href="<?=\yii\helpers\Url::to(['order/index','state'=>5])?>">已完成</a></li>
    </ul>
</div>

<div class="main">
    <div class="my_order_wrap">
        <div class="no-data" style="display:none">
            <div class="ico"><img src="<?=\Yii::getAlias('@assets')?>/assets/images/no-order.png" alt=""></div>
            <p>您还没有相关的订单<br><span>可以去看看有哪些想买~</span></p>
        </div>

        <div class="my_order_inner" id="demo">

        </div>
    </div>
</div>
<?php $this->endBlock()?>

<?php $this->beginBlock('script')?>
<script>
    $(function () {

        var url = '<?=\yii\helpers\Url::to(['show-list','state'=>$state])?>';
        var detail_url = '<?=\yii\helpers\Url::to(['detail'])?>';
        var opt_url = '<?=\yii\helpers\Url::to(['mine/add-cart'])?>';
        var del_order_url = '<?=\yii\helpers\Url::to(['order/del'])?>';
        var cancel_order_url = '<?=\yii\helpers\Url::to(['order/cancel-order'])?>';
        var logistics_url = '<?=\yii\helpers\Url::to(['order/logistics'])?>';
        var once_again = '<?=\yii\helpers\Url::to(['order/info'])?>';
        var receive_url = '<?=yii\helpers\Url::to(['order/receive'])?>';
        $(function(){
            layui.use('flow', function(){
                var $ = layui.jquery; //不用额外加载jQuery，flow模块本身是有依赖jQuery的，直接用即可。
                var flow = layui.flow;
                flow.load({
                    elem: '#demo' //指定列表容器
                    ,end:' '
                    ,done: function(page, next){ //到达临界点（默认滚动触发），触发下一页
                        var lis = [];
                        //以jQuery的Ajax请求为例，请求下一页数据（注意：page是从2开始返回）
                        $.get(url+(url.indexOf('?')===-1?'?':'&')+'page='+page, function(res){

                            //假设你的列表返回在data集合中
                            layui.each(res.data, function(index, item){
                                var data = [],handle=[];
                                var once_g_info='';
                                if(item.hasOwnProperty('goods'))  data=item.goods?item.goods:[];
                                if(item.hasOwnProperty('handle'))  handle=item.handle?item.handle:[];
                                var html='<div class="order_list">\n' +
                                    '                <a href="'+detail_url+(detail_url.indexOf('?')===-1?'?':'&')+'id='+item.id+'">\n' +
                                    '                    <div class="module seller">\n' +
                                    '                        <div class="shop-name">\n' +
                                    '                            <h4>订单编号：'+item.no+'</h4>\n' +
                                    '                        </div>\n' +
                                    '                        <div class="status">'+item.step_info_name+'</div>\n' +
                                    '                    </div>\n' +
                                    '                    <div class="module item">\n';
                                    data.map(function(goods_item,index){
                                        //商品数据
                                        once_g_info+=goods_item.gid+'-'+goods_item.num+',';
                                        html +='              <div class="item-list">\n' +
                                            '                            <div class="item-img"><img src="'+goods_item.cover_img+'" alt=""></div>\n' +
                                            '                            <div class="item-info">\n' +
                                            '                                <h3 class="title">'+goods_item.name+'</h3>\n' +
                                            '                                <div class="item-pay-data">\n' +
                                            '                                    <p class="price">¥'+goods_item.money+'</p>\n' +
                                            '                                    <p class="nums">x'+goods_item.num+'</p>\n' +
                                            '                                </div>\n' +
                                            '                            </div>\n' +
                                            '                        </div>\n';
                                    })


                                    html +='                    </div>\n' +
                                    '                    <div class="module total-pay">\n' +
                                    '                        <div class="total-price"><span>共<em>'+item.goods_num+'</em>件商品</span> <span>合计:<em class="price">¥'+item.goods_money+'</em></span></div>\n' +
                                    '                    </div>\n' +
                                    '                </a>\n' +
                                    '                <div class="module orderop clearfix">\n' +
                                    '                    <div class="tab-btn clearfix">\n';
                                    //物流查看
                                    if(handle.indexOf('logistics')!==-1){
                                        html+='<a href="'+logistics_url+(logistics_url.indexOf('?')===-1?'?':'&')+'id='+item.id+'" class="oh_btn">查看物流</a>\n';
                                    }

                                    //取消订单
                                    if(handle.indexOf('cancel_order')!==-1){
                                        html+='<a href="javascript:;"' +
                                            'onclick="$.common.reqInfo(this,{confirm_title:\'是否取消订单\'})"'  +
                                            'data-conf="{url:'+"'"+cancel_order_url+"'"+',data:{id:'+"'"+item.id+"'"+'},success:del_order}"' +
                                            ' class="oh_btn">取消订单</a>\n';
                                    }
                                    //删除订单
                                    if(handle.indexOf('delete')!==-1){
                                        html+='<a href="javascript:;"' +
                                            'onclick="$.common.reqInfo(this,{confirm_title:\'是否删除订单\'})"'  +
                                            'data-conf="{url:'+"'"+del_order_url+"'"+',data:{id:'+"'"+item.id+"'"+'},success:del_order}"' +
                                            ' class="oh_btn">删除订单</a>\n';
                                    }
                                    //确认收货
                                    if(handle.indexOf('receive')!==-1){
                                        html+='<a href="javascript:;" ' +
                                            'class="oh_btn"' +
                                            'onclick="$.common.reqInfo(this,{confirm_title:\'确定收货?\'})"'+
                                            'data-conf="{url:'+"'"+receive_url+"'"+',data:{id:'+"'"+item.id+"'"+'},success:receive_success}" class="mod_btn bg_orange"'+
                                            '>确认收货</a>\n';
                                    }
                                    html+='<a href="'+detail_url+(detail_url.indexOf('?')===-1?'?':'&')+'id='+item.id+'" class="oh_btn">查看订单</a>\n';

                                    //再次购买
                                    if(item.hasOwnProperty('status') && item.status>=2){
                                        html+='<a href="'+once_again+(once_again.indexOf('?')===-1?'?':'&')+'channel=once_again&channel_g_data='+once_g_info+'" class="oh_btn line_btn">再次购买</a>\n';
                                    }
                                    html+= '          </div>\n' +
                                    '                </div>\n' +
                                    '            </div>'
                                lis.push(html);
                            });

                            //执行下一页渲染，第二参数为：满足“加载更多”的条件，即后面仍有分页
                            //pages为Ajax返回的总页数，只有当前页小于总页数的情况下，才会继续出现加载更多
                            next(lis.join(''), page < res.pages);
                            //显示无商品信息
                            if(page===1 && lis.length===0){
                                $(".no-data").show()
                            }
                        });
                    }
                });
            });

        })

    });
    //删除订单刷新页面
    function del_order(res){
        layui.layer.msg(res.msg)
        if(res.code==1){
            setTimeout(function(){location.reload()},1000)
        }
    }

    //删除订单刷新页面
    function receive_success(res){
        layui.layer.msg(res.msg)
        if(res.code==1){
           location.reload()
        }
    }
</script>

<?php $this->endBlock()?>
