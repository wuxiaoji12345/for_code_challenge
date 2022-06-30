<?php

use yii\db\Migration;

/**
 * Class m210720_120932_add_check_info
 */
class m210720_120932_add_check_info extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<EOF
CREATE TABLE `swim_check_info` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL DEFAULT '' COMMENT '检察员姓名',
  `mobile` varchar(64) NOT NULL DEFAULT '' COMMENT '绑定手机',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '1-有效；2-无效',
  `create_time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `idx_unid` (`name`,`mobile`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='检查人员信息表';
EOF;
        $this->execute($sql);
        $sql = <<<EOF
INSERT INTO `pudongswim`.`swim_check_info` (`id`, `name`, `mobile`, `status`, `create_time`) VALUES (1, '韦云波', '13865046586', 1, 1626783948);
INSERT INTO `pudongswim`.`swim_check_info` (`id`, `name`, `mobile`, `status`, `create_time`) VALUES (2, '张小兵', '13270474125', 1, 1626783948);
INSERT INTO `pudongswim`.`swim_check_info` (`id`, `name`, `mobile`, `status`, `create_time`) VALUES (3, '王莉', '18551156816', 1, 1626783948);
INSERT INTO `pudongswim`.`swim_check_info` (`id`, `name`, `mobile`, `status`, `create_time`) VALUES (4, '刘津岚', '18550552181', 1, 1626783948);
INSERT INTO `pudongswim`.`swim_check_info` (`id`, `name`, `mobile`, `status`, `create_time`) VALUES (5, '刘杰', '18862270867', 1, 1626783948);
INSERT INTO `pudongswim`.`swim_check_info` (`id`, `name`, `mobile`, `status`, `create_time`) VALUES (6, '袁剑刚', '15771907525', 1, 1626783948);
INSERT INTO `pudongswim`.`swim_check_info` (`id`, `name`, `mobile`, `status`, `create_time`) VALUES (7, '杨文彬', '13568436447', 1, 1626783948);
INSERT INTO `pudongswim`.`swim_check_info` (`id`, `name`, `mobile`, `status`, `create_time`) VALUES (8, '路萍', '13817192196', 1, 1626783948);
INSERT INTO `pudongswim`.`swim_check_info` (`id`, `name`, `mobile`, `status`, `create_time`) VALUES (9, '朱烨', '13774278378', 1, 1626783948);
INSERT INTO `pudongswim`.`swim_check_info` (`id`, `name`, `mobile`, `status`, `create_time`) VALUES (10, '黄原', '13918665240', 1, 1626783948);
INSERT INTO `pudongswim`.`swim_check_info` (`id`, `name`, `mobile`, `status`, `create_time`) VALUES (11, '黄健', '13564645360', 1, 1626783948);
EOF;
        $this->execute($sql);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210720_120932_add_check_info cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210720_120932_add_check_info cannot be reverted.\n";

        return false;
    }
    */
}
