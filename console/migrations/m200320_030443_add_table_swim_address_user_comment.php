<?php

use yii\db\Migration;

/**
 * Class m200320_030443_add_table_swim_address_user_comment
 */
class m200320_030443_add_table_swim_address_user_comment extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<EOF
CREATE TABLE `swim_address_user_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `swim_address_id` int(12) NOT NULL,
  `comment_date` datetime NOT NULL,
  `user_id` int(11) NOT NULL,
  `score` tinyint(2) NOT NULL DEFAULT '0',
  `comment` varchar(256) NOT NULL DEFAULT '',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '1-有效；2-删除',
  `create_time` int(11) DEFAULT NULL,
  `update_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `idx_swim_address_id_date` (`swim_address_id`,`comment_date`) USING BTREE,
  KEY `idx_comment_date` (`comment_date`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='场馆用户评价表';
EOF;
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200320_030443_add_table_swim_address_user_comment cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200320_030443_add_table_swim_address_user_comment cannot be reverted.\n";

        return false;
    }
    */
}
