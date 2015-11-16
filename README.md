
## Install


## Configurate

```php
    'modules' => [
        // Notification by providers
        'notification' => [
          'class' => 'mirocow\notification\Module',
          'emailViewPath' => '@app/email',
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
                  'servers' => [
                    [
                      'host' => 'localhost',
                      'port' => 6379,
                      //'password' => '',
                      'db' => 2,
                    ],
                  ],
                ]
              ],

              // E-mail
              'email' => [
                'class' => 'mirocow\notification\providers\email',
                'config' => [
                  'from' => ['request@vse-avtoservisy.ru' => 'vse-avtoservisy'],
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