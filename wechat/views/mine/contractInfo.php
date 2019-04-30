

<div class="header">
    <?php if(isset($contract_model)) { ?>
        <a class="back" href="javascript:history.go(-1)"></a>
    <?php }?>
    <h4><?=$this->title?></h4>
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
        </ul>
        <div class="contract_text">
            <p>合同总金额：<font>¥<?=isset($money)?$money:0.00?></font></p>
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
