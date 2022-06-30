<?php

use yii\db\Migration;

/**
 * Class m210721_061929_create_work_order
 */
class m210721_061929_create_work_order extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<EOF
CREATE TABLE `swim_work_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titile` varchar(100) NOT NULL DEFAULT '' COMMENT '工单标题',
  `img_url` varchar(1024) NOT NULL DEFAULT '' COMMENT '图片地址',
  `info` text COMMENT '工单详情',
  `type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '工单类型 1普通 2紧急',
  `venue_name` varchar(100) NOT NULL DEFAULT '' COMMENT '工单所属场馆名称',
  `venue_id` int(11) NOT NULL DEFAULT '0' COMMENT '工单所属场馆id',
  `commit_id` int(11) NOT NULL DEFAULT '0' COMMENT '工单提交人id',
  `commit_type` tinyint(2) NOT NULL DEFAULT '1' COMMENT '提交类型 1小程序提交 2后台提交',
  `feedback_notes` text COMMENT '反馈备注',
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '工单状态 0待受理 1已受理 2已关闭',
  `feedback_status` int(11) NOT NULL DEFAULT '0' COMMENT '反馈状态 0未反馈 1同意2不同意',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='工单表';
EOF;
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210721_061929_create_work_order cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210721_061929_create_work_order cannot be reverted.\n";

        return false;
    }
    */
}
