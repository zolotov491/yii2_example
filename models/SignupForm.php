<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
* @property integer $gender
 *
 */
class SignupForm extends Model
{
    public $email;
    public $password;
    public $name;
    public $gender;
    public $surname;
    public $confirmRules;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['gender'], 'string', 'max' => 255],
            ['email', 'trim'],
            [['email', 'password'], 'required'],
            ['confirmRules', 'boolean'],
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
        if ($this->validate()) {
            $user = new User();
            $user->email = $this->email;
            $user->setPassword($this->password);
            $user->generateAuthKey();

            return $user->save() ? $user : false;
        }

        return false;
    }
}
