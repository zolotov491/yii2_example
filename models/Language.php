<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "language".
 *
 * @property integer $id
 * @property string $name_ru
 * @property string $name_en
 *
 * @property LanguageProfile[] $languageProfiles
 * @property Profile[] $profiles
 */
class Language extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'language';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name_ru', 'name_en'], 'string', 'max' => 255],
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
            'name_en' => 'Name_en',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanguageProfiles()
    {
        return $this->hasMany(LanguageProfile::className(), ['language_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfiles()
    {
        return $this->hasMany(Profile::className(), ['id' => 'profile_id'])->viaTable('language_profile', ['language_id' => 'id']);
    }

    public function getName()
    {
        switch(Yii::$app->language)
        {
            case 'ru':
                return $this->name_ru;
            case 'en':
                return $this->name_en;
            default:
                return $this->name_en;
        }
    }
}
