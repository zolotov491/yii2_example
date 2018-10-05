<?php

namespace app\modules\admin\models;

use Yii;

/**
 * This is the model class for table "blog".
 *
 * @property integer $id
 * @property string $title_ru
 * @property string $title_us
 * @property string $short_description_ru
 * @property string $short_description_us
 * @property string $text_ru
 * @property string $text_us
 * @property string $img
 * @property integer $active
 */
class Blog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'blog';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title_ru', 'title_us', 'text_ru', 'text_us', 'short_description_ru', 'short_description_us'], 'required'],
            [['text_ru', 'text_us'], 'string'],
            [['short_description_ru', 'short_description_us'], 'string', 'max' => '91'],
            [['active'], 'integer'],
            [['title_ru', 'title_us',], 'string', 'max' => 41],
            [['img'], 'image', 'skipOnEmpty' => true, 'minWidth' => 270, 'minHeight' => 270, 'maxSize' => 1000 * 1024, 'extensions' => 'png, jpg',
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'title_ru' => 'Название Ru',
            'title_us' => 'Title En',
            'short_description_ru' => 'Краткое описание Ru',
            'short_description_us' => 'Short description En',
            'text_ru' => 'Текст Ru',
            'text_us' => 'Text En',
            'img' => 'Img',
            'active' => 'Статус',
        ];
    }


    public function getShortDescription()
    {
        switch (Yii::$app->language) {
            case 'ru':
                return $this->short_description_ru;
            case 'en':
                return $this->short_description_us;
            default:
                return $this->short_description_us;
        }
    }

    public function getText()
    {
        switch (Yii::$app->language) {
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
        switch (Yii::$app->language) {
            case 'ru':
                return $this->title_ru;
            case 'en':
                return $this->title_us;
            default:
                return $this->title_us;
        }
    }

}
