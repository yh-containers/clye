<?php
    $this->title = '用户管理';
    $this->params = [
            'crumb'          => ['用户管理','用户详情'],
    ];
?>
<?php $this->beginBlock('content')?>


    <div class="box">
        <div class="box-header with-border">
            <h3>用户基本资料</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="row">
                <div class="col-sm-9">
                    <table class="layui-table"  lay-size="lg">
                        <colgroup>
                            <col width="120">
                            <col width="180">
                            <col width="120">
                            <col width="180">
                            <col width="120">
                            <col width="180">
                            <col width="120">
                            <col width="180">
                        </colgroup>

                        <tbody>
                        <tr>
                            <td>用户名</td>
                            <td><?=$model['username']?></td>
                            <td>用户类型</td>
                            <td><?=$model['type']?></td>
                            <td></td>
                            <td></td>
                            <td>状态</td>
                            <td><?=\common\models\Goods::getStatusName($model['status'])?></td>
                        </tr>
                        <tr>
                            <td>手机号码</td>
                            <td><?=$model['phone']?></td>
                            <td>邮箱</td>
                            <td><?=$model['email']?></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>行政区</td>
                            <td><?=$model['linkAreaInfo']['name']?></td>
                            <td>地区</td>
                            <td colspan="3"><?=$model['linkProvince']['name'].' '.$model['linkCity']['name']?></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>公司名</td>
                            <td><?= $model['company_name']?></td>
                            <td>联系人</td>
                            <td><?= $model['contacts']?></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>注册时间</td>
                            <td><?= $model['create_time']?date('Y-m-d H:i:s',$model['create_time']):''?></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>

                        </tbody>
                    </table>
                </div>
                <div class="col-sm-4">

                </div>
            </div>

        </div>
        <!-- /.box-body -->

    </div>


<?php $this->endBlock()?>