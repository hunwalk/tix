<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%system_variable}}`.
 */
class m220504_064927_create_system_variable_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%system_variable}}', [
            'id' => $this->primaryKey(),
            'key' => $this->string(),
            'value' => $this->text(),
            'value_type' => $this->string(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%system_variable}}');
    }
}
