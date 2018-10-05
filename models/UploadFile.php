<?php

namespace app\models;

use PHPUnit\Framework\Exception;
use yii;
use yii\base\Model;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

/**
 * Class LoadPhoto
 * @package frontend\models
 */
class UploadFile extends Model
{
    /** @var UploadedFile */
    public $file;
    
    /** @var $extensions string */
    public $extensions;

    public function __construct(array $config)
    {
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['file'], 'file', 'skipOnEmpty' => true, 'extensions' => $this->extensions],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'file' => 'Фото',
        ];
    }

    /**
     * @param string $path
     * @param string|null $fileName
     * @return bool
     * @throws yii\base\Exception
     */
    public function beforeSave(string $path, string $fileName = null): bool
    {
        if ($this->validate() && $this->file = UploadedFile::getInstance($this, 'file')) {
            $path = Yii::getAlias("@app/web/uploads/$path");
            FileHelper::createDirectory($path);
            if($fileName !== null) {
                $string = $fileName;
            } else {
                $string = md5($this->file->name);
            }
             $this->file->saveAs($path . '/' . $this->file->name = $string . '.' . $this->file->extension);

            return true;
        }

        return false;
    }
}

