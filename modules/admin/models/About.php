<?php

namespace app\modules\admin\models;

use Yii;

/**
 * This is the model class for table "about".
 *
 * @property integer $id
 * @property string $title_ru
 * @property string $title_us
 * @property string $text_ru
 * @property string $text_us
 * @property string $image
 * @property integer $active
 */
class About extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'about';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title_ru', 'title_us'], 'required'],
            [['text_ru', 'text_us'], 'string'],
            [['active'], 'integer'],
            [['title_ru', 'title_us', 'image'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'title_ru' => 'Название Ru',
            'title_us' => 'Название Us',
            'text_ru' => 'Текст Ru',
            'text_us' => 'Текст Us',
            'image' => 'Image',
            'active' => 'Статус',
        ];
    }

    public function getText()
    {
        switch(Yii::$app->language)
        {
            case 'ru':
                return $this->text_ru;
            case 'en':
                return $this->text_us;
            default:
                return $this->text_us;
        }
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
