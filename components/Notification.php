<?php

namespace mirocow\notification\components;

use Yii;
use yii\base\Event;
use yii\base\Exception;

/**
 * Class Notification
 * @package mirocow\notification\components
 */
class Notification extends Event
{
    /** @var int */
    public $fromId = null;

    /** @var array */
    public $toId = [];

    /** @var array */
    public $from = [];

    /** @var array  */
    public $to = [];

    /** @var array */
    public $phone = [];

    /** @var array  */
    public $token = [];

    /** @var string */
    public $subject = '';

    /** @var string */
    public $message = '';

    /** @var string */
    public $path;

    /** @var array */
    public $layouts = [
        'text' => '@common/mail/layouts/text',
        'html' => '@common/mail/layouts/html',
    ];

    /** @var array */
    public $view = [];

    /** @var array */
    public $params = [];

    /** @var array */
    public $push = [
      'aps' => [
        'alert' => 'Hi',
        'badge' => 1,
        'sound' => 'default',
        "link_url" => "https://google.com"
      ],
    ];

    /**
     * @throws Exception
     */
    public function init()
    {
        if (empty($this->from)) {
            if (!empty(\Yii::$app->params['supportEmail'])) {
                $this->from = [\Yii::$app->params['supportEmail'] => \Yii::$app->name];
                $this->fromId = 0;
            } else {
                throw new Exception("Sender object not found");
            }
        }
        if (empty($this->fromId)) {
            throw new Exception("Sender ID not found");
        }
    }

    /**
     * @return \ReflectionProperty[]
     */
    public function getAttributes()
    {
        $properties = [];

        $reflect = new \ReflectionClass($this);
        $props = $reflect->getProperties(\ReflectionProperty::IS_PUBLIC);

        foreach ($props as $prop) {
            $properties[$prop->name] = $this->{$prop->name};
        }

        return $properties;
    }
}
