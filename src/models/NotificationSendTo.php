<?php

namespace mirocow\notification\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "tbl_request_send_to".
 *
 * @property integer $id
 * @property integer $request_id
 * @property integer $user_id
 * @property integer $status
 * @property string $data
 *
 * @property Request $request
 */
class NotificationSendTo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_notification_send_to';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['request_id', 'user_id', 'status'], 'integer'],
            [['create_time'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'status' => 'Статус',
            'data' => 'Данные на отсылку',
            'create_time' => 'Дата нотификации',
        ];
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => \yii\behaviors\TimestampBehavior::className(),
                'createdAtAttribute' => 'create_time',
                'updatedAtAttribute' => false,
                'value' => new \yii\db\Expression('NOW()'),
            ],

        ];
    }

    /**
     * @return \yii\db\ActiveRelation
     */
    public function getRequest()
    {
        return $this->hasOne(Request::className(), ['id' => 'request_id']);
    }

    public static function log($user_id, $status = 0, $data)
    {

        $log = new self;
        $log->user_id = $user_id;
        $log->status = $status;
        $log->data = serialize($data);
        $log->save(false);

    }
}
