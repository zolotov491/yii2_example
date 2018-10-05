<?php

namespace app\models;

use tpmanc\imagick\Imagick;
use Yii;
use yii\base\Model;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

/**
 * @property integer $gender
 *
 */

/**
 * Class RegistrationForm
 * @package app\models
 */
class FullRegistrationForm extends Model
{
    /** @var UploadedFile[] */
    public $imageFiles;

    /** @var $titles array */
    private $titles;

    public $hairColor;
    public $eyeColor;
    public $education;
    public $specialty;
    public $profession;
//    public $language;
//    public $languageProficiency;
    public $maritalStatus;
    public $wantChildren;
    public $smoking;
    public $alcoholConsumption;
    public $aboutMe;
    public $hobbiesAndInterests;
    public $requirementsFromPartner;
    public $confirmRules;
    public $name;
    public $surname;
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
            [['imageFiles'], 'image', 'extensions' => 'png, jpg, jpeg', 'minWidth' => 270, 'minHeight' => 270, 'maxFiles' => 4, 'skipOnEmpty' => true],
            [['requirementsFromPartner', 'hobbiesAndInterests', 'aboutMe', 'alcoholConsumption', 'smoking',
                'maritalStatus', 'wantChildren', 'profession', 'specialty', 'hairColor', 'eyeColor', 'education',
                'viber', 'socialAddress', 'skype', 'howDidFindOutAboutUs'], 'string', 'max' => 255],
            [['country', 'day', 'month', 'year', 'phone', 'weight'], 'integer'],
            ['password', 'string', 'min' => 6],
            [['requirementsFromPartner', 'hobbiesAndInterests', 'aboutMe', 'alcoholConsumption', 'smoking',
                'maritalStatus', 'wantChildren', 'profession', 'specialty', 'hairColor', 'eyeColor', 'education',
                'name', 'surname', 'howDidFindOutAboutUs',
                'confirmRules', 'email', 'name', 'surname', 'password', 'day', 'year', 'month', 'phone', 'growth', 'weight', 'country'], 'required', 'message' => Yii::t('app', 'field is required!')],
            ['email', 'trim'],
            ['confirmRules', 'boolean'],
            ['email', 'email'],
            [['growth'], 'double'],
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


    /**
     * @param array $titles
     * @return $this
     */
    private function setTitles(array $titles)
    {
        $this->titles = $titles;

        return $this;
    }

    /**
     * @return array
     */
    public function getTitles(): array
    {
        return $this->titles;
    }


    /**
     * @param string $path
     * @throws \yii\base\Exception
     */
    public function upload(string $path)
    {
        $this->imageFiles = UploadedFile::getInstances($this, 'imageFiles');
        $path = \Yii::getAlias("@app/web/uploads/images/$path/");
        FileHelper::createDirectory($path);
        $titles = [];

        foreach ($this->imageFiles as $file) {
            $titles[] = $fileName = md5($file->name) . '.' . $file->extension;
            $file->saveAs($path . $fileName);

            $newFile = $path . $fileName;
            $file->saveAs($newFile);
            $img = Imagick::open($newFile);
            if ($img->getWidth() > 1200 || $img->getHeight() > 800) {
                Imagick::open($newFile)->resize(1200, 800)->saveTo($newFile);
            }

            $newFile = null;

        }

        $this->setTitles($titles);
    }

}
