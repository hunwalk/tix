<?php

use yii\db\Migration;

/**
 * Class m220503_110949_add_avatar_field_to_profile_table
 */
class m220503_110949_add_avatar_field_to_profile_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%profile}}','avatar', $this->string()->after('gravatar_id'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%profile}}', 'avatar');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220503_110949_add_avatar_field_to_profile_table cannot be reverted.\n";

        return false;
    }
    */
}
