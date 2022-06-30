<?php

use yii\db\Migration;

/**
 * Class m200402_082321_alter_table_swim_address_add_column_imgurl
 */
class m200402_082321_alter_table_swim_address_add_column_imgurl extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<EOF
alter table swim_address add column `imgurl` varchar(255) DEFAULT NULL COMMENT '场馆图片' after name
EOF;

        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200402_082321_alter_table_swim_address_add_column_imgurl cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200402_082321_alter_table_swim_address_add_column_imgurl cannot be reverted.\n";

        return false;
    }
    */
}
