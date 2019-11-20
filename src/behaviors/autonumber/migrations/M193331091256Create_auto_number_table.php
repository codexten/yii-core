<?php

namespace codexten\yii\behaviors\autonumber\migrations;

use yii\db\Migration;

/**
 * Class M193331091256Create_auto_number_table
 * @package codexten\yii\behaviors\autonumber\migrations
 */
class M193331091256Create_auto_number_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%auto_number}}', [
            'group' => $this->primaryKey(),
            'number' => $this->integer(),
            'optimistic_lock' => $this->integer(),
            'update_time' => $this->integer()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%auto_number}}');
    }
}