<?php

use yii\db\Migration;

/**
 * Class m210818_073452_modify_check_detail
 */
class m210818_073452_modify_check_detail extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<EOF
ALTER TABLE `pudongswim`.`swim_address_check_detail` 
ADD COLUMN `check_status` tinyint(2) UNSIGNED NOT NULL DEFAULT 1 COMMENT '1-正常；2-异常' AFTER `status`,
DROP PRIMARY KEY,
ADD PRIMARY KEY (`id`) USING BTREE;
ALTER TABLE `pudongswim`.`swim_user_channel_extra` 
ADD COLUMN `is_super_checker` tinyint(2) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否为超级检查员 1-否；2-是' AFTER `is_checker`,
DROP PRIMARY KEY,
ADD PRIMARY KEY (`id`) USING BTREE;
EOF;
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210818_073452_modify_check_detail cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210818_073452_modify_check_detail cannot be reverted.\n";

        return false;
    }
    */
}
