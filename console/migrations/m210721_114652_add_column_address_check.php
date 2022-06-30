<?php

use yii\db\Migration;

/**
 * Class m210721_114652_add_column_address_check
 */
class m210721_114652_add_column_address_check extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<EOF
ALTER TABLE `pudongswim`.`swim_address_check` 
CHANGE COLUMN `status` `check_status` tinyint(2) UNSIGNED NOT NULL DEFAULT 1 COMMENT '检查结果状态 1正常 2异常' AFTER `comment_num`,
ADD COLUMN `status` tinyint(2) UNSIGNED NOT NULL DEFAULT 1 COMMENT '1-有效；2-删除' AFTER `check_status`,
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
        echo "m210721_114652_add_column_address_check cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210721_114652_add_column_address_check cannot be reverted.\n";

        return false;
    }
    */
}
