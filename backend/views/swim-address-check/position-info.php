<?php
use yii\helpers\Html;
use backend\assets\AppAsset;

AppAsset::register($this);
$this->title = '位置信息';

/* @var $this \yii\web\View */
/* @var $data string */
?>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<?php $this->beginBody() ?>
    <div>
        <img src="data:image/png;base64,<?= $data ?>" />
    </div>
<?php $this->endBody() ?>
</body>
</html>