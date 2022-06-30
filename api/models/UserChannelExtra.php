<?php


namespace api\models;


class UserChannelExtra extends \common\models\UserChannelExtra
{
    const STATUS_VALID = 1;
    const STATUS_INVALID = 2;

    const CHECKER_YES = 1;
    const CHECKER_NO = 2;

    const SUPER_CHECKER_YES = 2;
    const SUPER_CHECKER_NO = 1;
}