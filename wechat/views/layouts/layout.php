<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="maximum-scale=1.0,minimum-scale=1.0,user-scalable=0,width=device-width,initial-scale=1.0" />
    <title><?=empty($this->title)?\Yii::$app->name:$this->title?></title>
    <meta name="keywords" content="<?=\Yii::$app->name?>">
    <meta name="description" content="<?=\Yii::$app->name?>">
    <link rel="stylesheet" type="text/css" href="<?=\Yii::getAlias('@assets')?>/assets/css/style.css" />
    <link rel="stylesheet" type="text/css" href="<?=\Yii::getAlias('@assets')?>/assets/css/css.css" />
    <link rel="stylesheet" type="text/css" href="<?=\Yii::getAlias('@assets')?>/assets/css/mui.min.css">
    <script type="text/javascript" src="<?=\Yii::getAlias('@assets')?>/assets/js/jquery.min.js"></script>
    <script type="text/javascript" src="<?=\Yii::getAlias('@assets')?>/assets/js/mui.min.js"></script>
    <link rel="stylesheet" href="/assets/layui-v2.4.5/css/layui.css">
    <script src="/assets/layui-v2.4.5/layui.js"></script>
</head>

<?php if (isset($this->blocks['style'])): ?>
    <?= $this->blocks['style'] ?>

<?php endif; ?>

<body class="<?=isset($this->params['body_class'])?$this->params['body_class']:''?>" >

<?php if (isset($this->blocks['content'])): ?>
    <?= $this->blocks['content'] ?>
<?php endif; ?>



</body>
</html>

<script type="text/javascript" src="<?=\Yii::getAlias('@assets')?>/assets/js/common.js"></script>
<script>
    var layer;
    layui.use(['layer'],function(){
        layer = layui.layer;
    })
</script>
<?php if (isset($this->blocks['script'])): ?>
    <?= $this->blocks['script'] ?>
<?php endif; ?>
