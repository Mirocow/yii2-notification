<?php

namespace mirocow\notification\controllers;

use Yii;
use yii\console\Controller;

$path = Yii::getAlias('@mirocow/notification');

class CronController extends Controller
{

    const RETRY_COUNT = 5;

    private $start;

    public function init()
    {
        $this->start = time();

        echo '[' . date('d.m.Y h:i',
                $this->start) . "] Запущена отсылка писем \n\n";

        register_shutdown_function([$this, 'finish'], true);
    }

    public function finish()
    {
        $end = $this->start;

        echo '[' . date('d.m.Y h:i', $end) . "] Отсылка писем завершена\n\n";
    }

    /**
     * php ./yii notification/cron/send DBGSESSID=1@192.168.8.14:7869;d=1
     *
     * @param mixed $params
     */
    public function actionSend()
    {

        try {

            $notification = Yii::$app->getModule('notification');
            $provider = Yii::createObject($notification->providers['mailQueue']);

            while ($mail = $provider->pop()) {

                if(empty($mail)){
                    continue;
                }

                $mail = @unserialize($mail);

                if (!$mail['to']) {
                    continue;
                }

                $mails_to = preg_split('`[\s\t]+`', $mail['to']);

                foreach ($mails_to as $mail_to) {

                    $mail['to'] = str_replace([',\t\s;'], '', $mail_to);

                    try {

                        $result = $notification->provider('email')->send($mail);

                        echo "Письмо отправленно: {$mail['to']} '{$mail['subject']}'\n";

                    } catch (\Exception $e) {

                        if (!isset($mail['retry_count'])) {
                            $mail['retry_count'] = 0;
                        }
                        $mail['retry_count']++;
                        if ($mail['retry_count'] < self::RETRY_COUNT) {
                            $queue[] = $mail;
                        }

                        echo "Не удалось отправить письмо: {$mail['to']} '{$mail['subject']}'\n";
                        echo sprintf("Err: '%s' in %s: %s\n", $e->getMessage(), $e->getFile(), $e->getLine());

                    }

                }

            }

        } catch (\Exception $e) {
            print_r($e);
            die();
        }

    }
}