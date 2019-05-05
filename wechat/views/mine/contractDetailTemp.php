

<div class="header">
    <?php if(isset($contract_model)) { ?>
        <a class="back" href="javascript:history.go(-1)"></a>
    <?php }?>
    <h4><?=isset($title)?$title:$this->title?></h4>
</div>

<div class="main">
    <div class="contract">
        <ul>
            <li>
                <div class="label">公司名称</div>
                <div class="con">
                    <input type="text" name="contract[name]" value="<?=isset($contract_model)?$contract_model['name']:''?>" placeholder="请填写公司名称" autocomplete="off">
                </div>
            </li>
            <?php if(isset($contract_model)){?>
            <li>
                <div class="label">合同编号</div>
                <div class="con">
                    <input type="text" readonly value="<?=isset($contract_model)?$contract_model['no']:''?>" placeholder="合同编号" autocomplete="off">
                </div>
            </li>
            <li>
                <div class="label">创建时间</div>
                <div class="con">
                    <input type="text" readonly value="<?=isset($contract_model)?$contract_model['createTime']:''?>" placeholder="合同编号" autocomplete="off">
                </div>
            </li>
            <?php }?>
            <li>
                <div class="label">地址</div>
                <div class="con">
                    <input type="text" name="contract[addr]" value="<?=isset($contract_model)?$contract_model['addr']:''?>" placeholder="请填写公司地址" autocomplete="off">
                </div>
            </li>
            <li>
                <div class="label">法人代表</div>
                <div class="con">
                    <input type="text" name="contract[f_name]" value="<?=isset($contract_model)?$contract_model['f_name']:''?>" placeholder="请填写法人代表" autocomplete="off">
                </div>
            </li>
            <li>
                <div class="label">委托代理人</div>
                <div class="con">
                    <input type="text" name="contract[w_name]" value="<?=isset($contract_model)?$contract_model['w_name']:''?>" placeholder="请填写委托代理人" autocomplete="off">
                </div>
            </li>
            <li>
                <div class="label">付款方式</div>
                <div class="con">
                    <select name="contract[pay_way]">
                        <?php $pay_way = \common\models\Order::getPayWay(); foreach($pay_way as $key=>$vo) {?>
                            <option value="<?=$key?>" <?=isset($contract_model)?($key==$contract_model['pay_way']?'selected':''):''?>><?=$vo['name']?></option>
                        <?php }?>
                    </select>
                </div>
            </li>
            <?php if(isset($contract_model)){?>
                <li>
                    <div class="label">上传附件</div>
                    <div class="con">
                        <a href="javascript:;" id="upload">上传附件</a>
                    </div>
                </li>
                <li>
                    <div class="label">有效期</div>
                    <div class="con">
                        <font><?=$contract_model['start_time']?date('Y-m-d',$contract_model['start_time']):''?></font> 至
                        <font><?=$contract_model['end_time']?date('Y-m-d',$contract_model['end_time']):''?></font>
                    </div>
                </li>
            <?php }?>
        </ul>
        <div class="contract_text">
            <p>合同总金额：<font>¥<?=isset($money)?$money:0.00?></font></p>
        </div>
    </div>
    <div class="footer">
        <div class="shop-bar-tab">
            <div class="bar-tab-item pay-infor">
                <div class="desc-text"><a href="<?=\yii\helpers\Url::to(['mine/contract-content','id'=>isset($contract_model)?$contract_model['id']:''])?>">点击查看合同详情</a></div>
            </div>
            <?php if(!isset($contract_model)){?>
            <a class="bar-tab-item bg-danger text-white" href="#" style="width: 6rem;" id="sure-contract">确定</a>
            <?php }?>
        </div>
    </div>
</div>
<?php if(isset($contract_model)){?>
<script>
    var upload_url = '<?=\yii\helpers\Url::to(['mine/contract-upload-file'])?>'
    var csrf_key = '<?=\Yii::$app->request->csrfParam?>'
    var csrf_token = '<?=\Yii::$app->request->csrfToken?>'
    var upoad_data = {}
    upoad_data[csrf_key]=csrf_token;
    upoad_data['id']='<?=$contract_model['id']?>';
    layui.use('upload', function(){
        var upload = layui.upload;

        //执行实例
        var uploadInst = upload.render({
            elem: '#upload' //绑定元素
            ,accept: 'file'
            ,url: upload_url //上传接口
            ,data:upoad_data
            ,before: function(obj){ //obj参数包含的信息，跟 choose回调完全一致，可参见上文。
                layer.load(); //上传loading
            }
            ,done: function(res){
                var item = this.item;
                layer.closeAll('loading'); //关闭loading
                layer.msg(res.msg)
            }
            ,error: function(){
                layer.closeAll('loading'); //关闭loading
                layer.msg('上传异常');
            }
        });
    });
</script>
<?php }?>
