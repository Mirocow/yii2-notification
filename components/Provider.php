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

    /** @var string|array */
    public $errors = [];

    /** @var bool */
    public $enabled = true;

    /**
     * @param Notification $notification
     * @throws Exception
     */
    public function send(Notification $notification){
        throw new Exception('Not found notification handler');
    }

    /**
     * @return array|string
     */
    public function getStatus()
    {
        return isset($this->errors)? $this->errors: $this->status;
    }

}