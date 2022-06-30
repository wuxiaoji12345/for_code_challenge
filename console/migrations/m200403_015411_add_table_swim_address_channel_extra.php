<?php

use yii\db\Migration;

/**
 * Class m200403_015411_add_table_swim_address_channel_extra
 */
class m200403_015411_add_table_swim_address_channel_extra extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<EOF
CREATE TABLE `swim_user_channel_extra` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_channel_id` int(11) NOT NULL COMMENT 'user channel id',
  `is_checker` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '是否为场馆检查员 1-是；2-否',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '1-有效；2-无效',
  `update_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `create_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `idx_user_channel_id` (`user_channel_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户渠道额外信息表';
EOF;
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200403_015411_add_table_swim_address_channel_extra cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200403_015411_add_table_swim_address_channel_extra cannot be reverted.\n";

        return false;
    }
    */
}
