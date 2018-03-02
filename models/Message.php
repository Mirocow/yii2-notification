<?php

namespace mirocow\notification\models;

use Yii;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * This is the model class for table "notification".
 *
 * @property integer $id
 * @property integer $from_id
 * @property integer $to_id
 * @property string $event
 * @property string $title
 * @property string $message
 * @property string $params
 * @property string $update_at
 * @property string $create_at
 */
class Message extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'notification';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
          [['from_id', 'to_id'], 'integer'],
          [['message', 'params'], 'string'],
          [['update_at', 'create_at'], 'safe'],
          [['title'], 'string', 'max' => 255],
          [['event'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
          'id' => 'ID',
          'from_id' => 'From Id',
          'to_id' => 'To Id',
          'event' => 'Event name',
          'title' => 'Title',
          'message' => 'Message',
          'params' => 'Json params',
          'update_at' => 'Update At',
          'create_at' => 'Create At',
        ];
    }

    /**
     * @param array $where
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function messages($where = [])
    {
        if(!$where) {
            $where = ['or', 'to_id' => Yii::$app->user->identity->id, 'from_id' => Yii::$app->user->identity->id];
        }

        return self::find()->where($where)->all();
    }

    /**
     * @param array $params
     */
    public function setParams($params = []){
        $params = ArrayHelper::merge($this->attributes, $params);
        $this->params = Json::encode($params);
    }

    /**
     * @return array|mixed
     */
    public function getParams(){
        $params = Json::decode($this->getAttribute('params'));
        if(!$params){
            $params = [];
        }
        return $params;
    }

    /**
     * @param string $name
     * @return array|mixed
     * @throws Exception
     */
    public function __get($name) {

        if($name == 'attributes'){
            return $this->getAttributes();
        }

        // If name is model attribute
        $attributes = $this->attributes();
        if(in_array($name, $attributes)){
            return parent::__get($name);
        }

        // If name is param of model`s attribute by name params
        $params = $this->getParams();
        if (isset($params[$name])) {
            return $params[$name];
        }

        throw new Exception();

    }
}
