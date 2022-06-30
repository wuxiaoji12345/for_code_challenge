<?php

use yii\db\Migration;

/**
 * Class m200427_060003_alter_table_swim_address_check_detail_change_column_result
 */
class m200427_060003_alter_table_swim_address_check_detail_change_column_result extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<EOF
ALTER TABLE `swim_address_check_detail` CHANGE `result` `result` text NOT NULL
EOF;
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200427_060003_alter_table_swim_address_check_detail_change_column_result cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200427_060003_alter_table_swim_address_check_detail_change_column_result cannot be reverted.\n";

        return false;
    }
    */
}
