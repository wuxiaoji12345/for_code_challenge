<?php

use yii\db\Migration;

/**
 * Class m200427_014444_alter_table_swim_address_check_add_column_longitude_latitude
 */
class m200427_014444_alter_table_swim_address_check_add_column_longitude_latitude extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<EOF
alter table swim_address_check add column `latitude` float(10,6) DEFAULT NULL COMMENT '赛事纬度' after check_date,
 add column `longitude` float(10,6) DEFAULT NULL COMMENT '赛事经度' after check_date
EOF;
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200427_014444_alter_table_swim_address_check_add_column_longitude_latitude cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200427_014444_alter_table_swim_address_check_add_column_longitude_latitude cannot be reverted.\n";

        return false;
    }
    */
}
