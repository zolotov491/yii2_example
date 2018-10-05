<?php

namespace app\form;

use tpmanc\imagick\Imagick;
use Yii;
use yii\base\Model;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

class ProfileEditForm extends Model
{
    /** @var UploadedFile[] */
    public $imageFiles;

    /** @var $titles array */
    private $titles;

    public $hairColor;
    public $eyeColor;
    public $growth;
    public $weight;
    public $education;
    public $specialty;
    public $maritalStatus;
    public $smoking;
    public $alcoholConsumption;
    public $aboutMe;
    public $hobbiesAndInterests;
    public $requirementsFromPartner;
    public $country;
    public $languageId;
    public $languageProficiencyId;
    public $profession;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['imageFiles'], 'image', 'extensions' => 'png, jpg, jpeg', 'minWidth' => 270, 'minHeight' => 270, 'maxFiles' => 4, 'skipOnEmpty' => true],
            [['profession', 'requirementsFromPartner', 'hobbiesAndInterests', 'aboutMe', 'alcoholConsumption', 'smoking',
                'maritalStatus', 'specialty', 'hairColor', 'eyeColor', 'education',
                ], 'string', 'max' => 255],
            [['country', 'weight', 'languageId', 'languageProficiencyId'], 'integer'],

            [['growth'], 'double'],
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
}
