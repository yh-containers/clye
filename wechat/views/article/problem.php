<?php
$this->title='联系我们';
$this->params = array_merge($this->params,[
        'body_class' => 'bg'
]);
?>

<?php $this->beginBlock('content')?>
<div class="header">
    <a class="back" href="javascript:history.go(-1)"></a>
</div>

<div class="main">
    <div class="about_head">
        <h4>常见问题</h4>
    </div>
    <div class="text_wrap">
        <ul id="accordion" class="accordion">

        </ul>

    </div>
</div>




<?php $this->endBlock()?>

<?php $this->beginBlock('script')?>

<script>
    $(function() {
        $("#accordion").on('click', 'li',function(){
            $(this).children().next().slideToggle()
        })

        var url = '<?=\yii\helpers\Url::to(['problem-list'])?>';
        $(function(){
            layui.use('flow', function(){
                var $ = layui.jquery; //不用额外加载jQuery，flow模块本身是有依赖jQuery的，直接用即可。
                var flow = layui.flow;
                flow.load({
                    elem: '#accordion' //指定列表容器
                    ,end:' '
                    ,done: function(page, next){ //到达临界点（默认滚动触发），触发下一页
                        var lis = [];
                        //以jQuery的Ajax请求为例，请求下一页数据（注意：page是从2开始返回）
                        $.get(url+(url.indexOf('?')===-1?'?':'&')+'page='+page, function(res){
                            //假设你的列表返回在data集合中
                            layui.each(res.data, function(index, item){
                                lis.push('<li>\n' +
                                    '                <div class="link">\n' +
                                    '                    <i class="ico"></i>\n' +
                                    '                    <span>'+item.title+'</span>\n' +
                                    '                </div>\n' +
                                    '                <ul class="submenu">\n'+item.content+
                                    '                </ul>\n' +
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



    });
</script>
<?php $this->endBlock()?>
