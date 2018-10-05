<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "country".
 *
 * @property integer $id
 * @property string $name_ru
 * @property string $name_us
 *
 * @property Profile[] $profiles
 */
class Country extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'country';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name_ru', 'name_us'], 'required'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name_ru' => 'Name_ru',
            'name_us' => 'Name_us',
        ];
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfiles()
    {
        return $this->hasMany(Profile::className(), ['country_id' => 'id']);
    }

    public function getName()
    {
        switch(Yii::$app->language)
        {
            case 'ru':
                return $this->name_ru;
            case 'en':
                return $this->name_us;
            default:
                return $this->name_us;
        }
    }
}
