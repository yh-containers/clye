<?php
$this->title='申请升级';
$this->params = array_merge($this->params,[
]);

?>

<?php $this->beginBlock('content')?>
<form id="form">
    <input type="hidden" name="<?=\Yii::$app->request->csrfParam?>" value="<?= \Yii::$app->request->csrfToken ?>"/>
    <input type="hidden" name="addr_id" value="<?=$model_addr['id']?>"/>
    <input type="hidden" name="gid" value="<?=$gid?>"/>
    <input type="hidden" name="num" value="<?=$num?>"/>
<div id="order-info">
<div class="header">
    <a class="back" href="javascript:history.go(-1)"></a>
    <h4>确认订单</h4>
</div>

<div class="main">
    <div id="pageOrder" class="order-detail">
        <div class="addr">
            <div class="addr-list">
                <?php if(empty($model_addr)){?>
                    <div id="addr-edit">
                        <a href="<?=\yii\helpers\Url::to(['mine/address-addr','origin'=>'order/info'])?>">
                            <span class="address-fm">您的收货地址为空，点击添加收货地址</span>
                        </a>
                    </div>
                <?php }else{?>
                    <div id="addr-default">
                        <a href="<?=\yii\helpers\Url::to(['mine/address','origin'=>'order/info'])?>">
                            <div class="contact-name"><?=$model_addr['username']?></div>
                            <div class="contact-mobile"><?=substr_replace($model_addr['phone'],'****',3,4)?></div>
                            <div class="contact-addr"><?=$model_addr['addr'].'  '.$model_addr['addr_extra']?></div>
                        </a>
                    </div>
                <?php }?>
            </div>
        </div>
        <div id="prod-detail">
            <div class="prod-info clearfix">
                <div class="shop-name">
                    <h4>商品清单</h4>
                </div>
                <?php foreach($goods_info as $vo) {?>
                <div class="row clearfix">
                    <img src="<?=\common\models\Goods::getCoverImg($vo['img'])?>">
                    <div class="prod-desc">
                        <div class="name"><?=$vo['name']?></div>
                        <div class="level clearfix">
                            <span class="price">¥<?=$vo['price']?></span>
                            <span class="mun">X<?=$vo['buy_num']?></span>
                        </div>
                        <div class="sku_info">
                            <span>在线提交订单</span>
                            <span>线下进行支付</span>
                            <span>专属顾问服务</span>
                        </div>
                    </div>
                </div>
                <?php }?>
            </div>

            <div class="choose-operate">
                <div class="oItem">
                    <a href="javascript:;" id="wirte-contract">
                        <div class="itemName">合同填写</div>
                        <div class="itemInfo oneLine">
                            <p class="iiItems selected">去填写</p>
                        </div>
                        <i class="oIcon oArrow"></i>
                    </a>
                </div>
            </div>

            <div class="buy_section">
                <ul class="buy_chart">
                    <li class="buy_chart_item">
                        <p class="text">商品金额：</p>
                        <p class="price">¥<em><?=$money['money']?></em></p>
                    </li>
                    <li class="buy_chart_item">
                        <p class="text">运费：</p>
                        <p class="price">¥<em><?=$money['freight_money']?></em></p>
                    </li>
                    <li class="buy_chart_item">
                        <p class="text">税费</p>
                        <p class="price">¥<em><?=$money['taxation_money']?></em></p>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="footer">
        <div class="shop-bar-tab">
            <div class="bar-tab-item pay-infor">
                <div class="pay-text">
                    合计：<span class="price">¥<em><?=$money['pay_money']?></em></span>
                </div>
            </div>
            <a class="bar-tab-item bg-danger text-white" id="confirm" href="javascript:;" class="" style="width: 6rem;">提交订单</a>
        </div>
    </div>
</div>
</div>


<div style="display: none" id="contract">
    <div class="header">
        <h4>填写合同</h4>
    </div>

    <div class="main">
        <div class="contract">
            <ul>
                <li>
                    <div class="label">公司名称</div>
                    <div class="con">
                        <input type="text" name="contract[name]" value="" placeholder="请填写公司名称" autocomplete="off">
                    </div>
                </li>
                <li>
                    <div class="label">地址</div>
                    <div class="con">
                        <input type="text" name="contract[addr]" value="" placeholder="请填写公司地址" autocomplete="off">
                    </div>
                </li>
                <li>
                    <div class="label">法人代表</div>
                    <div class="con">
                        <input type="text" name="contract[f_name]" value="" placeholder="请填写法人代表" autocomplete="off">
                    </div>
                </li>
                <li>
                    <div class="label">委托代理人</div>
                    <div class="con">
                        <input type="text" name="contract[w_name]" value="" placeholder="请填写委托代理人" autocomplete="off">
                    </div>
                </li>
                <li>
                    <div class="label">付款方式</div>
                    <div class="con">
                        <select name="contract[pay_way]">
                            <?php foreach($pay_way as $key=>$vo) {?>
                            <option value="<?=$key?>"><?=$vo['name']?></option>
                            <?php }?>
                        </select>
                    </div>
                </li>
            </ul>
            <div class="contract_text">
                <p>合同总金额：<font>¥<?=$money['pay_money']?></font></p>
            </div>
        </div>
        <div class="footer">
            <div class="shop-bar-tab">
                <div class="bar-tab-item pay-infor">
                    <div class="desc-text"><a href="contract-det.html">点击查看合同详情</a></div>
                </div>
                <a class="bar-tab-item bg-danger text-white" href="#" style="width: 6rem;" id="sure-contract">确定</a>
            </div>
        </div>
    </div>
</div>
</form>
<?php $this->endBlock()?>

<?php $this->beginBlock('script')?>
<script>
    $(function () {
        //填合同
        $("#wirte-contract").click(function(){
            $("#order-info").hide();
            $("#contract").show();
        })
        $("#sure-contract").click(function(){
            $("#order-info").show();
            $("#contract").hide();
        })
        $("#confirm").click(function(){
            $.post("<?=\yii\helpers\Url::to(['order/confirm'])?>",$("#form").serialize(),function(result){
                console.log(result)
            })
        })
    });
</script>

<?php $this->endBlock()?>
