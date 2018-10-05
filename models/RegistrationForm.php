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
class RegistrationForm extends Model
{
    public $email;
    public $password;
    public $name;
    public $gender;
    public $surname;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['gender', 'name', 'surname'], 'string', 'max' => 255],
            [['email'], 'required'],
            ['email', 'trim'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => User::className(), 'message' => Yii::t('app', 'This email address has already been taken.')],

            ['password', 'required'],
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
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        $user = new User();
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();

        return $user->save() ? $user : null;
    }
}
