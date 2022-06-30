<?php

use yii\db\Migration;

/**
 * Class m210204_063352_add_table_address_lifeguard
 */
class m210204_063352_add_table_address_lifeguard extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<EOF
CREATE TABLE `swim_address_lifeguard` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `swim_address_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL COMMENT '姓名',
  `gender` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '性别 1-男，2-女',
  `mobile` varchar(16) NOT NULL COMMENT '手机',
  `id_card` varchar(32) NOT NULL COMMENT '身份证',
  `cert_type` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '证件类型 1-救生员证；2-国职证书',
  `cert_level` varchar(16) NOT NULL COMMENT '证书级别 初级 中级 高级',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '1-有效；2-删除',
  `create_time` int(11) DEFAULT NULL,
  `update_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `idx_adddress_idcard` (`swim_address_id`,`id_card`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='场馆救生员表表';
EOF;
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210204_063352_add_table_address_lifeguard cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210204_063352_add_table_address_lifeguard cannot be reverted.\n";

        return false;
    }
    */
}
