<?php
$this->title='我的收藏';
$this->params = array_merge($this->params,[
]);

?>

<?php $this->beginBlock('content')?>
    <div class="header">
        <a class="back" href="javascript:history.go(-1)"></a>
        <h4>我的收藏</h4>
        <a class="right delete" onclick="changeState(this)" data-change="1">管理</a>
    </div>

    <div class="main">
        <div class="no-data" style="display:none;">
            <div class="ico"><img src="<?=\Yii::getAlias('@assets')?>/assets/images/no-collect.png" alt=""></div>
            <p>您还没有关注任何商品</p>
        </div>
        <div class="fav_items" id="j_tb">

        </div>
        <div class="fav_fixbar">
            <span class="fav_select_all" id="selectAllBtn"><input type="checkbox" class="radioclass" id="j_cbAll">全选</span>
            <a href="javascript:void(0);" class="btn" id="multiCancle">删除</a>
        </div>

    </div>
<?php $this->endBlock()?>

<?php $this->beginBlock('script')?>
<script>
    function changeState(e) {
        var state = $(e).attr("data-change");
        if (state == 1) {
            $(e).html("完成");
            $(e).attr("data-change", 0);
            $(".fav_items").addClass("fav_items_edit");
            $(".fav_select").css("display", "block");
            $(".fav_fixbar").css("display", "block");
        } else {
            $(e).html("管理");
            $(e).attr("data-change", 1);
            $(".fav_items").removeClass("fav_items_edit");
            $(".fav_select").css("display", "none");
            $(".fav_fixbar").css("display", "none");
        }
    };
    $(".fav_select_all").on("click",function(){
        if(!$(this).hasClass("selected")){
            $(this).addClass("selected");
            $(".fav_select").addClass("selected");
        }else{
            $(this).removeClass("selected");
            $(".fav_select").removeClass("selected");
        }
    });
    $("body").on("click",".fav_select",function(){
        $(this).toggleClass("selected");
        if($(".fav_select").length==$(".fav_select.selected").length){
            $(".fav_select_all").addClass("selected");
        }else{
            $(".fav_select_all").removeClass("selected");
        }
    });

    var all = document.getElementById("j_cbAll");
    var tbody = document.getElementById("j_tb");
    var checkboxs = tbody.getElementsByTagName("input");
    all.onclick = function() {
        for (var i = 0; i < checkboxs.length; i++) {
            var checkbox = checkboxs[i];
            checkbox.checked = this.checked;
        }
    };
    for (var i = 0; i < checkboxs.length; i++) {
        checkboxs[i].onclick = function() {
            var isCheckedAll = true;
            for (var i = 0; i < checkboxs.length; i++) {
                if (!checkboxs[i].checked) {
                    isCheckedAll = false;
                    break;
                }
            }
            all.checked = isCheckedAll;
        };
    }

    $(function () {
        var url = '<?=\yii\helpers\Url::to(['collect-list'])?>';
        var detail_url = '<?=\yii\helpers\Url::to(['goods/detail'])?>';
        var opt_url = '<?=\yii\helpers\Url::to(['mine/add-cart'])?>';
        var col_del_url = '<?=\yii\helpers\Url::to(['mine/goods-col'])?>';
        layui.use('flow', function(){
            var $ = layui.jquery; //不用额外加载jQuery，flow模块本身是有依赖jQuery的，直接用即可。
            var flow = layui.flow;
            flow.load({
                elem: '#j_tb' //指定列表容器
                ,end:' '
                ,done: function(page, next){ //到达临界点（默认滚动触发），触发下一页
                    var lis = [];
                    //以jQuery的Ajax请求为例，请求下一页数据（注意：page是从2开始返回）
                    $.get(url+(url.indexOf('?')===-1?'?':'&')+'page='+page, function(res){
                        //假设你的列表返回在data集合中
                        layui.each(res.data, function(index, item){
                            lis.push(' <div class="fav_item">\n' +
                                '                <div class="fav_select">\n' +
                                '                    <input type="checkbox" class="radioclass" data-gid="'+item.goods_id+'">\n' +
                                '                </div>\n' +
                                '                <div class="move_div">\n' +
                                '                    <a class="fav_link fav_link_goods" href="'+detail_url+(detail_url.indexOf('?')===-1?'?':'&')+'id='+item.goods_id+'">\n' +
                                '                        <div class="image"><img src="'+item.cover_img+'"></div>\n' +
                                '                        <div class="content">\n' +
                                '                            <p class="name">'+item.name+'</p>\n' +
                                '                            <p class="price">￥'+item.price+'</p>\n' +
                                '                        </div>\n' +
                                '                    </a>\n' +
                                '                    <a href="javascript:;" ' +
                                'class="addcart"' +
                                'onclick="$.common.reqInfo(this)" ' +
                                'data-conf="{url:'+"'"+opt_url+"'"+',data:{gid:'+"'"+item.goods_id+"'"+'}}"' +
                                '><i class="icon"></i></a>\n' +
                                '                </div>\n' +
                                '            </div>');
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

        //删除动作
        $("#multiCancle").click(function(){
            var gid = [];
            $("#j_tb input[type='checkbox']:checked").each(function(){
                gid.push($(this).data('gid'))
            })
            if(!gid.length){
                layui.layer.msg('请选择要删除的数据')
                return false;
            }
            layui.layer.confirm('是否删除选中的数据',function(){
                $.common.reqInfo({url:col_del_url,data:{gid:gid,is_del:1},success:function(res){
                    layui.layer.msg(res.msg)
                    if(res.code==1){
                        setTimeout(function(){location.reload()},1000)
                    }
                }})
            })
        })

    });

</script>

<?php $this->endBlock()?>
