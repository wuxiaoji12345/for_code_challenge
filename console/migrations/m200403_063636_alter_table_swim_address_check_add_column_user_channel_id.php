<?php

use yii\db\Migration;

/**
 * Class m200403_063636_alter_table_swim_address_check_add_column_user_channel_id
 */
class m200403_063636_alter_table_swim_address_check_add_column_user_channel_id extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<EOF
alter table swim_address_check add column `user_channel_id` int(11) NOT NULL COMMENT 'user channel id' after swim_address_id
EOF;
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200403_063636_alter_table_swim_address_check_add_column_user_channel_id cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200403_063636_alter_table_swim_address_check_add_column_user_channel_id cannot be reverted.\n";

        return false;
    }
    */
}
