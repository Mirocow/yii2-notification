<?php

use yii\db\Migration;

class m170419_203853_create_table_notification extends Migration
{
    public function up()
    {

        $this->createTable('notification', [
          'id' => $this->primaryKey(),
          'from_id' => $this->integer(11),
          'to_id' => $this->integer(11),
          'title' => $this->string(255),
          'message' => $this->text(),
          'params' => $this->text(),
          'update_at' => $this->timestamp(),
          'create_at' => $this->timestamp(),
        ]);

    }

    public function down()
    {
        $this->dropTable('notification');
    }

}
