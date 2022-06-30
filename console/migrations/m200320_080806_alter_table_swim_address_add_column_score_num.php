<?php

use yii\db\Migration;

/**
 * Class m200320_080806_alter_table_swim_address_add_column_score_num
 */
class m200320_080806_alter_table_swim_address_add_column_score_num extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<EOF
alter table swim_address
add column comment_sum_score int(11) default '0' after lane,
add column comment_num int(11) default '0' after lane;
EOF;
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200320_080806_alter_table_swim_address_add_column_score_num cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200320_080806_alter_table_swim_address_add_column_score_num cannot be reverted.\n";

        return false;
    }
    */
}
