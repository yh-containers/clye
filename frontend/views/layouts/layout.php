<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="maximum-scale=1.0,minimum-scale=1.0,user-scalable=0,width=device-width,initial-scale=1.0" />
    <title><?\Yii::$app->name?></title>
    <meta name="keywords" content="<?\Yii::$app->name?>">
    <meta name="description" content="<?\Yii::$app->name?>">
    <link rel="stylesheet" type="text/css" href="/assets/css/style.css" />
    <link rel="stylesheet" type="text/css" href="/assets/css/css.css" />
    <link rel="stylesheet" type="text/css" href="/assets/css/mui.min.css">
    <script type="text/javascript" src="/assets/js/jquery.min.js"></script>
    <script type="text/javascript" src="/assets/js/mui.min.js"></script>

</head>

<?php if (isset($this->blocks['style'])): ?>
    <?= $this->blocks['style'] ?>

<?php endif; ?>

<body>

<?php if (isset($this->blocks['content'])): ?>
    <?= $this->blocks['content'] ?>
<?php endif; ?>


<script type="text/javascript" src="/assets/js/common.js"></script>

</body>
</html>

<?php if (isset($this->blocks['script'])): ?>
    <?= $this->blocks['script'] ?>

<?php endif; ?>
