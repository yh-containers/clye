<?php

$this->params = [
    'crumb'          => ['会员管理','用户列表'],
];
?>
<?php $this->beginBlock('content')?>



    <div class="box">
        <div class="box-header with-border">
            <div class="row">
                <div class="col-sm-8"></div>
                <div class="col-sm-4">
                    <form>
                        <div class="input-group col-sm-6">
                            <input type="text" name="keyword" value="<?=$keyword?>" placeholder="用户名/手机号" class="form-control">
                            <span class="input-group-btn">
                          <button type="submit" class="btn btn-info btn-flat">搜索</button>
                        </span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <table class="table table-bordered">

                <thead>
                <tr>
                    <th width="20">#</th>
                    <th width="120">用户名</th>
                    <th width="100">用户类型</th>
                    <th width="100">手机号</th>
                    <th width="100">邮箱</th>
                    <th width="60">性别</th>
                    <th width="200">所属地区</th>
                    <th width="200">公司名称</th>
                    <th width="80">联系人</th>
                    <th width="80">创建时间</th>
                    <th width="40">状态</th>
                    <th width="80">操作</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($list as $key=>$vo) {?>
                    <tr>
                        <td><?=$key+1?></td>
                        <td><?=$vo['username']?></td>
                        <td><?=\common\models\User::getUserTypePoint(null,'name',$vo['cg_type'])?></td>
                        <td><?=$vo['phone']?></td>
                        <td><?=$vo['email']?></td>
                        <td><?=\common\models\User::getSexInfo($vo['sex']) ?></td>
                        <td>
                            <p>地区:<?=$vo['linkProvince']['name'].' '.$vo['linkCity']['name']?> </p>
                        </td>

                        <td><?=$vo['company_name']?> </td>
                        <td><?=$vo['contacts']?> </td>
                        <td><?=$vo['create_time']?date('Y-m-d',$vo['create_time']):''?></td>
                        <td><?=\common\models\Goods::getStatusName($vo['status'])?></td>
                        <td>
                            <a href="<?=\yii\helpers\Url::to(['add','id'=>$vo['id']])?>">编辑</a>
                            <a href="<?=\yii\helpers\Url::to(['detail','id'=>$vo['id']])?>">查看</a>
                        </td>
                    </tr>
                <?php }?>
                </tbody>
            </table>
        </div>
        <!-- /.box-body -->
        <div class="box-footer clearfix">
            <?= \yii\widgets\LinkPager::widget(['pagination'=>$pagination])?>
        </div>
    </div>


<?php $this->endBlock()?>