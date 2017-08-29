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
    public $fromId = 0;

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
    public $path = '';

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
        if (!$this->fromId) {
            $this->fromId = Yii::$app->user->identity->id;
        }
    }
}