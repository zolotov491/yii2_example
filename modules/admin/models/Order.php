<?php

namespace app\modules\admin\models;

use app\models\Profile;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "order".
 *
 * @property integer $id
 * @property integer $profile_id
 * @property integer $service_id
 * @property integer $payment_status
 * @property integer $active
 *
 * @property Service $service
 * @property Profile $profile
 */
class Order extends \yii\db\ActiveRecord
{
    const LIQPAY = 'liqpay';
    const ORDERED = 'ordered';

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                ],
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['active'], 'integer'],
            [['payment_status'], 'string'],
            [['created_at'], 'safe'],
            [['profile_id', 'service_id'], 'required'],
            [['profile_id', 'service_id',], 'integer'],
            [['service_id'], 'exist', 'skipOnError' => true, 'targetClass' => Service::className(), 'targetAttribute' => ['service_id' => 'id']],
            [['profile_id'], 'exist', 'skipOnError' => true, 'targetClass' => Profile::className(), 'targetAttribute' => ['profile_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'profile_id' => 'Profile id',
            'service_id' => 'Service ID',
            'payment_status' => 'Статус',
            'active' => 'Active',
            'profileName' => 'Имя',
            'serviceName' => 'Услуга',
            'paymentSum'  => 'Сумма',
            'created_at' => 'Дата',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getService()
    {
        return $this->hasOne(Service::className(), ['id' => 'service_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['id' => 'profile_id']);
    }
    
    public function getProfileName()
    {
        $profile = $this->profile;

        return $profile ? $profile->getFullName() : '';
    }

    public function getPaymentSum()
    {
        $service = $this->service;

        return $service ? $service->price : '';
    }

    public function getServiceName()
    {
        $service = $this->service;

        return $service ? $service->title_ru : '';
    }
}
