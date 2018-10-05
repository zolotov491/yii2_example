<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%message}}".
 *
 * @property int $id
 * @property int $sender_id Message sender user id
 * @property int $recipient_id Message recipient user id
 * @property int $dialog_id Message dialog
 * @property string $content Message body
 * @property boolean $view Message view flag
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Dialog $dialog
 * @property int $unreadCount
 * @property Profile $recipientProfile
 */
class Message extends \yii\db\ActiveRecord
{   
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%message}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sender_id', 'recipient_id', 'dialog_id', 'content', 'dialog_id'], 'required'],
            [['sender_id', 'recipient_id', 'created_at', 'updated_at'], 'integer'],
            [['content'], 'string'],
            [['dialog_id'], 'exist', 'skipOnError' => true, 'targetClass' => Dialog::className(), 'targetAttribute' => ['dialog_id' => 'id']],
            [['sender_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['sender_id' => 'id']],
            [['recipient_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['recipient_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'direction' => Yii::t('app', 'Direction'),
            'dialog_id' => Yii::t('app', 'Dialog ID'),
            'content' => Yii::t('app', 'Content'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDialog()
    {
        return $this->hasOne(Dialog::className(), ['id' => 'dialog_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSender()
    {
        return $this->hasOne(User::className(), ['id' => 'sender_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRecipient()
    {
        return $this->hasOne(User::className(), ['id' => 'recipient_id']);
    }
    
    /**
     * Get message recipient profile
     * @return Profile
     */
    public function getRecipientProfile()
    {
        return $this->recipient
                ->profiles[0];
    }

    /**
     * Get unread message count
     * @param int $dialogId
     * @return int
     */
    public function getUnreadCount($dialogId = 0)
    {
        $query = self::find()
                ->where(['recipient_id' => Yii::$app->user->id])
                ->andWhere(['view' => 0]);
        
        if($dialogId > 0)
        {
            $query->andWhere(['dialog_id' => $dialogId]);
        }
        
        return $query->count();
    }
}
