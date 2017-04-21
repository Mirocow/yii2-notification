<?php

namespace mirocow\notification;

use mirocow\notification\components\Notification;
use mirocow\notification\components\Provider;
use Yii;
use yii\base\BootstrapInterface;

class Module extends \yii\base\Module implements BootstrapInterface
{
    public $controllerNamespace = 'mirocow\notification\controllers';

    public $providers = [];

    private $_providers = [];

    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        foreach ($this->providers as $provider){
            if(empty($provider['events'])) continue;
            $this->attachEvents($provider);
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
        $status = $provider->send($notification);
        if (isset($notification->callback) && is_callable($notification->callback)) {
            call_user_func_array($notification->callback, [$provider, $status]);
        }
    }

    /**
     * @param $message
     * @param null $callback
     */
    public function send($params = [], $callback = null)
    {
        foreach ($this->providers as $name => $provider) {
            $provider = $this->provider($name);
            if(!$provider) continue;
            if(is_array($params)){
                $notification = new Notification;
                foreach ($params as $name => $param){
                    if(!isset( $notification->{$name})) continue;
                    $notification->{$name} = $param;
                }
                $status = $provider->send($notification);
            } elseif(is_a($params, 'Notification')){
                $status = $provider->send($params);
            } else {
                throw new \Exception();
            }

            if (is_callable($callback)) {
                call_user_func_array($callback, [$params, $status]);
            }
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
    public function attachEvents($provider)
    {
        foreach ($provider['events'] as $className => $events) {
            foreach ($events as $eventName) {
                Notification::on($className, $eventName, [$this, 'sendEvent'], ['provider' => $provider]);
            }
        }
    }

}