<?php

namespace backend\models;

use yii\db\ActiveRecord;

class AddressUserComment extends \common\models\AddressUserComment
{
    const STATUS_VALID = 1;
    const STATUS_INVALID = 2;
}