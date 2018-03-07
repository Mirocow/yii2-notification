<?php

namespace mirocow\notification\models;

use Yii;

/**
 * This is the model class for table "notification_status".
 *
 * @property integer $id
 * @property string $provider
 * @property string $event
 * @property string $params
 * @property string $status
 * @property string $update_at
 * @property string $create_at
 */
class NotificationStatus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'notification_status';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['params'], 'string'],
            [['update_at', 'create_at'], 'safe'],
            [['provider', 'event'], 'string', 'max' => 255],
            [['status'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'provider' => 'Provider',
            'event' => 'Event',
            'params' => 'Params',
            'status' => 'Status',
            'update_at' => 'Update At',
            'create_at' => 'Create At',
        ];
    }
}