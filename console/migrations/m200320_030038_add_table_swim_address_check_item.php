<?php

use yii\db\Migration;

/**
 * Class m200320_030038_add_table_swim_address_check_item
 */
class m200320_030038_add_table_swim_address_check_item extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<EOF
CREATE TABLE `swim_address_check_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `level` tinyint(2) NOT NULL,
  `pid` int(11) NOT NULL DEFAULT '0',
  `weight` tinyint(3) NOT NULL DEFAULT '0' COMMENT '权重 越高越先展示',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '1-有效；2-删除',
  `info` varchar(256) DEFAULT '' COMMENT '检查内容 json k-v',
  `create_time` int(11) DEFAULT NULL,
  `update_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `idx_pid` (`pid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='场馆检查项目表';
EOF;
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200320_030038_add_table_swim_address_check_item cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200320_030038_add_table_swim_address_check_item cannot be reverted.\n";

        return false;
    }
    */
}
