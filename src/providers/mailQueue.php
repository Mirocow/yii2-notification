<?php
/**
 * Created by PhpStorm.
 * User: mirocow
 * Date: 25.08.14
 * Time: 3:51
 */

namespace mirocow\notification\providers;

use Yii;

$path = Yii::getAlias('@notification');

require_once($path . '/vendor/Rediska/library/Rediska.php');

class mailQueue
{

    public static $rediska = false;

    public $debug = false;

    public $config = [
        'servers' => [
            [
                'host' => 'localhost',
                'port' => 6379,
                'password' => '',
                'db' => 2,
            ],
        ],
    ];

    public function __construct()
    {

        self::$rediska = new \Rediska($this->config);

    }

    public function push($args = [])
    {

        $args['to'] = $this->debug ? $this->debug_mail : trim($args['to']);

        $list = new \Rediska_Key_List('emails_queue');

        try {
            $list[] = @serialize($args);

            return true;

        } catch (\Exception  $e) {

            return false;

        }
    }

    public function pop()
    {
        $list = new \Rediska_Key_List('emails_queue');
        if (count($list)) {
            return $list->pop();
        } else {
            return false;
        }
    }

    public static function getQueue()
    {
        return new \Rediska_Key_List('emails_queue');
    }

}

?>
