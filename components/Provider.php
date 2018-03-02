<?php

namespace mirocow\notification\components;

use yii\base\Component;
use yii\base\Exception;

abstract class Provider extends \yii\base\Component
{
    /** @var array  */
    public $config = [];

    /** @var array */
    public $events = [];

    /** @var string|array */
    public $status = [];

    public function send(Notification $notification){
        throw new Exception('Not found notification handler');
    }
}