<?php
namespace common\config;
/**
 * Created by wayne.
 * Date: 2019/1/10
 * Time: 10:22 AM
 */

define('CREATE','创建');
define('UPDATE','编辑');
define('WEIGHT','权重');
define('CREATE_TIME','添加时间');
define('SUCCESSCODE','200');

define("PAY_FREE",0);//免费
define("PAY_ALI",1);//支付宝
define("PAY_WX",2);//微信
define("PAY_OFFLINE",3);//银行转账
define("PAY_IMPORT",4);//外部导入
define("PAY_DISCOUNT",5);//折扣码
define("PAY_METHOD_WAP",1);
define("PAY_METHOD_PC",2);
define("PAY_METHOD_QR",3);
define("PAY_METHOD_NONE",4);
define("PAY_METHOD_H5",5);


define("REG_WAIT" , 10);//报名未开始
define("REG_PRO" , 11);//报名进行中
define("REG_END" , 12);//报名结束
define("REG_OVER" , 13);//报名已满
define("MATCH_END" , 99);//比赛结束


define('MATCH_TYPE_SINGLE',1);//单人
define('MATCH_TYPE_FAMILY',2);//家庭
define('MATCH_TYPE_TEAM',3);//队伍