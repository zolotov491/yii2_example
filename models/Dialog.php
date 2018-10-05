<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%dialog}}".
 *
 * @property int $id
 * @property int $user_id User created dialog
 * @property int $with_id User dialog with
 * @property int $last_message_id
 * @property int $created_at
 * @property int $updated_at
 *
 * @property User $with
 * @property User $user
 * @property Message[] $messages
 */
class Dialog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%dialog}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'with_id'], 'required'],
            [['user_id', 'with_id', 'last_message_id', 'created_at', 'updated_at'], 'integer'],
            [['with_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['with_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'with_id' => Yii::t('app', 'With ID'),
            'last_message_id' => Yii::t('app', 'Last Message ID'),
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
    public function getWith()
    {
        return $this->hasOne(User::className(), ['id' => 'with_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessages()
    {
        return $this->hasMany(Message::className(), ['dialog_id' => 'id'])
                ->orderBy(['id' => SORT_ASC]);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLastMessage()
    {
        return $this->hasOne(Message::className(), ['id' => 'last_message_id']);
    }
    
    /**
     * Check if user can star dialog with given user
     * @param int $with
     * @return boolean
     */
    public static function canStartDialog($with)
    {
        /** @var $identity \app\models\User */
        $identity = Yii::$app->user->identity;
        $user = User::findOne((int)$with);
        
        if($identity && $user) {
            /** @var $identityProfile \app\models\Profile */
            $identityProfile = $identity->profiles[0];
            /** @var $userProfile \app\models\Profile */
            $userProfile = $user->profiles[0];
            // Only if user has no same gender
            if($identityProfile && $userProfile
                    && $identityProfile->gender != $userProfile->gender) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Check if user has dialog with user
     * @param int $with user id
     */
    public static function hasDialog($with)
    {
        $count = self::find()
                ->where([
                    'and',
                    ['user_id' => Yii::$app->user->id],
                    ['with_id' => $with],
                ])
                ->orWhere([
                    'and',
                    ['with_id' => Yii::$app->user->id],
                    ['user_id' => $with],
                ])
                ->count();
        
        if($count > 0) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Check if user can send message to target dialog
     * This message only check if user has access to dialog
     * This function NOT check billing or banned user
     * @param int $dialog dialog id
     * @return boolean
     */
    public static function canSendMessage($dialog)
    {
        $id = Yii::$app->user->id;
        $count = (int) self::find()
                ->where(['id' => $dialog])
                ->andWhere(['or', 
                    ['user_id' => $id],
                    ['with_id' => $id],
                ])->count();
        
        if($count === 1) {
            return true;
        }
        
        return false;
    }
}
