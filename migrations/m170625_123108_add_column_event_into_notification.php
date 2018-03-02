<?php

use yii\db\Expression;
use yii\db\Migration;

class m170625_123108_add_column_event_into_notification extends Migration
{
    public function up()
    {
        $this->addColumn('notification', 'event', $this->string(100)->after('to_id'));
    }

    public function down()
    {
        $this->dropColumn('notification', 'event');
    }

}
