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
use Yii;

class push extends Provider
{
    /**
     * @param Notification $notification
     */
    public function send(Notification $notification)
    {
        if (empty($notification->token)) {
            return;
        }

        /** @var \mirocow\notification\components\Push $push */
        $push = Yii::createObject(array_merge(['class' => 'mirocow\notification\components\Push'], $this->config));

        if (is_array($notification->token)) {
            $tokens = $notification->token;
        } else {
            $tokens = [$notification->token];
        }

        foreach ($tokens as $token) {
            try {
                $status = $push->ios()->send($token, $notification->push);
            } catch (\Exception $e) {
                $this->errors[] = ErrorHelper::message($e);
            }

            $this->status[$token] = $status;
        }

        unset($push);
    }
}