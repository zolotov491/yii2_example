<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * Signup form
 */
class FilterForm extends Model
{
    public $age;
    public $gender;
    public $growth;
    public $weight;
    public $eyeColor;
    public $children;
    public $smoking;
    public $countryId;
    public $languageId;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['age', 'safe'],
            [['languageId'], 'exist', 'skipOnError' => true, 'targetClass' => Language::className(), 'targetAttribute' => ['languageId' => 'id']],
            [['countryId'], 'exist', 'skipOnError' => true, 'targetClass' => Country::className(), 'targetAttribute' => ['countryId' => 'id']],
            [['gender', 'growth', 'weight', 'eyeColor', 'children',  'smoking'], 'string', 'max' => 255],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'age' => Yii::t('app', 'age'),
            'countryId' => Yii::t('app', 'Country'),
            'languageId' => Yii::t('app', 'Language'),
            'gender' => Yii::t('app', 'Gender'),
            'growth' => Yii::t('app', 'Growth'),
            'weight' => Yii::t('app', 'Weight'),
            'hairColor' => Yii::t('app', 'Hair Color'),
            'eyeColor' => Yii::t('app', 'Eye Color'),
            'children' => Yii::t('app', 'Children'),
            'smoking' => Yii::t('app', 'Smoking'),
        ];
    }
}
