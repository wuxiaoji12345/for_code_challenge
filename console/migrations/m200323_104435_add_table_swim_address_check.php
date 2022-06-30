<?php

use yii\db\Migration;

/**
 * Class m200323_104435_add_table_swim_address_check
 */
class m200323_104435_add_table_swim_address_check extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<EOF
CREATE TABLE `swim_address_check` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `swim_address_id` int(11) NOT NULL,
  `check_date` datetime NOT NULL,
  `status` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '1-有效；2-删除',
  `create_time` int(11) DEFAULT NULL,
  `update_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `idx_adddress_check_date` (`swim_address_id`,`check_date`) USING BTREE,
  KEY `idx_check_date` (`check_date`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='场馆每日检查表';
EOF;

        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200323_104435_add_table_swim_address_check cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200323_104435_add_table_swim_address_check cannot be reverted.\n";

        return false;
    }
    */
}
