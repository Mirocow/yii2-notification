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

    /** @var array  */
    public $replyTo = [];

    /** @var array */
    public $phone = [];

    /** @var array  */
    public $token = [];

    /** @var string */
    public $subject = '';

    /** @var string  */
    public $notify = '';

    /** @var string */
    public $content = '';

    /** @var string */
    public $path;

    /** @var array */
    public $layouts = [
        'text' => '@common/mail/layouts/text',
        'html' => '@common/mail/layouts/html',
    ];

    /** @var array */
    public $view = [];

    /** @var string  */
    public $TextBody = '';

    /** @var string  */
    public $HtmlBody = '';

    /** @var array */
    public $attaches = [];

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

    public function __construct($config = [])
    {
        if (!empty($config)) {
            foreach ($config as $name => $value) {
                if(property_exists($this, $name)) {
                    $this->{$name} = $value;
                }
            }
        }
        $this->init();
    }

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
                throw new Exception("Sender email not found");
            }
        }

        if(!$this->notify){
            $this->notify = ['growl', $this->subject];
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
