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
use mirocow\notification\helpers\ErrorHelper;
use mirocow\notification\models\Message;
use yii\helpers\ArrayHelper;

class web extends Provider
{
    /**
     * @param Notification $notification
     *
     * @return bool
     */
    public function send(Notification $notification)
    {
        if (empty($notification->toId)) {
            return;
        }

        if (is_array($notification->toId)) {
            $toIds = $notification->toId;
        } else {
            $toIds = [$notification->toId];
        }

        \Yii::$app->db->open();
        foreach ($toIds as $toId) {
            try {
                $message = new Message();
                $message->from_id = $notification->fromId;
                $message->to_id = $toId;
                $message->event = $notification->name;
                $message->title = $notification->subject;
                $message->message = $notification->content;
                $message->setParams(ArrayHelper::merge(['event' => $notification->name], $notification->params));
                $status = $message->save();
                unset($message);
            } catch (\Exception $e) {
                $this->errors[] = ErrorHelper::message($e);
            }

            $this->status[$toId] = $status;
        }
        \Yii::$app->db->close();

    }
}