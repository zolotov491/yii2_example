<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%chat_settings}}".
 *
 * @property int $id
 * @property string $name
 * @property resource $value
 */
class ChatSettings extends \yii\db\ActiveRecord
{
    const SETTINGS_PRICE = 'price_month';
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%chat_settings}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['value'], 'string'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'value' => Yii::t('app', 'Value'),
        ];
    }
}
