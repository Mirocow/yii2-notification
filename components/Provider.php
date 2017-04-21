<?php

namespace mirocow\notification\components;

use yii\base\Component;
use yii\base\Exception;

abstract class Provider extends \yii\base\Component
{
    public $config = [];

    /** @var array */
    public $events = [];

    public function send(Notification $notification){
        throw new Exception('Not found notification handler');
    }
}