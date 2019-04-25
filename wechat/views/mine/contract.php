<?php
$this->title='合同管理';
$this->params = array_merge($this->params,[
]);

?>

<?php $this->beginBlock('content')?>

<div class="header">
    <a class="back" href="javascript:history.go(-1)"></a>
    <h4><?=$this->title?></h4>
</div>

<div class="main">
    <div class="contract">
        <div class="num">您共有<font><?=$num?></font>份合同</div>
        <div class="contract_list" id="demo">

        </div>

    </div>

</div>

<?php $this->endBlock()?>

<?php $this->beginBlock('script')?>
<script>

    $(function () {
        var url = '<?=\yii\helpers\Url::to(['mine/contract-list'])?>';
        var detail_url = '<?=\yii\helpers\Url::to(['mine/contract-addr'])?>';
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
                            lis.push('<div class="item">\n' +
                                '                <a class="mui-navigate-right" href="'+detail_url+(detail_url.indexOf('?')===-1?'?':'&')+'id='+item.id+'">\n' +
                                '                    <div class="name">'+item.name+'</div>\n' +
                                '                    <div class="date">签订日期：'+item.date+'</div>\n' +
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

    });

</script>

<?php $this->endBlock()?>
