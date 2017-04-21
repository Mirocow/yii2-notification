# Yii 2.0 Notification


## Install

```sh
$ composer require --prefer-dist "mirocow/yii2-notification"
$ php ./yii migrate/up -p=@mirocow/notification/migrations
```

## Configurate

```php
    'modules' => [
        // Notification by providers
        'notification' => [
          'class' => 'mirocow\notification\Module',
          'providers' => [

              // SMS prostor-sms.ru
              'sms' => [
                'class' => 'mirocow\notification\providers\sms',
                'config' => [
                  'gate' => '',
                  'port' => 80,
                  'login' => '',
                  'password' => '',
                  'signature' => '',
                ]
              ],

              // Redis
              'mailQueue' => [
                'class' => 'mirocow\notification\providers\mailQueue',
                'debug' => true,
                'queue_name' => 'emails_queue',
                'config' => [
                  'hostname' => 'localhost',
                  'port' => 6379,
                  //'password' => '',
                  'database' => 0,
                ]
              ],

              // E-mail
              'email' => [
                'class' => 'mirocow\notification\providers\email',
                'config' => [
                  'from' => ['request@myhost.com' => 'My host.com'],
                  'class' => 'Swift_SmtpTransport',
                  'charset' => 'utf-8',
                  'host' => '',
                  //'port' => 25,
                  'username' => '',
                  'password' => ''
                ]
              ]
          ],
        ]
    ],        
```

## Using

### By method send

```php
    $email = [
      'to' => 'notification@mirocow.com
      //'from_name' => '',
      'subject' => 'Subject example
      'message' => '<h1>Content example</h1>'
    ];
    
    /* @var Notification $notification */
    $notification = Yii::$app->getModule('notification');    
    
    $notification->send($email, function ($mail, $status) use (&$errors) {

        $errors[] = Yii::t('core', 'Email {mail} sent', [
          'mail' => $mail['to']
        ]);

    });
```

### By Event

```php
use yii\base\Event;
use mirocow\notification\components\Notification;

$event = new Notification(['params' => [
  'from' => [\Yii::$app->params['supportEmail'] => \Yii::$app->name . ' robot'],
  'to' => $user->email,
  'subject' => 'Регистрация на сайте ' . \Yii::$app->name,
  'emailView' => ['html' => 'signUp-html', 'text' => 'signUp-text'],
  'user' => $user,
  'phone' => $user->phone_number,
  'notify' => ['growl', 'На Ваш email отправлено письмо для подтверждения'],
]]);
Notification::trigger(self::className(),'actionSignup', $event);
```

## Run console

```php
php ./yii notification/cron/send
```               