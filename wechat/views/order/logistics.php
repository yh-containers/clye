<?php
$this->title='物流信息';
$this->params = array_merge($this->params,[
]);

?>

<?php $this->beginBlock('content')?>

<div class="header">
    <a class="back" href="javascript:history.go(-1)"></a>
    <h4>物流信息</h4>
</div>

<div class="main">
    <div class="prod-order">
        <div class="prod-info clearfix">
            <div class="row clearfix">
                <div class="prod-desc">
                    <p>承运公司：<span><?=$model['linkOrderLogistics']['company']?></span></p>
                    <p>运单编号：<span><?=$model['linkOrderLogistics']['no']?></span></p>
                </div>
            </div>
        </div>
    </div>
    <div class="logistics">
        <ul>
            <?php if(!empty($model['linkOrderLogs'])) foreach ($model['linkOrderLogs'] as $vo){?>
            <li>
                <div class="logistics_track">
                    <p class="track"><?=$vo['intro']?></p>
                    <p class="date"><?=$vo['create_time']?></p>
                </div>
            </li>
            <?php }?>

        </ul>
    </div>
</div>

<?php $this->endBlock()?>

