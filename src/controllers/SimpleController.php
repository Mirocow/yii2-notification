<?php

namespace mirocow\notification\controllers;

use mirocow\notification\models\SimpleNotificationForm;

use Yii;
use yii\db\Query;

class SimpleController extends \yii\web\Controller
{
    public $debug = false;

    public function actionSend()
    {
        $errors = [];

        $model = new SimpleNotificationForm;

        if ($model->load($_POST)) {

            $errors[] = '';

            $mails = [];
            
            /* @var Notification $notification */
            $notification = Yii::$app->getModule('notification');            

            if(preg_match('~\s~', $model->to)){
              $mails = preg_split("~[\s\n\t]+~", $model->to);
            } else {
              $mails = [$model->to];
            }            

            if ($mails) {
              
                foreach ($mails as $mail) {
                  
                    $email = [
                      'to' => $mail,
                      //'from_name' => '',
                      'subject' => $model->subject,
                      'message' => $model->message,
                    ];
                    
                    $notification->sendMessage([$email], function ($mail, $status) use (&$errors) {

                        $errors[] = Yii::t('core', 'На почтовый ящик {mail} выслано письмо', [
                          'mail' => $mail['to']
                        ]);

                    });
                                        
                }              

            } else {

                $errors[] = 'Не найдено e-mail-ов для отправки';

            }

        } else {

            $errors[] = 'Ошибка при инициализации отправки писем';

        }

        if($model->errors){
            $errors[] = print_r($model->errors, true);
        }

        return $this->render('/simple/form-notification', ['model' => $model, 'errors' => $errors]);

    }

}
