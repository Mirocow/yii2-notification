<?php

namespace mirocow\notification\components;

use yii\base\Exception;
use yii\helpers\Json;

abstract class Provider extends \yii\base\Component
{
    /** @var array */
    public $config = [];

    /** @var array */
    public $events = [];

    /** @var string|array */
    public $status = [];

    /** @var array */
    public $errors = [];

    /** @var bool */
    public $enabled = true;

    /**
     * @param Notification $notification
     *
     * @throws Exception
     */
    public function send(Notification $notification)
    {
        throw new Exception('Not found notification handler');
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        $status = !empty($this->errors)? $this->errors: $this->status;
        return Json::encode($status);
    }

}