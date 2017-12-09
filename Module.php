<?php

namespace mirocow\notification;

use mirocow\notification\components\JobEvent;
use mirocow\notification\components\Notification;
use mirocow\notification\components\Provider;
use Yii;
use yii\base\BootstrapInterface;

class Module extends \yii\base\Module implements BootstrapInterface
{
    const EVENT_BEFORE_SEND = 'beforeSend';

    const EVENT_AFTER_SEND = 'afterSend';

    public $controllerNamespace = 'mirocow\notification\controllers';

    public $providers = [];

    private $_providers = [];

    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        foreach ($this->providers as $providerName => $provider){
            if(empty($provider['events'])) continue;
            $this->attachEvents($providerName, $provider);
        }
    }

    /**
     * @param Notification $notification
     */
    public function sendEvent(Notification $notification)
    {
        /** @var Provider $provider */
        $provider = Yii::createObject($notification->data['provider']);
        if(!$provider) return;

        $event = new JobEvent([
          'provider' => $notification->data['providerName'],
          'event' => $notification->name,
          'params' => $notification,
        ]);

        $this->trigger(self::EVENT_BEFORE_SEND, $event);

        if(!$event->isValid){
            return;
        }

        try {
            $provider->send($notification);
            $event->status = $provider->status;
            $this->trigger(self::EVENT_AFTER_SEND, $event);
        } catch (\Exception $e){
            \Yii::error($e, __METHOD__);
            throw $e;
        }
    }

    /**
     * @param $name
     * @return mixed
     */
    public function provider($name)
    {
        if (!isset($this->_providers[$name])) {
            if (isset($this->providers[$name])) {
                $this->_providers[$name] = Yii::createObject($this->providers[$name]);
            }
        }
        return $this->_providers[$name];
    }

    /**
     * @param $provider
     */
    public function attachEvents($providerName, $provider)
    {
        foreach ($provider['events'] as $className => $events) {
            foreach ($events as $eventName) {
                Notification::on($className, $eventName, [$this, 'sendEvent'], ['providerName' => $providerName, 'provider' => $provider]);
            }
        }
    }

    function class_basename($class)
    {
        $class = is_object($class) ? get_class($class) : $class;

        return basename(str_replace('\\', '/', $class));
    }

}
