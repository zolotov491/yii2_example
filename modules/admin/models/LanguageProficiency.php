<?php

namespace app\modules\admin\models;

use app\models\LanguageProfile;
use Yii;

/**
 * This is the model class for table "language_proficiency".
 *
 * @property integer $id
 * @property string $name_ru
 * @property string $name_us
 *
 * @property LanguageProfile[] $languageProfiles
 */
class LanguageProficiency extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'language_proficiency';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name_ru', 'name_us'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name_ru' => 'Name Ru',
            'name_us' => 'Name Us',
        ];
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
                return $this->name_ru;
        }
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanguageProfiles()
    {
        return $this->hasMany(LanguageProfile::className(), ['language_proficiency_id' => 'id']);
    }
}
