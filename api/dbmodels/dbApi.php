<?php

/**
 * Created by wayne.
 * Date: 2019/2/1
 * Time: 1:27 PM
 */

namespace api\dbmodels;



use api\models\MatchImageConfig;

class dbApi
{

    public static function getShareInfo($type,$matchid){

        $data = [];
        if($type == 1){

            $data = MatchImageConfig::find()->select('id, matchid, sourcetype, sourceurl, sourceid, sharetitle, sharedesc, sharelink, shareimg, values, total, title')
                ->andWhere(['matchid'=>$matchid])->asArray()->one();
        }
        return $data;

    }

}