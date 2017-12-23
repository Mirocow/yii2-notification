<?php

use yii\db\Expression;
use yii\db\Migration;

/**
 * Class m171222_202322_create_table_notificatio_status
 */
class m171222_202322_create_table_notification_status extends Migration
{
    public function up()
    {

        $this->createTable('notification_status', [
            'id' => $this->primaryKey(),
            'provider' => $this->string(),
            'event' => $this->string(),
            'params' => $this->text(),
            'status' => $this->string()->null(),
            'update_at' => $this->timestamp()->null(),
            'create_at' => $this->timestamp()->defaultValue(new Expression('CURRENT_TIMESTAMP')),
        ]);

    }

    public function down()
    {
        $this->dropTable('notification_status');
    }
}
