<?php
return yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/rules.php'),
    require(__DIR__ . '/news.php'),
    require(__DIR__ . '/activity.php'),
    require(__DIR__ . '/user.php'),
    require(__DIR__ . '/sports.php'),
    require(__DIR__ . '/auth.php'),
    require(__DIR__ . '/training.php'),
    require(__DIR__ . '/wsaf.php'),
    require(__DIR__ . '/referee.php'),
    require(__DIR__ . '/venue.php')
);
