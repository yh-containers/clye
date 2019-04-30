<select class="form-control" name="province" style="display: inline-block; width: unset;">
    <?php foreach($province_list as $vo){?>
        <option value="<?=$vo['id']?>" <?=$province==$vo['id']?'selected':''?> ><?=$vo['name']?></option>
    <?php }?>

</select>

<select class="form-control " name="city" style="display: inline-block; width: unset;">
    <?php foreach($city_list as $vo){?>
        <option value="<?=$vo['id']?>" <?=$city==$vo['id']?'selected':''?> ><?=$vo['name']?></option>
    <?php }?>
</select>

<script>
    $(function(){
        $("select[name='province']").change(function(){
            var index = layui.layer.load(3)
            $.get('<?=\yii\helpers\Url::to(['index/location'])?>',{id:$(this).val()},function(result){
                layui.layer.close(index)
                var html ='';
                if(typeof result==='object'){
                    result.map(function(item,index){
                        html +='<option value="'+item.id+'" >'+item.name+'</option>'
                    })
                }
                $("select[name='city']").html(html)
            })
        })
    })
</script>