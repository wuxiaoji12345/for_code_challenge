<?php

use yii\db\Migration;

/**
 * Class m200320_030325_add_table_swim_address_check_detail
 */
class m200320_030325_add_table_swim_address_check_detail extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<EOF
CREATE TABLE `swim_address_check_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `swim_address_check_id` int(11) NOT NULL,
  `swim_address_check_item_id` varchar(64) NOT NULL,
  `result` varchar(256) NOT NULL,
  `item_snapshot` varchar(256) NOT NULL DEFAULT '' COMMENT '检查内容快照',
  `check_date` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '1-有效；2-删除',
  `create_time` int(11) DEFAULT NULL,
  `update_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `idx_adddress_check_date` (`swim_address_id`, `check_date`) USING BTREE,
  KEY `idx_check_item` (`swim_address_check_item_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='场馆检查日志表';
EOF;
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200320_030325_add_table_swim_address_check_detail cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200320_030325_add_table_swim_address_check_detail cannot be reverted.\n";

        return false;
    }
    */
}
