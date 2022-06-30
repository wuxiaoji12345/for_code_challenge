<?php

namespace common\models;

use common\models\base\__EventType;

class EventType extends __EventType
{
    const NEEDCHECK_NOCHECK = 1;
    const NEEDCHECK_CHECKFIRST = 2;
    const NEEDCHECK_CHECKLAST = 3;

    
}
