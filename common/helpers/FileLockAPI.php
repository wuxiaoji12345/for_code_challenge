<?php

/**
 * Created by wayne.
 * Date: 2019/1/8
 * Time: 4:48 PM
 */
namespace common\helpers;

use Yii;
class FileLockAPI
{
    static public function getFileLock($lockname) {
        $fp = fopen($lockname, "w");
        if(empty($fp)) {
            return false;
        } else {
            if (!flock($fp, LOCK_EX + LOCK_NB)) {
                return false;
            }
            return $fp;
        }
    }

    static public function getActionLock() {
        $path = Yii::$app->getRuntimePath();
        $lockname = Yii::$app->controller->id.'_'.Yii::$app->controller->action->id.'.lock';
        $lockfile = $path.'/'.$lockname;

        $fp = fopen($lockfile, "w");
        if(empty($fp)) {
            return false;
        } else {
            if (!flock($fp, LOCK_EX + LOCK_NB)) {
                return false;
            }
            return $fp;
        }
    }

    static public function unlockFile($fp) {
        if(empty($fp)) return;
        flock($fp, LOCK_UN);
        fclose($fp);
    }
}