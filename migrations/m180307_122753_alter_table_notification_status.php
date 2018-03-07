<?php

use yii\db\Migration;

/**
 * Class m180307_122753_alter_table_notification_status
 */
class m180307_122753_alter_table_notification_status extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->alterColumn('notification_status', 'status', $this->text());
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180307_122753_alter_table_notification_status cannot be reverted.\n";

        return false;
    }

}
