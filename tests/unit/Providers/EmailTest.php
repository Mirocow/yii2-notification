<?php
namespace Providers;

use mirocow\notification\components\Notification;
use Yii;

class EmailTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    
    protected function _before()
    {
        foreach ($this->getFiles() as $fileName) {
            unlink($fileName);
        }
    }

    protected function _after()
    {
        foreach ($this->getFiles() as $fileName) {
            unlink($fileName);
        }
    }

    protected function getFiles()
    {
        return glob(Yii::getAlias("@runtime/mail/*.eml"));
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
                'providerName' => 'email',
            ],
        ];
        $notification->sendEvent(new Notification($data));
        $provider = $notification->provider('email');
        $this->assertNotEmpty($this->getFiles());
        $this->assertNotEmpty($provider->status);
    }
}