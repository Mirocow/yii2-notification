<?php
/**
 * Created by PhpStorm.
 * User: mirocow
 * Date: 15.04.2017
 * Time: 20:13
 */

namespace mirocow\notification\providers;
use mirocow\notification\components\Provider;
use mirocow\notification\components\Notification;
use Yii;

class notify  extends Provider
{
    /**
     * @param Notification $notification
     */
    public function send(Notification $notification)
    {
        if(empty($notification->notify)) return;

        Yii::$app->session->addFlash($notification->notify[0], $notification->notify[1]);

    }
}