<?php

use yii\db\Migration;

/**
 * Class m210128_072303_alter_table_add_column_comment_num
 */
class m210128_072303_alter_table_add_column_comment_num extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<EOF
alter table swim_address_check
add column comment_num int(11) default '0' after latitude;
EOF;
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210128_072303_alter_table_add_column_comment_num cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210128_072303_alter_table_add_column_comment_num cannot be reverted.\n";

        return false;
    }
    */
}
