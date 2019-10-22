<?php

namespace mirocow\notification\providers;

use mirocow\notification\components\Notification;
use mirocow\notification\components\Provider;
use mirocow\notification\helpers\ErrorHelper;
use Yii;

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
     *
     * @return bool
     */
    public function send(Notification $notification)
    {
        if (empty($notification->phone)) {
            return;
        }

        /** @var \ladamalina\smsc\Smsc $sms */
        $sms = Yii::createObject(array_merge(['class' => 'ladamalina\smsc\Smsc'], $this->config));

        if (is_array($notification->phone)) {
            $phones = $notification->phone;
        } else {
            $phones = [$notification->phone];
        }

        foreach ($phones as $phone) {
            try {
                $result = $sms->send_sms($phone, $notification->subject);
                $status = $sms->isSuccess($result);
            } catch (\Exception $e) {
                $this->errors[] = ErrorHelper::message($e);
            }

            $this->status[$phone] = $status;
        }

        unset($sms);

    }

}