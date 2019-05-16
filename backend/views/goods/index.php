<?php

$this->params = [
    'crumb'          => ['商品管理','商品列表'],
];
?>
<?php $this->beginBlock('content')?>



    <div class="box">
        <div class="box-header with-border">
            <div class="row">
                <div class="col-sm-8">
                    <a href="<?=\yii\helpers\Url::to(['add'])?>" class="btn bg-olive margin">新增</a>

                </div>
                <div class="col-sm-4">
                    <div class="col-sm-4">
                        <select name="cid" class="form-control">
                            <option value="">请选择分类</option>
                            <?php foreach($top_cate as $vo) {?>
                                <option value="<?=$vo['id']?>" <?=$vo['id']==$cid?'selected':''?>><?=$vo['name']?></option>
                            <?php }?>
                        </select>
                    </div>
                    <div class="col-sm-6">
                    <form>
                        <div class="input-group">
                            <input type="text" name="keyword" value="<?=$keyword?>" placeholder="商品名称" class="form-control">
                            <span class="input-group-btn">
                              <button type="submit" class="btn btn-info btn-flat">搜索</button>
                            </span>
                        </div>
                    </form>
                    </div>
                </div>

            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <table class="table table-bordered">

                <thead>
                <tr>
                    <th width="20">#</th>
                    <th width="200">商品名</th>
                    <th width="60">分类名</th>
                    <th width="60">价格<span style="color: red">(<?=\common\models\User::getUserTypePoint(null,'name',(int)\Yii::$app->controller->is_super_manager)?>)</span></th>
                    <th width="60">热门</th>
                    <th width="120">更新时间</th>
                    <th width="80">状态</th>
                    <th width="80">操作</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($list as $key=>$vo) {?>
                    <tr>
                        <td><?=$key+1?></td>
                        <td title="<?=$vo['name']?>">
                            <a href="<?=\yii\helpers\Url::to(['add','id'=>$vo['id']])?>"> <?=mb_strlen($vo['name'],'utf-8')>20?mb_substr($vo['name'],0,20,'utf-8').'...':$vo['name']?></a>
                        </td>
                        <td><?=$vo['linkCate']['name']?></td>
                        <td><?=$vo->getUserPrice(\Yii::$app->controller->user_model,\Yii::$app->controller->is_super_manager)?> </td>
                        <td><?=$vo['is_hot']?'是':'否'?> </td>
                        <td><?=$vo['updateTime']?></td>
                        <td><?=\common\models\Goods::getStatusName($vo['status'])?></td>
                        <td>
                            <a href="<?=\yii\helpers\Url::to(['add','id'=>$vo['id']])?>">编辑</a>
                            <a  href="javascript:;" onclick="$.common.del('<?= \yii\helpers\Url::to(['del','id'=>$vo['id']])?>','删除')" class="ml-5">  删除</a>
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
<?php $this->beginBlock('script')?>
    <script>
        $(function(){
            $("select[name='cid']").change(function(){
                var cid = $(this).val()
                var url = "<?=\yii\helpers\Url::to([''])?>"
                window.location.href=url+(url.indexOf('?')===-1?'?':'&')+'cid='+cid

            })
        })
    </script>
<?php $this->endBlock()?>