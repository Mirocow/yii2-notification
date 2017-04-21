<?php
/**
 * Created by PhpStorm.
 * User: mirocow
 * Date: 25.08.14
 * Time: 3:51
 */

namespace mirocow\notification\providers;

use mirocow\notification\components\Provider;
use mirocow\notification\components\Notification;
use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\redis\Connection;

/**
 * Class mailQueue
 * @package mirocow\notification\providers
 */
class mailQueue extends Provider
{
    public $debug = false;

    /** @var Connection null */
    public $db = null;

    public $queue_name = 'emails_queue';

    public $config = [
        'hostname' => 'localhost',
        'port' => 6379,
        'database' => 0,
    ];

    public function init()
    {
        $this->db = new Connection($this->config);
        if(!$this->db){
            throw new InvalidConfigException(Yii::t('app', 'Driver queue incorrectly configured'));
        }
        $this->db->open();
    }

    /**
     * @param Notification $notification
     * @return mixed
     */
    public function send(Notification $notification)
    {
        if(empty($notification->to)) return;

        $params['to'] = trim($notification->to);
        return $this->db->rpush($this->queue_name, @serialize($params));
    }

    public function pop()
    {
        return $this->db->rpop($this->queue_name);
    }

    public function flushdb(){
        $this->db->flushdb();
    }

}

?>
