<?php
$this->title='个人中心';
$this->params = array_merge($this->params,[
]);

?>

<?php $this->beginBlock('content')?>
<div class="user-info">
    <div class="user-bg">
        <div class="list-item-middle">
            <div class="media-list-item-inner">
                <a href="<?=\yii\helpers\Url::to(['info'])?>" class="clearfix">
                    <div class="list-item-media">
                        <img src="<?=$user_model['face']?>" >
                    </div>
                    <div class="list-item-text">
                        <div class="name"><?=$user_model['username']?></div>
                        <div class="info"><?=substr_replace($user_model['phone'],'****',3,4)?></div>
                    </div>
                </a>
            </div>
        </div>
        <div class="list-item-nav">
            <div class="user-nav clearfix">
                <div class="col-xs-4">
                    <a href="<?=\yii\helpers\Url::to(['collect'])?>">
                        <div class="num"><?=$col_num?></div>
                        <div class="label">收藏</div>
                    </a>
                </div>
                <div class="col-xs-4">
                    <a href="<?=\yii\helpers\Url::to(['address'])?>">
                        <div class="icon icon-add"></div>
                        <div class="label">地址管理</div>
                    </a>
                </div>
                <div class="col-xs-4">
                    <a href="<?=\yii\helpers\Url::to(['info'])?>">
                        <div class="icon icon-edit"></div>
                        <div class="label">编辑资料</div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="user-vip">
        <div class="grade">
            游客
        </div>
        <a href="<?=\yii\helpers\Url::to(['up-page'])?>" class="apply">点击申请升级</a>
    </div>
</div>
<div class="container">
    <section class="content-grid margin-b-15">
        <div class="user-title">
            <h4>我的订单</h4>
            <a href="<?=\yii\helpers\Url::to(['order/index'])?>" class="mui-push-right">全部订单</a>
        </div>
        <div class="row user-list">
            <div class="col-xs-3">
                <a href="<?=\yii\helpers\Url::to(['order/index','state'=>1])?>">
                    <div class="icon icon-1"><?=$wait_num?'<span class="badge">'.$wait_num.'</span>':''?></div>
                    <div class="gird-lable">待付款</div>
                </a>
            </div>
            <div class="col-xs-3">
                <a href="<?=\yii\helpers\Url::to(['order/index','state'=>2])?>">
                    <div class="icon icon-2"><?=$produce_num?'<span class="badge">'.$produce_num.'</span>':''?></div>
                    <div class="gird-lable">生产中</div>
                </a>
            </div>
            <div class="col-xs-3">
                <a href="<?=\yii\helpers\Url::to(['order/index','state'=>3])?>">
                    <div class="icon icon-3"><?=$send_num?'<span class="badge">'.$send_num.'</span>':''?></div>
                    <div class="gird-lable">待发货</div>
                </a>
            </div>
            <div class="col-xs-3">
                <a href="<?=\yii\helpers\Url::to(['order/index','state'=>4])?>">
                    <div class="icon icon-4"><?=$receive_num?'<span class="badge">'.$receive_num.'</span>':''?></div>
                    <div class="gird-lable">待收货</div>
                </a>
            </div>
        </div>
    </section>
    <section class="content-grid margin-b-15">
        <div class="row user-list">
            <a class="mui-navigate-right" href="<?=\yii\helpers\Url::to(['data'])?>">
                <div class="icon icon-5"></div>
                <div class="gird-lable">会员信息查看</div>
            </a>
            <a class="mui-navigate-right" href="<?=\yii\helpers\Url::to(['up'])?>">
                <div class="icon icon-6"></div>
                <div class="gird-lable">会员升级申请</div>
            </a>
            <a class="mui-navigate-right" href="<?=\yii\helpers\Url::to(['mine/contract'])?>">
                <div class="icon icon-7"></div>
                <div class="gird-lable">合同管理</div>
            </a>
            <a class="mui-navigate-right" href="<?=\yii\helpers\Url::to(['article/problem'])?>">
                <div class="icon icon-8"></div>
                <div class="gird-lable">常见问题</div>
            </a>

        </div>
    </section>
</div>

<?=\wechat\widgets\Footer::widget(['current_active'=>'mine'])?>
<?php $this->endBlock()?>

<?php $this->beginBlock('script')?>
<script>

</script>

<?php $this->endBlock()?>
