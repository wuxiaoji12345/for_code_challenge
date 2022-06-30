<?php

use yii\db\Migration;

/**
 * Class m200402_111654_alter_table_swim_address_user_comment_add_index_urid_address_id
 */
class m200402_111654_alter_table_swim_address_user_comment_add_index_urid_address_id extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<EOF
alter table swim_address_user_comment add index  `idx_urid_address_id` (`user_id` ,`swim_address_id`) USING BTREE
EOF;
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200402_111654_alter_table_swim_address_user_comment_add_index_urid_address_id cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200402_111654_alter_table_swim_address_user_comment_add_index_urid_address_id cannot be reverted.\n";

        return false;
    }
    */
}
