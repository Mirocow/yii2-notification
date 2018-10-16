# Yii 2.0 Notification

[![Latest Stable Version](https://poser.pugx.org/mirocow/yii2-notification/v/stable)](https://packagist.org/packages/mirocow/yii2-notification) [![FOSSA Status](https://app.fossa.io/api/projects/git%2Bgithub.com%2FMirocow%2Fyii2-notification.svg?type=shield)](https://app.fossa.io/projects/git%2Bgithub.com%2FMirocow%2Fyii2-notification?ref=badge_shield)

[![Latest Unstable Version](https://poser.pugx.org/mirocow/yii2-notification/v/unstable)](https://packagist.org/packages/mirocow/yii2-notification) 
[![Total Downloads](https://poser.pugx.org/mirocow/yii2-notification/downloads)](https://packagist.org/packages/mirocow/yii2-notification) [![License](https://poser.pugx.org/mirocow/yii2-notification/license)](https://packagist.org/packages/mirocow/yii2-notification)

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

              // E-mail
              'email' => [
                'class' => 'mirocow\notification\providers\email',
                'emailViewPath' => '@common/mail',
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
    
    /* @var \mirocow\notification\Module $sender */
    $sender = Yii::$app->getModule('notification');
    
    $notification = new Notification([
      'from' => [\Yii::$app->params['supportEmail'] => \Yii::$app->name . ' robot'],
      'to' => $deal['userSeller']['email'], // строка или массив
      'toId' => $deal['userSeller']['id'], // строка или массив
      'phone' => $deal['userSeller']['phone_number'], // строка или массив
      'subject' => "\"{$deal['userBuyer']['nameForOut']}\" предлагает вам сделку для \"{$deal['ads']['product']->getName()}\"",
      'token' => 'TOKEN',
      'content' => "",
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
           
    $sender->sendEvent($notification);
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
  'content' => "",
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

```php
    \Yii::$app->queue->getChannel()->push(new MessageModel([
        'worker' => 'notification',
        'method' => 'action',
        'arguments' => [
            'triggerClass' => self::class,
            'methodName' => 'Subscribe',
            'arguments' => [
                'param' => 'value'
            ],
        ],
    ]), 30);
```

## Tests

```bash
$ ./vendor/bin/codecept -c ./vendor/mirocow/yii2-notification run unit
```


## License
[![FOSSA Status](https://app.fossa.io/api/projects/git%2Bgithub.com%2FMirocow%2Fyii2-notification.svg?type=large)](https://app.fossa.io/projects/git%2Bgithub.com%2FMirocow%2Fyii2-notification?ref=badge_large)