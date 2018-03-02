<?php

namespace mirocow\notification\commands;

use mirocow\notification\components\Notification;
use Yii;
use yii\console\Controller;

/**
 * NotificationController
 */
class NotificationController extends Controller
{
    public function actionSend($triggerClass, $name, array $data = [])
    {
        if(empty($data)){
            return;
        }

        Yii::$app->db->close();
        $notification = new Notification($data);
        Notification::trigger($triggerClass, $name, $notification);
    }
}
