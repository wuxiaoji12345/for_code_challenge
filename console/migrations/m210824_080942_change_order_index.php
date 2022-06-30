<?php

use yii\db\Migration;

/**
 * Class m210824_080942_change_order_index
 */
class m210824_080942_change_order_index extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<EOF
ALTER TABLE `pudongswim`.`swim_work_order_index` 
MODIFY COLUMN `status` int(11) NOT NULL DEFAULT 0 COMMENT '工单状态 0未处理 1已处理待审核 3已删除' AFTER `commit_type`,
ADD COLUMN `examine_status` int(11) NOT NULL DEFAULT 1 COMMENT '审核状态 1未审核 2审核通过' AFTER `status`,
DROP PRIMARY KEY,
ADD PRIMARY KEY (`id`) USING BTREE;
UPDATE swim_work_order_index set examine_status = 2 where status = 2; 
EOF;
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210824_080942_change_order_index cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210824_080942_change_order_index cannot be reverted.\n";

        return false;
    }
    */
}
