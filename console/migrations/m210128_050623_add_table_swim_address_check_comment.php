<?php

use yii\db\Migration;

/**
 * Class m210128_050623_add_table_swim_address_check_comment
 */
class m210128_050623_add_table_swim_address_check_comment extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<EOF
CREATE TABLE `swim_address_check_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `swim_address_check_id` int(11) NOT NULL,
  `swim_address_id` int(11) NOT NULL,
  `imgurl` varchar(255) NOT NULL DEFAULT '' COMMENT '图片',
  `comment` varchar(255) NOT NULL DEFAULT '' COMMENT '文字',
  `bkurid` int(11) NOT NULL DEFAULT '0',
  `is_stadium` tinyint(2) NOT NULL DEFAULT '1' COMMENT '1-场馆方 2-非场馆方',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '1-有效；2-删除',
  `create_time` int(11) DEFAULT NULL,
  `update_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `idx_adddress_check` (`swim_address_check_id`, `swim_address_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='场馆检查评论';
EOF;
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210128_050623_add_table_swim_address_check_comment cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210128_050623_add_table_swim_address_check_comment cannot be reverted.\n";

        return false;
    }
    */
}
