<?php

use yii\db\Migration;

/**
 * Class m210901_102124_modify_order_index
 */
class m210901_102124_modify_order_index extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<EOF
ALTER TABLE `pudongswim`.`swim_work_order_index` 
ADD COLUMN `address_check_id` int(11) NOT NULL DEFAULT 0 COMMENT '检查id' AFTER `id`,
DROP PRIMARY KEY,
ADD PRIMARY KEY (`id`) USING BTREE,
ADD INDEX `address_check_id`(`address_check_id`) USING BTREE;
EOF;
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210901_102124_modify_order_index cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210901_102124_modify_order_index cannot be reverted.\n";

        return false;
    }
    */
}
