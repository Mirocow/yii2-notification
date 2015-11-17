<?php

namespace mirocow\notification\providers;

use Yii;

class email
{

    public $config = [
        'from' => '',
        'charset' => 'utf-8',
        'host' => 'localhost',
        'port' => 25,
        'username' => '',
        'password' => '',
        'encryption' => '',
        /*
        * Swift_SmtpTransport: Использует SMTP-сервер для отправки сообщений.
        * Swift_SendmailTransport: Использует объект sendmail для отправки сообщений.
        * Swift_MailTransport: Использует стандартную функцию PHP mail() для отправки сообщений.
        */
        'class' => 'Swift_MailTransport',
    ];

    public function send($params)
    {

        /** @var Mailer $mailer */
        $mailer = Yii::$app->mail;

        if (isset($this->config)) {
            $config = [];

            foreach ([
                         'class',
                         'host',
                         'username',
                         'password',
                         'port',
                         'encryption'
                     ] as $name
            ) {

                if (isset($this->config[$name])) {

                    $config[$name] = $this->config[$name];

                }

            }

            $mailer->setTransport($config);
        }

        /* @var Notification $notification */
        $notification = Yii::$app->getModule('notification');

        $mailer->viewPath = isset($params['emailViewPath']) ? $params['emailViewPath'] : $notification->emailViewPath;

        $emailView = isset($params['emailView']) ? $params['emailView'] : $notification->emailView;

        if (isset($this->config['from'])) {

            $from = $this->config['from'];

        } else {

            $from = isset(Yii::$app->params['adminEmail']) ? Yii::$app->params['adminEmail'] : 'admin@localhost';

        }

        return $mailer->compose($emailView, $params)
            ->setFrom($from)
            ->setTo($params['to'])
            ->setSubject($params['subject'])
            ->setTextBody($params['message'])
            ->setHtmlBody($params['message'])
            ->send();

    }

}