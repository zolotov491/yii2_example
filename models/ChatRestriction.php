<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%chat_restriction}}".
 *
 * @property int $id
 * @property string $description patter description
 * @property resource $regex
 */
class ChatRestriction extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%chat_restriction}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['regex', 'description'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'description' => 'Описание',
            'regex' => Yii::t('app', 'Regex'),
        ];
    }
}
