<?php
$this->title='地址操作';
$this->params = array_merge($this->params,[
        'body_class' => 'bg'
]);

?>

<?php $this->beginBlock('content')?>
<div class="header">
    <a class="back" href="javascript:history.go(-1)"></a>
    <h4><?=$this->title?></h4>
    <div class="right delete" id="addDelete" data-id="<?=$model['id']?>">删除</div>
</div>
<div class="main">
    <form id="form">
        <input type="hidden" name="<?=\Yii::$app->request->csrfParam?>" value="<?= \Yii::$app->request->csrfToken ?>"/>
        <input type="hidden" name="id" value="<?=$model['id']?>" >
    <div class="add_address">
        <ul>
            <li>
                <label>收件人：</label>
                <input type="text" name="username" value="<?=$model['username']?>" >
            </li>
            <li>
                <label>手机号码：</label>
                <input type="tel" name="phone" value="<?=$model['phone']?>" >
            </li>
            <li>
                <label>所在区域：</label>
                <input type="text" class="cell-input module-select " name="addr"  value="<?=$model['addr']?>" id="J_Address" readonly="readonly" placeholder="请选择收货地址">
            </li>
            <li>
                <label>详细地址：</label>
                <textarea placeholder="请输入详细地址" class="adinfo" name="addr_extra" rows="2"><?=$model['addr_extra']?></textarea>
            </li>
            <li>
                <div class="set_default">
                    <span>设置为默认地址</span>
                </div>
                <div class="set_checkbox">
                    <label>
                        <input name="is_default" type="checkbox" <?=$model['is_default']?'checked':''?> >
                        <span class="switch"></span>
                    </label>
                </div>
            </li>
        </ul>
        <div class="fix_btn"><input type="button" name="" id="submit" value="保存"></div>
    </div>
    </form>
</div>
<?php $this->endBlock()?>

<?php $this->beginBlock('script')?>
<script type="text/javascript" src="<?=\Yii::getAlias('@assets')?>/assets/js/ydui.citys.js"></script>
<script type="text/javascript" src="<?=\Yii::getAlias('@assets')?>/assets/js/ydui.js"></script>
<script>
    $(function () {
        $("#submit").click(function(){
            var index=layui.layer.load(3)
            $.post($("#form").attr('action'),$("#form").serialize(),function(result){
                layer.close(index)
                layer.msg(result.msg)
            })
        })

        $("#addDelete").click(function(){
            var id= $(this).data('id')
            layer.confirm('是否删除该数据',function(){
                var index = layer.load(3)
                $.get("<?=\yii\helpers\Url::to(['mine/address-del'])?>",{id:id},function(result){
                    layer.close(index);
                    layer.msg(result.msg)
                    if(result.code==1){
                        setTimeout(function(){window.history.back()},1000)
                    }
                })
            })
        })
    })
    // 地址选择
    ! function() {
        var $target = $('#J_Address');
        $target.citySelect();
        $target.on('click', function(event) {
            event.stopPropagation();
            $target.citySelect('open');
        });
        $target.on('done.ydui.cityselect', function(ret) {
            $(this).val(ret.provance + ' ' + ret.city + ' ' + ret.area);
        });
    }();
</script>

<?php $this->endBlock()?>
