<?php

namespace mirocow\notification;

use Yii;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'mirocow\notification\controllers';

    public $providers = [];

    private $_providers = [];

    public $config = [];

    public $emailViewPath = '';

    public $emailView = 'email-base.tpl.php';

    private $provider = null;

    public function init()
    {
        Yii::setAlias('@' . $this->id, __DIR__);

        Yii::setAlias('@' . $this->id . '/tpl', __DIR__ . '/tpl');

        parent::init();

    }

    public function provider($name)
    {

        if (!isset($this->_providers[$name])) {
            if (isset($this->providers[$name])) {
                $params = $this->providers[$name];
                $class = Yii::getAlias($params['class']);
                $provider = new $class;

                foreach ($params['config'] as $param_name => $param_value) {
                    if (isset($provider->config[$param_name])) {

                        $provider->config[$param_name] = $param_value;

                    }
                }

                $this->provider = $provider;

                $this->_providers[$name] = $this;

            }
        }

        return $this->_providers[$name];

    }

    public function send($params = [], $method = 'send')
    {

        if ($this->provider) {

            if (method_exists($this->provider, $method)) {
                return call_user_func_array([$this->provider, $method],
                    [$params]);
            }

        }

        return false;

    }

    public function getTemplate($template, $params = [])
    {

        $template = Yii::getAlias($this->emailViewPath . '/' . $template . '.tpl.php');

        $view = Yii::$app->getView();

        $content = $view->renderFile($template, $params);

        $content = str_replace(array_keys($params), array_values($params),
            $content);

        return $content;

    }

    public function sendMessage($mails, $callBackStatus = null)
    {

        if ($mails) {

            /* @var Notification $notification */
            $notification = Yii::$app->getModule('notification');

            foreach ($mails as $mail) {

                $status = $notification->provider('mailQueue')
                    ->send($mail, 'push');

                if (!$status) {

                    // Наш редис упал
                    /*$notification->provider('smsc')
                        ->send([
                            'phone' => '',
                            'message' => 'Redis server failure'
                        ]);*/

                    // Отправляем письмо пользователю
                    $status = $notification->provider('email')->send($mail);

                }

                if (is_callable($callBackStatus)) {

                    call_user_func_array($callBackStatus, [$mail, $status]);

                }

            }

            return true;

        }
    }

}
