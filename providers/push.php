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

class push  extends Provider
{
    /**
     * @param Notification $notification
     */
    public function send(Notification $notification)
    {
        if(empty($notification->token)) return;

        $push = Yii::createObject(array_merge(['class' => 'develandoo\notification\Push'], $this->config));

        if(is_array($notification->token)){
            $tokens = $notification->token;
        } else {
            $tokens = [$notification->token];
        }

        foreach ($tokens as $token){
            $this->status[$token] = $push->ios()->send($token, [
              //'custom-key' => 'custom-value',
              /*'aps' => [
                'alert' => [
                  'loc-key' => 'i18n_key',
                  'loc-args' => ['arg1'],
                ]
              ],*/
              'badge' => 1,
              'sound' => 'default'
            ]);
        }

    }
}