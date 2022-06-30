<?php
use yii\helpers\Html;
use backend\assets\AppAsset;

AppAsset::register($this);
$this->title = '打印';

/* @var $this \yii\web\View */
/* @var $score array */
/* @var $itemName string */
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
                        <th class="text-center" colspan="7">
                            <h3><?= $itemName; ?></h3>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="width: 100px;"><b>排序</b></td>
                        <td style="width: 100px;"><b>组次</b></td>
                        <td style="width: 100px;"><b>泳道</b></td>
                        <td style="width: 100px;"><b>姓名</b></td>
                        <td style="width: 100px;"><b>代表队</b></td>
                        <td style="width: 100px;"><b>成绩</b></td>
                        <td style="width: 100px;"><b>备注</b></td>
                    </tr>

                    <?php foreach ($score as $idx => $scoreData) { ?>
                    <tr>
                        <td class="text-center" ><b><?= $idx + 1; ?></b></td>
                        <td class="text-center"><b><?= $scoreData['groupnum']; ?></b></td>
                        <td class="text-center" ><b><?= $scoreData['lane']; ?></b></td>
                        <td class="text-center"><b><?= $scoreData['enrollname']; ?></b></td>
                        <td class="text-center" ><b><?= $scoreData['unit']; ?></b></td>
                        <td class="text-center"><b><?= $scoreData['score_s']; ?></b></td>
                        <td class="text-center" ><b><?= $scoreData['remark']; ?></b></td>
                    </tr>
                    <?php } ?>

                </tbody>
            </table>
             </section>
        </div>
<?php $this->endBody() ?>
</body>
</html>