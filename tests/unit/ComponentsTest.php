<?php

use mirocow\notification\Module;
use mirocow\notification\components\JobEvent;
use mirocow\notification\components\Notification;
use mirocow\notification\models\NotificationStatus;

class ComponentsTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected $notification;

    protected function _before()
    {
        /* @var \common\modules\notification\Module $notification */
        $this->notification = \Yii::$app->getModule('notification');

        $this->notification->on(Module::EVENT_BEFORE_SEND, function (JobEvent $event){
            $event->isValid = true;
            $fileName = Yii::getAlias('@runtime/test-event.lock');
            file_put_contents($fileName, '');
        });
    }

    protected function _after()
    {
        foreach (glob(Yii::getAlias("@runtime/test-*.lock")) as $fileName) {
            unlink($fileName);
        }

        \Yii::$app->db->createCommand()->truncateTable('notification_status')->execute();
    }

    public function testCheckEvent()
    {

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
        $this->notification->sendEvent(new Notification($data));
        $file = Yii::getAlias('@runtime/test-event.lock');
        $this->assertFileExists($file);
        @unlink($file);
    }

    public function testCheckStatus()
    {
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
        $this->notification->sendEvent(new Notification($data));
        $this->assertNotEmpty(NotificationStatus::find()->one());
    }
}