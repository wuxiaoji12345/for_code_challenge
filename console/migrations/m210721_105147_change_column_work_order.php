<?php

use yii\db\Migration;

/**
 * Class m210721_105147_change_column_work_order
 */
class m210721_105147_change_column_work_order extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<EOF
ALTER TABLE `pudongswim`.`swim_work_order` 
CHANGE COLUMN `titile` `title` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '工单标题' AFTER `id`;
EOF;
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210721_105147_change_column_work_order cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210721_105147_change_column_work_order cannot be reverted.\n";

        return false;
    }
    */
}
