<?php

namespace mirocow\notification\providers;
use mirocow\notification\components\Notification;
use mirocow\notification\components\Provider;
use Yii;
use yii\base\Component;

/**
 * Class smsc
 * @package mirocow\notification\providers
 */
class smsc extends Provider
{
    public $config = [
        'login' => '',
        'password' => '',
        'post' => true,
        // use http POST method
        'https' => true,
        // use secure HTTPS connection
        'charset' => 'utf-8',
        // charset: windows-1251, koi8-r or utf-8 (default)
        'debug' => false,
        // debug mode
    ];

    /**
     * @param Notification $notification
     * @return bool
     */
    public function send(Notification $notification)
    {
        if(empty($notification->phone)) return;

        $sms = Yii::createObject(array_merge(['class' => 'ladamalina\smsc\Smsc'], $this->config));

        $result = $sms->send_sms($notification->phone, $notification->message);
        if ($sms->isSuccess($result)) {
            return true;
        } else {
            return false;
        }
    }

}