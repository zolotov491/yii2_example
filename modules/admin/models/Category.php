<?php

namespace app\modules\admin\models;

use Yii;

/**
 * This is the model class for table "category".
 *
 * @property integer $id
 * @property string $title_ru
 * @property string $title_us
 * @property integer $active
 * @property string $img
 * @property string $description_ru
 * @property string $description_us
 *
 * @property Service[] $services
 */
class Category extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description_ru', 'description_us'], 'string'],
            [['active'], 'integer'],
            [['title_ru', 'title_us'], 'required'],
            [['title_ru', 'img', 'title_us'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title_ru' => 'Название RU',
            'title_us' => 'Название US',
            'img' => 'Фотография',
            'active' => 'Active',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getServices()
    {
        return $this->hasMany(Service::className(), ['category_id' => 'id']);
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
                return $this->title_ru;
        }
    }

    public function getDescription()
    {
        switch(Yii::$app->language)
        {
            case 'ru':
                return $this->description_ru;
            case 'en':
                return $this->description_us;
            default:
                return $this->description_ru;
        }
    }



}
