<?php

namespace app\models;

use app\modules\admin\models\LanguageProficiency;
use Yii;

/**
 * This is the model class for table "language_profile".
 *
 * @property integer $id
 * @property integer $language_id
 * @property integer $profile_id
 * @property integer $language_proficiency_id
 *
 * @property Language $language
 * @property LanguageProficiency $languageProficiency
 * @property Profile $profile
 */
class LanguageProfile extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'language_profile';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['language_id', 'profile_id', 'language_proficiency_id'], 'integer'],
            [['language_id'], 'exist', 'skipOnError' => true, 'targetClass' => Language::className(), 'targetAttribute' => ['language_id' => 'id']],
            [['language_proficiency_id'], 'exist', 'skipOnError' => true, 'targetClass' => LanguageProficiency::className(), 'targetAttribute' => ['language_proficiency_id' => 'id']],
            [['profile_id'], 'exist', 'skipOnError' => true, 'targetClass' => Profile::className(), 'targetAttribute' => ['profile_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'language_id' => 'Language ID',
            'profile_id' => 'Profile ID',
            'language_proficiency_id' => 'Language Proficiency ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanguage()
    {
        return $this->hasOne(Language::className(), ['id' => 'language_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanguageProficiency()
    {
        return $this->hasOne(LanguageProficiency::className(), ['id' => 'language_proficiency_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['id' => 'profile_id']);
    }
}
