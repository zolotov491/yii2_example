<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * @property integer $gender
 *
 */

/**
 * Class RegistrationForm
 * @package app\models
 */
class FastRegistrationForm extends Model
{
    public $name;
    public $surname;
    public $gender;
    public $email;
    public $password;
    public $phone;
    public $day;
    public $month;
    public $year;
    public $country;
    public $skype;
    public $viber;
    public $howDidFindOutAboutUs;
    public $socialAddress;
    public $growth;
    public $weight;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'surname'], 'string', 'max' => 16],
            [['surname'], 'string', 'max' => 30],
            [['gender', 'viber', 'socialAddress', 'skype', 'howDidFindOutAboutUs'], 'string', 'max' => 255],
            [['country', 'day', 'month', 'phone', 'year', 'weight'], 'integer'],
            [['growth'], 'double'],
            ['password', 'string', 'min' => 6],
            [['email', 'name', 'surname', 'password','phone',  'day', 'year', 'month', 'growth', 'weight', 'country'], 'required', 'message' => Yii::t('app', 'field is required!')],
            ['email', 'trim'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => User::className(), 'message' => Yii::t('app', 'This email address has already been taken.')],
            ['password', 'string', 'min' => 6],
        ];
    }

    public function attributeLabels()
    {
        return [
            'password' => Yii::t('app', 'Password'),
            'email' => Yii::t('app', 'Email'),
        ];
    }

    /**
     * @return User|bool
     */
    public function signup()
    {
        $user = new User();
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();

        return $user->save() ? $user : false;
    }
}
