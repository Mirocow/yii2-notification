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
                ],
                'events' => [
                  'frontend\controllers\SiteController' => [
                    'Request',
                    'Signup',
                  ],
                  'backend\controllers\deal\SiteController' => [
                    'Login',
                    'Confirm',
                  ]
                ]                
              ]
          ],
        ]
    ],        
```

## Using

### By method send

```php
use mirocow\notification\components\Notification;

    $email = [
      'to' => 'notification@mirocow.com
      //'from_name' => '',
      'subject' => 'Subject example
      'message' => '<h1>Content example</h1>'
    ];
    
    /* @var \mirocow\notification\Module $sender */
    $sender = Yii::$app->getModule('notification');
    
    $notification = new Notification([
      'from' => [\Yii::$app->params['supportEmail'] => \Yii::$app->name . ' robot'],
      'to' => $deal['userSeller']['email'], // строка или массив
      'toId' => $deal['userSeller']['id'], // строка или массив
      'phone' => $deal['userSeller']['phone_number'], // строка или массив
      'subject' => "\"{$deal['userBuyer']['nameForOut']}\" предлагает вам сделку для \"{$deal['ads']['product']->getName()}\"",
      'token' => 'TOKEN',
      'message' => "",
      'params' => [
        'productName' => $deal['ads']['product']->getName(),
        'avatar' => $deal['userBuyer']->avatarFile,
        'fromUserName' => $deal['userBuyer']['nameForOut'],
      ],
      'view' => ['html' => 'Request-html', 'text' => 'Request-text'],
      'path' => '@common/mail/deal',
      'notify' => ['growl', 'На Ваш email отправлено письмо для подтверждения'],
      'callback' => function(Provider $provider, $status){
        // Тут можно обработать ответ от провайдеров нотификаций
      }
    ]);
           
    $sender->sendEvent($notifacation);
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
Notification::trigger(self::className(),'Signup', $event);
```

or full

```php
$notification = new Notification([
  'from' => [\Yii::$app->params['supportEmail'] => \Yii::$app->name . ' robot'],
  'to' => $deal['userSeller']['email'], // строка или массив
  'toId' => $deal['userSeller']['id'], // строка или массив
  'phone' => $deal['userSeller']['phone_number'], // строка или массив
  'subject' => "\"{$deal['userBuyer']['nameForOut']}\" предлагает вам сделку для \"{$deal['ads']['product']->getName()}\"",
  'token' => 'TOKEN',
  'message' => "",
  'params' => [
    'productName' => $deal['ads']['product']->getName(),
    'avatar' => $deal['userBuyer']->avatarFile,
    'fromUserName' => $deal['userBuyer']['nameForOut'],
  ],
  'view' => ['html' => 'Request-html', 'text' => 'Request-text'],
  'path' => '@common/mail/deal',
  'notify' => ['growl', 'На Ваш email отправлено письмо для подтверждения'],
  'callback' => function(Provider $provider, $status){
    // Тут можно обработать ответ от провайдеров нотификаций
  }
]);
Notification::trigger(self::className(),'Request', $notification);
```

### With mirocow/yii2-queue             