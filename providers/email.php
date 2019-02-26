<?php

namespace mirocow\notification\providers;

use mirocow\notification\components\Notification;
use mirocow\notification\components\Provider;
use Yii;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\mail\MessageInterface;

/**
 * Class email
 * @package mirocow\notification\providers
 */
class email extends Provider
{
    public $emailViewPath = '@mirocow/notification/tpl';

    public $layouts = [
        'text' => '@common/mail/layouts/text',
        'html' => '@common/mail/layouts/html',
    ];

    public $views = [
        'text' => 'email-base.text.tpl.php',
        'html' => 'email-base.html.tpl.php',
    ];

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
            throw new InvalidConfigException();
        }

        /**
         * Prepare
         */

        if (!empty($this->config['view'])) {
            $mailer->setView($this->config['view']);
            $mailer->getView();
        }

        $mailer->view->params['notification'] = $notification;

        $mailer->viewPath = isset($notification->path) ? $notification->path : $this->emailViewPath;

        $params = array_merge($notification->params, [
          'subject' => $notification->subject,
        ]);

        // Registered variable
        unset($params['message']);

        /**
         * Layouts
         */

        if(isset($notification->layouts['text'])){
            $mailer->textLayout = $notification->layouts['text'];
        }
        elseif(isset($this->layouts['text'])){
            $mailer->textLayout = $this->layouts['text'];
        }

        if(isset($notification->layouts['html'])){
            $mailer->htmlLayout = $notification->layouts['html'];
        }
        elseif(isset($this->layouts['html'])){
            $mailer->htmlLayout = $this->layouts['html'];
        }

        /**
         * From
         */

        if(!empty($notification->from)){
            $from = $notification->from;
        }else {
            if (isset($this->config['from'])) {
                $from = $this->config['from'];
            }
            else {
                $from = isset(Yii::$app->params['adminEmail']) ? Yii::$app->params['adminEmail'] : 'admin@localhost';
            }
        }

        /**
         * To
         */

        if (is_array($notification->to)) {
            if(is_array(reset($notification->to))){
                $emails = $notification->to;
            } else {
                // like [email => userName]
                $emails = [$notification->to];
            }
        }
        else {
            $emails = [$notification->to];
        }

        /**
         * Send emails
         */

        $views = isset($notification->view) ? $notification->view : $this->views;

        foreach ($emails as $email) {
            $status = false;
            try {

                /** @var MessageInterface $message */
                $message = $mailer
                    ->compose($views, $params);

                /**
                 * Reply-To
                 */

                if($notification->replyTo){
                    $message->setReplyTo($notification->replyTo);
                }

                /**
                 * Body
                 */

                if($notification->TextBody){
                    $message->setTextBody($notification->TextBody);
                }

                if($notification->HtmlBody){
                    $message->setHtmlBody($notification->HtmlBody);
                }

                /**
                 * Attaches
                 */

                if($notification->attaches){
                    foreach ($notification->attaches as $attach) {
                        $message->attach($attach);
                    }
                }

                /**
                 * Send email
                 */

                $status = $message
                    ->setFrom($from)
                    ->setTo($email)
                    ->setSubject($notification->subject)
                    ->send();

            } catch (\Exception $e){
                $this->errors[] = $e->getMessage();
            }

            if (is_array($email)) {
                foreach ($email as $_email) {
                    $this->status[$_email] = $status;
                }
            } else {
                $this->status[$email] = $status;
            }
        }

        unset($mailer);
    }

}
