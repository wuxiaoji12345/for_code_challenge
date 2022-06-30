<?php
namespace common\config;
/**
 * Created by wayne.
 * Date: 2019/1/10
 * Time: 2:17 PM
 */
class Connection extends \yii\db\Connection
{
    static $sqltime;
    public function createCommand($sql = null, $params = array()) {
        $createCommand = parent::createCommand($sql, $params);

        //        if($_SERVER['REQUEST_URI'] == "/user/login") {
//            $rawSql = $createCommand->getRawSql();
//
//            $path = \Yii::$app->getRuntimePath();
//            $logfile = $path.'/'.'sqltime.log';
//            $myfile = fopen($logfile, "a+");
//
//            if(empty($this::$sqltime)) {
//                $showtime = sprintf("%.3f", microtime(true));
//                $this::$sqlstart = $showtime;
//                $this::$sqltime = $showtime;
//                fwrite($myfile, $showtime."\t\t".$rawSql . "\r\n");
//
//            } else {
//                $time = sprintf("%.3f", microtime(true));
//                $showtime = sprintf("%.3f", $time - $this::$sqltime);
//                $totaltime = sprintf("%.3f", $time - $this::$sqlstart);
//                $this::$sqltime = $time;
//                fwrite($myfile, "(".$showtime.",".$totaltime.")\r\n\t\t".$rawSql."\r\n");
//
//            }
//
//            fclose($myfile);
//        }

        if(isset($_REQUEST['__debug'])) {
            $rawSql = $createCommand->getRawSql();

            if(empty($this::$sqltime)) {
                $showtime = sprintf("%.3f", microtime(true));
                $this::$sqltime = $showtime;
                echo $showtime."&nbsp;&nbsp;&nbsp;&nbsp;".$rawSql . "<br>";
            } else {
                $time = sprintf("%.3f", microtime(true));
                $showtime = sprintf("%.3f", $time - $this::$sqltime);
                $this::$sqltime = $time;
                echo $showtime."<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$rawSql."</br>";
            }

        }
        return $createCommand;
    }
}