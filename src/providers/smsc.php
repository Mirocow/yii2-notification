<?php

namespace mirocow\notification\providers;

use Yii;

/**
 * Created by PhpStorm.
 * User: mirocow
 * Date: 24.08.14
 * Time: 20:44
 */
class smsc
{
    public $config = [
        'class' => 'ladamalina\smsc\Smsc',
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

    public function send($args = [])
    {
        $sms = Yii::createObject($this->config);

        $result = $sms->send_sms($args['phone'], $args['message']);
        if ($sms->isSuccess($result)) {
            return true;
        } else {
            return false;
        }
    }

}