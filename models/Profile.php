<?php

namespace app\models;

use app\modules\admin\models\Reviews;
use app\modules\admin\models\Order;
use yii\db\ActiveRecord;
use Yii;
use yii\behaviors\SluggableBehavior;
use yii\db\Expression;
use app\models\User;

/**
 * This is the model class for table "profile".
 *
 * @property integer $id
 * @property string $name
 * @property string $surname
 * @property integer $user_id
 * @property integer $country_id
 * @property string $gender
 * @property integer $day
 * @property integer $month
 * @property integer $year
 * @property integer $phone
 * @property string $skype
 * @property string $viber
 * @property string $social_network_address
 * @property string $how_did_find_out_about_us
 * @property string $hobbies_and_interests
 * @property integer $growth
 * @property integer $growth_ft
 * @property integer $weight
 * @property integer $weight_lbs
 * @property integer $age
 * @property string $hair_color
 * @property string $eye_color
 * @property string $education
 * @property string $specialty
 * @property string $profession
 * @property string $language_proficiency
 * @property string $marital_status
 * @property string $children
 * @property string $count_children
 * @property string $want_children
 *
 * @property string $hobbies_and_interests_en
 * @property string $about_me_en
 * @property string $requirements_from_partner_en
 * @property string $profession_en
 * @property string $specialty_en
 * @property string $name_en
 *
 * @property string $alcohol_consumption
 * @property string $getFullName
 * @property string $smoking
 * @property string $about_me
 * @property string $inhabited_locality
 * @property string $requirements_from_partner
 *
 * @property LanguageProfile[] $languageProfiles
 * @property Language[] $languages
 * @property Photo[] $photos
 * @property Order[] $orders
 * @property Reviews[] $reviews
 * @property Country $country
 * @property User $user
 */
class Profile extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'profile';
    }



    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                ],
                'value' => new Expression('NOW()'),
            ],
            'slug' => [
                'class' => 'Zelenin\yii\behaviors\Slug',
                'slugAttribute' => 'name_en',
                'attribute' => 'name',
                // optional params
                'ensureUnique' => false,
                'replacement' => ' ',
                'lowercase' => false,
                'immutable' => false,
                // If intl extension is enabled, see http://userguide.icu-project.org/transforms/general.
                'transliterateOptions' => 'Russian-Latin/BGN; Any-Latin; Latin-ASCII; NFD; [:Nonspacing Mark:] Remove; NFC;'
            ]
        ];
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'growth_ft', 'growth'], 'safe'],
            [['country_id', 'day', 'month', 'year', 'name', 'surname', 'name_en',], 'required'],
            [['user_id', 'country_id', 'day', 'month', 'year', 'weight', 'weight_lbs', 'phone', 'age'], 'integer'],
            [['gender', 'skype', 'viber', 'social_network_address',
                'how_did_find_out_about_us', 'hobbies_and_interests', 'hobbies_and_interests_en', 'hair_color', 'eye_color', 'education', 'count_children', 'specialty', 'specialty_en',
                'profession', 'profession_en', 'marital_status', 'children', 'want_children', 'alcohol_consumption', 'smoking', 'inhabited_locality'], 'string', 'max' => 255],
            [['name', 'name_en'], 'string', 'max' => 16],
            [['surname'], 'string', 'max' => 30],
            [['about_me', 'about_me_en', 'requirements_from_partner', 'requirements_from_partner_en'], 'string', 'max' => 500],
            [['country_id'], 'exist', 'skipOnError' => true, 'targetClass' => Country::className(), 'targetAttribute' => ['country_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'id',
            'birthDay' => 'День рождения',
            'fullName' => 'Имя',
            'email' => 'Почта',
            'countryName' => 'Страна',
            'name' => Yii::t('app', 'Name'),
            'surname' => Yii::t('app', 'Surname'),
            'country_id' => Yii::t('app', 'Country'),
            'gender' => Yii::t('app', 'Gender'),
            'date_of_birth' => Yii::t('app', 'Date Of Birth'),
            'phone' => Yii::t('app', 'Phone:'),
            'day' => Yii::t('app', 'Day'),
            'month' => Yii::t('app', 'Month'),
            'year' => Yii::t('app', 'Year'),
            'skype' => 'Skype',
            'viber' => 'Viber',
            'inhabited_locality' => 'Населенный пункт',
            'social_network_address' => Yii::t('app', 'Social Network Address'),
            'how_did_find_out_about_us' => Yii::t('app', 'How did you hear about us ?'),
            'growth' => Yii::t('app', 'Growth'),
            'weight' => Yii::t('app', 'Weight'),
            'hair_color' => Yii::t('app', 'Hair color'),
            'eye_color' => Yii::t('app', 'Eye color'),
            'education' => Yii::t('app', 'Education'),
            'specialty' => Yii::t('app', 'Specialty'),
            'profession' => Yii::t('app', 'Profession'),
            'marital_status' => Yii::t('app', 'Marital status'),
            'children' => Yii::t('app', 'Children'),
            'want_children' => Yii::t('app', 'Want children ?'),
            'hobbies_and_interests' => Yii::t('app', 'Hobbies and interests'),
            'alcohol_consumption' => Yii::t('app', 'Alcohol consumption'),
            'smoking' => Yii::t('app', 'Smoking'),
            'about_me' => Yii::t('app', 'About Me'),
            'age' => Yii::t('app', 'Age'),
            'requirements_from_partner' => Yii::t('app', 'Requirements from partner'),
            'hobbies_and_interests_en' => 'Hobbies And Interests En',
            'about_me_en' => 'About Me En',
            'requirements_from_partner_en' => 'Requirements From Partner En',
            'profession_en' => 'Profession En',
            'specialty_en' => 'Specialty En',
            'name_en' => 'Name En',
            'created_at' => 'Дата',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(Country::className(), ['id' => 'country_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanguageProfiles()
    {
        return $this->hasMany(LanguageProfile::className(), ['profile_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanguages()
    {
        return $this->hasMany(Language::className(), ['id' => 'language_id'])->viaTable('language_profile', ['profile_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['profile_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReviews()
    {
        return $this->hasMany(Reviews::className(), ['profile_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhotos()
    {
        return $this->hasMany(Photo::className(), ['profile_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }


    public function getNineRandomProfiles()
    {
        return self::find()
            ->joinWith('user')
            ->where(['user.status' => 10])
            ->orderBy(['user_id' => SORT_DESC]);
    }

    public function getFullName()
    {
        if (Yii::$app->language == 'en') {
            return $this->name_en;
        }

        return $this->name;
    }

    public function getEmail()
    {
        return $this->user->email;
    }


    /**
     * @return int|null
     */
    public function getAge()
    {
        if (!empty($this->year) && !empty($this->month) && !empty($this->day)) {
            $date = $this->year . '-' . $this->month . '-' . $this->day;
            $birthday = new \DateTime($date);

            return (new \DateTime)->diff($birthday)->y;
        }

        return null;
    }

    /**
     * @param $minAge
     * @param $maxAge
     * @param $minWeight
     * @param $maxWeight
     * @param $minGrowth
     * @param $maxGrowth
     * @param $gender
     * @param $smoking
     * @param $children
     * @param $eyeColor
     * @param $countryId
     * @param $languageId
     * @return $this
     */
    public function getProfiles($minAge, $maxAge, $minWeight, $maxWeight, $minGrowth,
                                $maxGrowth, $gender, $smoking, $children, $eyeColor, $countryId, $languageId)
    {
        $query = self::find()
            ->joinWith('user')
            ->where(['gender' => $gender, 'user.status' => 10])
            ->joinWith('languages')
            ->andFilterWhere([
                'language_id' => $languageId,
                'country_id' => $countryId,
                'smoking' => $smoking,
                'count_children' => $children,
                'eye_color' => $eyeColor
            ]);

        $query->andFilterWhere(['AND', ['>=', 'age', $minAge], ['<=', 'age', $maxAge]]);

        if (Yii::$app->language == 'en') {
            // foot  filter
            $query->andFilterWhere(['AND', ['>=', 'weight_lbs', $minWeight], ['<=', 'weight_lbs', $maxWeight]]);
            // inch filter
            $query->andFilterWhere(['AND', ['>=', 'growth_ft', $minGrowth], ['<=', 'growth_ft', $maxGrowth]]);
        } else if (Yii::$app->language == 'ru'){
             $query->andFilterWhere(['AND', ['>=', 'growth', $minGrowth], ['<=', 'growth', $maxGrowth]]);
            $query->andFilterWhere(['AND', ['>=', 'weight', $minWeight], ['<=', 'weight', $maxWeight]]);
            
           
        }

        $query->orderBy(['user_id' => SORT_DESC]);
        return $query;
    }

    public function getCountryName()
    {
        $country = $this->country;

        return $country ? $country->name : '';
    }


    public function getMonths()
    {
        return [
            0 => null,
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
     * @return string
     */
    public function getBirthday(): string
    {
        $months = $this->getMonths();

        return $this->day . ' ' . $months[$this->month] . ' ' . $this->year;
    }

    public function getAboutMe()
    {
        if (Yii::$app->language == 'en') {
            return $this->about_me_en;
        }

        return $this->about_me;
    }

    public function getHobbiesAndInterests()
    {
        if (Yii::$app->language == 'en') {
            return $this->hobbies_and_interests_en;
        }

        return $this->hobbies_and_interests;
    }

    public function getRequirementsFromPartner()
    {
        if (Yii::$app->language == 'en') {
            return $this->requirements_from_partner_en;
        }

        return $this->requirements_from_partner;
    }

    public function getSpecialty()
    {
        if (Yii::$app->language == 'en') {
            return $this->specialty_en;
        }

        return $this->specialty;

    }

    public function getProfession()
    {
        if (Yii::$app->language == 'en') {
            return $this->profession_en;
        }

        return $this->profession;
    }

}
