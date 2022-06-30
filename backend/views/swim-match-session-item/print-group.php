<?php
use yii\helpers\Html;
use backend\assets\AppAsset;

AppAsset::register($this);
$this->title = '打印';

/* @var $this \yii\web\View */
/* @var $data array */
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
    <div class="wrapper">
        <div class="content-wrapper">
            <section class="content">
            <table  class="table table-striped table-bordered" >
                <thead>
                    <tr >
                        <th class="text-center" colspan="<?= $data['maxLane'] + 1; ?>">
                            <h3><?= $data['datetime']; ?></h3>
                        </th>
                    </tr>
                    <tr class="info">
                        <th class="text-center " colspan="<?= $data['maxLane'] + 1; ?>">
                            <?= $data['title'] ?>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="width: 100px;"><b>分组/泳道</b></td>
                        <?php for ($i = 1; $i <= $data['maxLane']; $i++) { ?>
                        <td class="text-center"><b><?= $i ?> </b></td>
                        <?php } ?>

                    </tr>

                    <?php foreach ($data['group'] as $groupName => $groupData) { ?>
                    <tr>
                        <td class="text-center" ><b><?= $groupName ?></b></td>
                        <?php foreach ($groupData as $enrollName) { ?>
                            <td class="text-center"><b><?= $enrollName ?></b></td>
                        <?php } ?>
                    </tr>
                    <?php } ?>

                </tbody>
            </table>
             </section>
        </div>
<?php $this->endBody() ?>
</body>
</html>