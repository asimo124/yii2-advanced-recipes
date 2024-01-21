<?php

use yii\db\Migration;

/**
 * Class m200726_161328_add_column_access_token_to_user_table
 */
class m200726_161328_add_column_access_token_to_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user', 'access_token', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200726_161328_add_column_access_token_to_user_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200726_161328_add_column_access_token_to_user_table cannot be reverted.\n";

        return false;
    }
    */
}
