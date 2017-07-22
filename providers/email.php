<?php

namespace mirocow\notification\providers;

use mirocow\notification\components\Notification;
use mirocow\notification\components\Provider;
use Yii;
use yii\base\Exception;

/**
 * Class email
 * @package mirocow\notification\providers
 */
class email extends Provider
{
    public $emailViewPath = '@mirocow/notification/tpl';

    public $emailView = 'email-base.tpl.php';

    public $config = [
        'mailer' => [],
    ];

    /**
     * @param Notification $notification
     * @return bool
     * @throws Exception
     */
    public function send(Notification $notification)
    {
        if(empty($notification->to)) return;

        $provider = 'mailer';

        if (!empty($this->config['mailer'])) {
            $provider = $this->config['mailer'];
        }

        /** @var \yii\swiftmailer\Mailer $mailer */
        $mailer = Yii::$app->get($provider);

        if(!$mailer){
            throw new Exception();
        }

        $mailer->viewPath = isset($notification->path) ? $notification->path : $this->emailViewPath;

        $emailView = isset($notification->view) ? $notification->view : $this->emailView;

        if(isset($notification->from)){
            $from = $notification->from;
        }else {
            if (isset($this->config['from'])) {
                $from = $this->config['from'];
            }
            else {
                $from = isset(Yii::$app->params['adminEmail']) ? Yii::$app->params['adminEmail'] : 'admin@localhost';
            }
        }

        $params = array_merge($notification->params, [
          'subject' => $notification->subject,
          'message' => $notification->message
        ]);

        if (is_array($notification->to)) {
            $emails = $notification->to;
        }
        else {
            $emails = [$notification->to];
        }

        foreach ($emails as $email) {
            $this->status[$email] = $mailer->compose($emailView, $params)
                                     ->setFrom($from)
                                     ->setTo($email)
                                     ->setSubject($notification->subject)
                                     ->send();
        }

    }

}