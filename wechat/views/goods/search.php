<?php
$this->title='检索商品';
$this->params = array_merge($this->params,[
]);

?>

<?php $this->beginBlock('content')?>
<div class="header">
    <a class="back" href="javascript:history.go(-1)"></a>
    <div class="header-container">
        <div class="search-wrap search-right clearfix">
            <div class="search">
                <form>
                <input class="input-text" type="text" name="keyword" value="<?=$keyword?>" placeholder="请输入搜索关键词！" id="newinput" autofocus="autofocus">
                <button type="submit" class="btn-submit">搜索</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="main">
    <div class="panel">
        <div class="title">
            <div class="pull-left"><i class="icon-hot"></i>热门搜索</div>
        </div>
        <div class="list">
            <a class="item" href="javascript:;">blackmores</a>
        </div>
    </div>

    <div class="no-data" style="display:none">
        <img class="lazyload" data-original="images/no-search.png" alt="">
        <p>抱歉！暂无相关商品</p>
    </div>
    <div class="goods_list itemList">
        <ul id="demo">



        </ul>
    </div>
</div>

<?=\wechat\widgets\Footer::widget(['current_active'=>'goods'])?>
<?php $this->endBlock()?>

<?php $this->beginBlock('script')?>
<script>
    var url = '<?=\yii\helpers\Url::to(['show-list','cid'=>$cid,'keyword'=>$keyword])?>';
    var detail_url = '<?=\yii\helpers\Url::to(['detail'])?>';
    $(function(){
        layui.use('flow', function(){
            var $ = layui.jquery; //不用额外加载jQuery，flow模块本身是有依赖jQuery的，直接用即可。
            var flow = layui.flow;
            flow.load({
                elem: '#demo' //指定列表容器
                ,done: function(page, next){ //到达临界点（默认滚动触发），触发下一页
                    var lis = [];
                    //以jQuery的Ajax请求为例，请求下一页数据（注意：page是从2开始返回）
                    $.get(url+(url.indexOf('?')===-1?'?':'&')+'page='+page, function(res){
                        //假设你的列表返回在data集合中
                        layui.each(res.data, function(index, item){
                            lis.push('<li class="good_item">\n' +
                                '                <a href="'+detail_url+(detail_url.indexOf('?')===-1?'?':'&')+'id='+item.id+'">\n' +
                                '                    <div class="img">\n' +
                                '                        <img src="'+item.cover_img+'" class="lazyload" alt="">\n' +
                                '                    </div>\n' +
                                '                    <div class="prolist_info">\n' +
                                '                        <div class="name">'+item.name+'</div>\n' +
                                '                        <div class="desc">'+item.intro+'</div>\n' +
                                '                        <div class="price">\n' +
                                '                            <span>¥'+item.price+'</span>\n' +
                                '                        </div>\n' +
                                '                    </div>\n' +
                                '                </a>\n' +
                                '                <a href="javascript:;" class="cart_btn" id="addCart"></a>\n' +
                                '            </li>');
                        });

                        //执行下一页渲染，第二参数为：满足“加载更多”的条件，即后面仍有分页
                        //pages为Ajax返回的总页数，只有当前页小于总页数的情况下，才会继续出现加载更多
                        next(lis.join(''), page < res.pages);
                    });
                }
            });
        });
    })

</script>

<?php $this->endBlock()?>
