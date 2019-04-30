<?php
$this->title='收货地址管理';
$this->params = array_merge($this->params,[
        'body_class' => 'bg'
]);

?>

<?php $this->beginBlock('content')?>
<div class="header">
    <a class="back" href="javascript:history.go(-1)"></a>
    <h4><?=$this->title?></h4>
</div>
<div class="main">
    <div class="address">
        <div class="addr">
            <a href="<?=\yii\helpers\Url::to(['address-addr'])?>">添加新地址</a>
        </div>

        <div id="addr_skid">

        </div>
    </div>
</div>
<?php $this->endBlock()?>

<?php $this->beginBlock('script')?>
<script>
    var channel = "<?=$channel?>"
    $(function () {
        var url = '<?=\yii\helpers\Url::to(['address-list'])?>';
        var detail_url = '<?=\yii\helpers\Url::to(['mine/address-addr'])?>';
        layui.use('flow', function(){
            var $ = layui.jquery; //不用额外加载jQuery，flow模块本身是有依赖jQuery的，直接用即可。
            var flow = layui.flow;
            flow.load({
                elem: '#addr_skid' //指定列表容器
                ,done: function(page, next){ //到达临界点（默认滚动触发），触发下一页
                    var lis = [];
                    //以jQuery的Ajax请求为例，请求下一页数据（注意：page是从2开始返回）
                    $.get(url+(url.indexOf('?')===-1?'?':'&')+'page='+page, function(res){
                        //假设你的列表返回在data集合中
                        layui.each(res.data, function(index, item){
                            lis.push(' <div class="address-list">\n' +
                                '                <a class="addr_info" href="javascript:;" data-id='+item.id+'>\n' +
                                '                    <div class="addr_title">\n' +
                                '                        <span class="name">'+item.username+'</span>\n' +
                                '                        <span class="tel">'+item.phone+'</span>\n' +
                                '\n' +
                                '                    </div>\n' +
                                '                    <div class="addr_desc">\n' +
                                '                        '+(item.is_default?'<span class="label">默认</span>':'')+''+item.addr+item.addr_extra+'\n' +
                                '                    </div>\n' +
                                '                </a>\n' +
                                '                <a class="addr_edit" href="'+detail_url+(detail_url.indexOf('?')===-1?'?':'&')+'id='+item.id+'">\n' +
                                '                    <em>编辑</em>\n' +
                                '                </a>\n' +
                                '            </div>');
                        });

                        //执行下一页渲染，第二参数为：满足“加载更多”的条件，即后面仍有分页
                        //pages为Ajax返回的总页数，只有当前页小于总页数的情况下，才会继续出现加载更多
                        next(lis.join(''), page < res.pages);
                    });
                }
            });
        });


        $("#addr_skid").on('click','.addr_info',function(){
            if(channel){

                var addr_id = $(this).data('id');
                var up_href = document.referrer
                window.location.href= up_href+(up_href.indexOf('?')===-1?'?':'&')+'channel='+channel+'&addr_id='+addr_id
            }
        })

    });



</script>

<?php $this->endBlock()?>
