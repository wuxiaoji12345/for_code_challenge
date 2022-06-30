<?php

use yii\db\Migration;

/**
 * Class m210721_102942_add_column_work_order
 */
class m210721_102942_add_column_work_order extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<EOF
ALTER TABLE `pudongswim`.`swim_work_order` 
ADD COLUMN `handle_notes` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '处理备注' AFTER `commit_type`;
ALTER TABLE `pudongswim`.`swim_work_order` 
ADD COLUMN `handle_img` varchar(1024) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '处理图片' AFTER `handle_notes`;
EOF;
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210721_102942_add_column_work_order cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210721_102942_add_column_work_order cannot be reverted.\n";

        return false;
    }
    */
}
