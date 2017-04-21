<?php
/**
 * Created by PhpStorm.
 * User: mirocow
 * Date: 15.04.2017
 * Time: 20:13
 */

namespace mirocow\notification\providers;
use mirocow\notification\components\Notification;
use mirocow\notification\components\Provider;
use mirocow\notification\models\Message;
use yii\helpers\ArrayHelper;

class web  extends Provider
{
    /**
     * @param Notification $notification
     * @return bool
     */
    public function send(Notification $notification)
    {
        if(is_array($notification->toId)){
            $toIds = $notification->toId;
        } else {
            $toIds = [$notification->toId];
        }

        foreach ($toIds as $toId) {
            $message          = new Message();
            $message->from_id = $notification->fromId;
            $message->to_id   = $toId;
            $message->title   = $notification->subject;
            $message->message = $notification->message;
            $message->setParams(ArrayHelper::merge(['event' => $notification->name], $notification->params));
            $message->save();
        }

        return true;
    }
}