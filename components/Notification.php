<?php

namespace mirocow\notification\components;

use Yii;
use yii\base\Event;

/**
 * Class Notification
 * @package mirocow\notification\components
 */
class Notification extends Event
{
    /** @var int */
    public $fromId;

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
     *
     */
    public function init() {
        if (!isset($this->fromId)) {
            $this->fromId = Yii::$app->user->identity->id;
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
