<?php
namespace mirocow\notification\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 */
class SimpleNotificationForm extends Model
{

    public $to;

    public $subject;

    public $message;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['subject', 'message'], 'required'],
            // rememberMe must be a boolean value
            [['subject', 'message', 'to'], 'string'],
            [['to'], 'safe'],
        ];
    }

}