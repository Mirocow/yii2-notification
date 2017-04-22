<?php

namespace mirocow\notification\components;

use yii\base\Event;
use Yii;

/**
 * Class Notification
 * @package mirocow\notification\components
 */
class Notification extends Event
{
    /** @var int  */
    public $fromId = 0;

    /** @var array  */
    public $toId = [];

    /** @var array  */
    public $from = [];

    /** @var array  */
    public $to = [];

    /** @var array  */
    public $phone = [];

    /** @var array  */
    public $token = [];

    /** @var string  */
    public $subject = '';

    /** @var string  */
    public $message = '';

    /** @var string  */
    public $path = '';

    /** @var array  */
    public $view = [];

    /** @var \Closure */
    public $callback = null;

    /** @var array  */
    public $params = [];

    public function init()
    {
        $this->fromId = Yii::$app->user->identity->id;
    }
}