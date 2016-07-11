<?php
/**
 * Created by PhpStorm.
 * User: mirocow
 * Date: 25.08.14
 * Time: 3:51
 */

namespace mirocow\notification\providers;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\redis\Connection;

class mailQueue extends Component
{
    public $debug = false;

    public $db = false;

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

    public function push($args = [])
    {
        $args['to'] = trim($args['to']);
        return $this->db->rpush($this->queue_name, @serialize($args));
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
