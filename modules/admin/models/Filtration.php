<?php

namespace app\modules\admin\models;

use Yii;
use app\models\Language;
use app\models\Country;

class Filtration
{
    /**
     * @return array
     */
    public function getWeight(): array
    {
        $weight = [];
        if (Yii::$app->language === 'en') {
            // get lbs
            for ($kg = 45; $kg <= 180; $kg++) {
                $lb = round($kg * 2.2);
                $weight[$lb] = $lb;
            }
        } else {
            for ($kg = 45; $kg <= 180; $kg++) {
                $weight[$kg] = $kg;
            }
        }

        return $weight;
    }

    /**
     * @return array
     */
    public function getGrowth(): array
    {
        $growth = [];
        if (Yii::$app->language === 'en') {
            for ($i = 4; $i <= 7; $i++) {
                for ($j = 0; $j <= 9; $j++) {
                    $growth[$i . '.' . $j] = "$i.$j ' ";
                }
            }

        } else {
            for ($i = 150; $i <= 210; $i++) {
                $growth[$i] = "$i см";
            }
        }

        return $growth;
    }

    public function getDays()
    {
        $days = [
            null => Yii::t('app', 'Day')
        ];

        for ($i = 1; $i <= 31; $i++) {
            $days[$i] = $i;
        }

        return $days;
    }

    public function getMonths()
    {
        return [
            null => Yii::t('app', 'Month'),
            1 => Yii::t('app', 'January'),
            2 => Yii::t('app', 'February'),
            3 => Yii::t('app', 'March'),
            4 => Yii::t('app', 'April'),
            5 => Yii::t('app', 'May'),
            6 => Yii::t('app', 'June'),
            7 => Yii::t('app', 'July'),
            8 => Yii::t('app', 'August'),
            9 => Yii::t('app', 'September'),
            10 => Yii::t('app', 'October'),
            11 => Yii::t('app', 'November'),
            12 => Yii::t('app', 'December'),
        ];
    }

    /**
     * @return array
     */
    public function getLanguages(): array
    {
        $languages = [
            null => Yii::t('app', 'Select value')
        ];
        /** @var  $country Country */
        foreach (Language::find()->orderBy(['name_' . Yii::$app->language => SORT_ASC])->all() as $language) {
            $languages[$language->id] = $language->getName();
        }

        return $languages;
    }

    /**
     * @return array
     */
    public function getCountries(): array
    {

        $countries = [
            null => Yii::t('app', 'Select value')
        ];
        if (Yii::$app->language == 'ru') {
            /** @var  $country Country */
            foreach (Country::find()->orderBy(['name_ru' => SORT_ASC])->all() as $country) {
                $countries[$country->id] = $country->getName();
            }
        } else {
            /** @var  $country Country */
            foreach (Country::find()->orderBy(['name_us' => SORT_ASC])->all() as $country) {
                $countries[$country->id] = $country->getName();
            }
        }


        return $countries;
    }

    public function getYears()
    {
        $years = [
            null => Yii::t('app', 'Year')
        ];

        for ($i = 1992; $i >= 1938; $i--) {
            $years[$i] = $i;
        }

        return $years;
    }
}
