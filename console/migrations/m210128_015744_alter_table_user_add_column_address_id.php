<?php

use yii\db\Migration;

/**
 * Class m210128_015744_alter_table_user_add_column_address_id
 */
class m210128_015744_alter_table_user_add_column_address_id extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<EOF
ALTER TABLE `user` add column swim_address_id int(11) not null default 0 after email
EOF;
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210128_015744_alter_table_user_add_column_address_id cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210128_015744_alter_table_user_add_column_address_id cannot be reverted.\n";

        return false;
    }
    */
}
