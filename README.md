# Yii 2.0 Notification


## Install

```sh
composer require --prefer-dist "mirocow/yii2-notification"
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
                'config' => [
                  'hostname' => 'localhost',
                  'port' => 6379,
                  //'password' => '',
                  'database' => 0,
                  ],
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

          ]
        ],
    ]        
```

## Using

```php
    $email = [
      'to' => 'notification@mirocow.com
      //'from_name' => '',
      'subject' => 'Subject example
      'message' => '<h1>Content example</h1>'
    ];
    
    /* @var Notification $notification */
    $notification = Yii::$app->getModule('notification');    
    
    $notification->sendMessage([$email], function ($mail, $status) use (&$errors) {

        $errors[] = Yii::t('core', 'Email {mail} sent', [
          'mail' => $mail['to']
        ]);

    });
```

## Run console

```php
php ./yii notification/cron/send
```

## Simle notification form

```php
http://host-name.com/notification/simple/send/
```