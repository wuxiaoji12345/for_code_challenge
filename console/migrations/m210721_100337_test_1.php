<?php

use yii\db\Migration;

/**
 * Class m210721_100337_test_1
 */
class m210721_100337_test_1 extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<EOF
INSERT INTO `pudongswim`.`swim_address` (`id`, `name`, `imgurl`, `province`, `city`, `district`, `address`, `longitude`, `latitude`, `lane`, `comment_num`, `comment_sum_score`, `publish`, `account`, `account_id`, `status`, `create_time`, `update_time`) VALUES (206, '测试场馆', NULL, '上海市', '上海市', '浦东新区', '自由贸易试验区世纪大道100号地下2楼东首、1楼东首、79-88楼、90-93楼', 121.507690, 31.234877, NULL, 0, 0, 1, '123456', 0, 1, NULL, '2021-07-20 17:32:58');
INSERT INTO `pudongswim`.`swim_check_info` (`id`, `name`, `mobile`, `status`, `create_time`) VALUES (12, '测试', '12345678900', 1, 1626783948);
EOF;
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210721_100337_test_1 cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210721_100337_test_1 cannot be reverted.\n";

        return false;
    }
    */
}
