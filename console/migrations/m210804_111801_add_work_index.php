<?php

use yii\db\Migration;

/**
 * Class m210804_111801_add_work_index
 */
class m210804_111801_add_work_index extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<EOF
CREATE TABLE `swim_work_order_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `work_order_id` int(11) NOT NULL DEFAULT '0' COMMENT '细分类工单id',
  `operation_id` int(11) NOT NULL DEFAULT '0' COMMENT '工单操作人',
  `operation_name` varchar(100) NOT NULL DEFAULT '' COMMENT '工单操作人姓名',
  `operation_type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '工单操作人类型 1检查人员 2场馆负责人',
  `operation_status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '操作类型 1已提交照片 2审核通过 3审核不通过',
  `handle_img` varchar(1024) NOT NULL DEFAULT '' COMMENT '处理图片',
  `handle_notes` text COMMENT '处理备注',
  `feedback_notes` text COMMENT '反馈备注',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `work_order_id` (`work_order_id`) USING BTREE,
  KEY `operation_id` (`operation_id`) USING BTREE,
  KEY `operation_type` (`operation_type`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='工单历史表';
EOF;
        $this->execute($sql);

        $sql = <<<EOF
CREATE TABLE `swim_work_order_index` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `index_title` varchar(100) NOT NULL DEFAULT '' COMMENT '工单主标题',
  `type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '工单类型 1普通 2紧急',
  `info` text COMMENT '工单详情',
  `venue_name` varchar(100) NOT NULL DEFAULT '' COMMENT '工单所属场馆名称',
  `venue_id` int(11) NOT NULL DEFAULT '0' COMMENT '工单所属场馆id',
  `commit_id` int(11) NOT NULL DEFAULT '0' COMMENT '工单提交人id',
  `commit_type` tinyint(2) NOT NULL DEFAULT '1' COMMENT '提交类型 1小程序提交 2后台提交',
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '工单状态 0未处理 1已处理待审核 2审核通过 3已删除',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `venue_id` (`venue_id`) USING BTREE,
  KEY `commit_id` (`commit_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='工单主表';
EOF;
        $this->execute($sql);

        $sql = <<<EOF
ALTER TABLE `pudongswim`.`swim_work_order` 
ADD COLUMN `index_id` int(11) NOT NULL DEFAULT 0 COMMENT '工单所属主工单id' AFTER `id`,
DROP PRIMARY KEY,
ADD PRIMARY KEY (`id`) USING BTREE,
ADD INDEX `index_id`(`index_id`) USING BTREE,
ADD INDEX `commit_id`(`commit_id`) USING BTREE,
ADD INDEX `venue_id`(`venue_id`) USING BTREE;
ALTER TABLE `pudongswim`.`swim_user_channel_extra` 
ADD INDEX `idx_is_owner`(`is_owner`) USING BTREE;
EOF;
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210804_111801_add_work_index cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210804_111801_add_work_index cannot be reverted.\n";

        return false;
    }
    */
}
