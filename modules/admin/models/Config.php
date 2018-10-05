<?php

namespace app\modules\admin\models;

use Yii;

/**
 * This is the model class for table "config".
 *
 * @property integer $id
 * @property string $title_ru
 * @property string $title_us
 * @property integer $gallery
 * @property integer $profile
 * @property  string phone_ua
 * @property  string phone_us
 * @property  string phone_pl
 */
class Config extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'config';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['gallery', 'profile'], 'integer'],
            [['phone_ua', 'phone_us', 'title_ru', 'title_us', 'phone_pl'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'title_ru' => 'Слоган Ru',
            'title_us' => 'Слоган En',
            'gallery' => 'Gallery',
            'profile' => 'Profile',
            'phone_ua' => 'Телефон Украина',
            'phone_us' => 'Телефон Америка',
            'phone_pl' => 'Телефон Польша'
        ];
    }

    public function getTitle()
    {
        switch(Yii::$app->language)
        {
            case 'ru':
                return $this->title_ru;
            case 'en':
                return $this->title_us;
            default:
                return $this->title_us;
        }
    }
}
