<?php
namespace Providers;

use mirocow\notification\components\Notification;
use Yii;

class NotifyTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    
    protected function _before()
    {
    }

    protected function _after()
    {
        \Yii::$app->db->createCommand()->truncateTable('notification_status')->execute();
    }

    // tests
    public function testSend()
    {
        /* @var \common\modules\notification\Module $notification */
        $notification = \Yii::$app->getModule('notification');
        $data = [
            'name' => 'simple',
            'from' => \Yii::$app->params[ 'noreplyEmail' ],
            'to' => \Yii::$app->params[ 'noreplyEmail' ],
            'message' => '',
            'subject' => "Test subject",
            'path' => '@app/mail',
            'view' => ['html' => 'simple-html', 'text' => 'simple-text'],
            'layouts' => [
                'text' => '@app/mail/layouts/text',
                'html' => '@app/mail/layouts/html',
            ],
            'params' => [
                'content_subject' => 'Test subject',
            ],
            'data' => [
                'providerName' => 'notify',
            ],
        ];
        $notification->sendEvent(new Notification($data));
        $this->assertNotEmpty(Yii::$app->session->getAllFlashes());
    }
}