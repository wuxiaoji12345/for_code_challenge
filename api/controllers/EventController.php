<?php

/**
 * Created by wayne.
 * Date: 2019/1/30
 * Time: 5:33 PM
 */

namespace api\controllers;


use api\dbmodels\dbEvent;
use api\dbmodels\dbMatch;
use api\dbmodels\dbScore;
use api\models\Banners;
use api\models\Match;
use common\models\Event;
use common\models\EventType;
use yii\helpers\ArrayHelper;

class EventController extends Controller
{


    /**
     * by lito from mcloud fapi
     */
    public $modelClass = "common\models\Event";

    public function actionView($id)
    {
        $tn = Event::tableName();
        $query = $this->modelClass::find();
        $query->andWhere([$this->modelClass::tableName() . '.id' => $id]);
        if (!empty($this->filter)) {
            $query->andWhere($this->filter);
        }
        $query->select(
            [
                $tn . '.id',
                $tn . '.title',
                $tn . '.imgurl',
                $tn . ".province",
                $tn . ".city",
                $tn . ".district",
                $tn . '.address',
                $tn . ".start_time",
                $tn . ".end_time",
                $tn . ".reg_start_time",
                $tn . ".reg_end_time",
                $tn . ".reg_end_time",
                $tn . ".intro",
                $tn . ".tips",
                $tn . ".latitude",
                $tn . ".longitude",
                $tn . ".qrcode"
            ]
        );
        $query->joinWith([
            'registerType' => function ($query) {
                $tn = EventType::tableName();
                $query->onCondition([$tn . ".status" => 1]);
                $query->select([
                    $tn . '.id',
                    $tn . '.matchid',
                    $tn . '.amount',
                    $tn . '.fees',
                    $tn . '.maxcount',
                    $tn . '.mincount',
                    $tn . '.needcheck',
                    $tn . '.notice',
                    $tn . '.type',
                    $tn . '.title',
                ]);
            },
        ]);
        $oldModel = clone $query->one();
        $data = $query->asArray()->one();
        $data['state']  =   Event::getStateInfo($oldModel);
        return self::dataOut($data);
    }



    public function actionIndex()
    {
        $categoryid = self::getJsonParam('categoryid', 0);
        $gid = self::getJsonParamErr('gid');
        $page = self::getJsonParam('page', 1);
        $keywords = self::getJsonParam('keywords', 1);
        $gid = $this->checkGid($gid);
        if (empty($gid)) {
            return self::dataOut(array(), $GLOBALS['errormsg'], self::GID_ERR);
        }

        $data = dbEvent::getGidEvent($gid, $categoryid, $page, $keywords);
        self::checkData($data, '暂无活动');
        return self::dataOut($data);
    }




    public function actionTiminglist()
    {
        $categoryid = self::getJsonParam('categoryid', 0);
        $gid = self::getJsonParamErr('gid');
        $page = self::getJsonParam('page', 1);
        $gid = $this->checkGid($gid);
        if (empty($gid)) {
            return self::dataOut(array(), $GLOBALS['errormsg'], self::GID_ERR);
        }

        $data = dbEvent::getTimingEvent($gid, $categoryid, $page);
        self::checkData($data, '暂无活动');
        return self::dataOut($data);
    }

    public function actionDetail()
    {
        $matchid    = self::getJsonParamErr('matchid', 0);
        $urid = self::getJsonParam('urid');
        $gid = self::getJsonParamErr('gid');
        $gid = $this->checkGid($gid);
        if (empty($gid)) {
            return self::dataOut(array(), $GLOBALS['errormsg'], self::GID_ERR);
        }

        $data = dbEvent::getEventDetail($matchid, $gid, $urid);
        self::checkData($data);
        return self::dataOut($data);
    }

    public function actionEventtypelist()
    {

        $matchid = self::getJsonParamErr('matchid');
        $gid = self::getJsonParamErr('gid');
        $gid = $this->checkGid($gid);
        if (empty($gid)) {
            return self::dataOut(array(), $GLOBALS['errormsg'], self::GID_ERR);
        }
        $data = dbEvent::getEventTypeList($matchid, $gid);

        if ($data == false) {
            return self::dataOut(array(), $GLOBALS['errormsg'], self::OTHER_ERR);
        }

        return self::dataOut($data);
    }

    public function actionEventintro()
    {
        $eventid = self::getJsonParamErr('eventid');
        $type = self::getJsonParam('type', 0);
        $query =  Event::find()
            ->andFilterWhere(['id' => $eventid]);

        if (empty($type)) {
            $query->select('intro');
            $data = $query->asArray()->one();
            $body = $data['intro'];
        } else {
            $query->select('disclaimer');
            $data = $query->asArray()->one();
            $body = $data['disclaimer'];
        }


        $start = '<html>
                <head>
                  <meta charset="utf-8">
                  <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0,user-scalable=0">
                  <style>
                    img{
                        width:auto !important;
                        max-width:100% !important;
                        height:auto !important;
                    }
                   </style> 
                    </head>
                   
                  <body>';

        $end = '<script type="text/javascript">
              window.onload = function() {
                var iframe = window.parent.document.getElementsByClassName("match-desc")[0];
                var height = Math.max(document.documentElement.scrollHeight, document.documentElement.offsetHeight, document.documentElement.clientHeight);
                if( iframe ) {
                  iframe.style.height = height + "px";
                }
              }
            </script></body></html>';

        echo $start . $body . $end;
        exit;
    }
}
