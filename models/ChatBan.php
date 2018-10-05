<?php

namespace app\models;

use Yii;
use app\models\User;

/**
 * This is the model class for table "{{%chat_ban}}".
 *
 * @property int $id
 * @property int $who Who banned
 * @property int $whom Banned by whom
 */
class ChatBan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%chat_ban}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['who', 'whom'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'who' => Yii::t('app', 'Who'),
            'whom' => Yii::t('app', 'Whom'),
        ];
    }
    
    /**
     * User who has been banned
     * @return \yii\db\ActiveQuery
     */
    public function getWhoUser()
    {
        return $this->hasOne(User::class, ['id' => 'who']);
    }
    
    /**
     * User whom has been set ban
     * @return \yii\db\ActiveQuery
     */
    public function getWhomUser()
    {
        return $this->hasOne(User::class, ['id' => 'whom']);
    }
}
