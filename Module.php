<?php

namespace mirocow\notification;

use mirocow\notification\components\JobEvent;
use mirocow\notification\components\Notification;
use mirocow\notification\components\Provider;
use mirocow\notification\models\NotificationStatus;
use Yii;
use yii\base\BootstrapInterface;
use yii\db\Expression;
use yii\helpers\Json;

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
            $this->setProviderStatus($notification);
            $provider->send($notification);
            if($provider->status) {
                $this->setProviderStatus($notification, $provider->status);
            } else {
                $this->setProviderStatus($notification, 'Dont worked');
            }
            $event->status = $provider->status;
            $this->trigger(self::EVENT_AFTER_SEND, $event);
        } catch (\Exception $e){
            $this->setProviderStatus($notification, $e->getMessage());
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

    private function setProviderStatus(Notification $notification, $ret = null)
    {
        $providerName = $notification->data['providerName'];

        $event = $notification->name;

        /** @var NotificationStatus $status */
        $status = NotificationStatus::find()
            ->where(
                [
                    'provider' => $providerName,
                    'event' => $event,
                    //'status' => null,
                    'update_at' => null,
                ]
            )
            ->one();
        if (!$status) {
            $status = new NotificationStatus;
            $status->provider = $providerName;
            $status->event = $event;
            $status->params = Json::encode($notification->getAttributes());
        } else {
            $status->update_at = new Expression('CURRENT_TIMESTAMP');
            $status->status = Json::encode($ret);
        }

        $status->save();
    }

}
