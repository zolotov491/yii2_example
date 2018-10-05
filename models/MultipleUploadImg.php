<?php


namespace app\models;

use yii\base\Model;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;
use tpmanc\imagick\Imagick;

class MultipleUploadImg extends Model
{
    /** @var UploadedFile[] */
    public $imageFiles;

    /** @var $titles array */
    private $titles;

    private $title;

    public function rules()
    {
        return [
            [['imageFiles'], 'image', 'extensions' => 'png, jpg, jpeg', 'minWidth' => 270, 'minHeight' => 270, 'maxFiles' => 4, 'skipOnEmpty' => true]
        ];
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
     * @param string $title
     * @return $this
     */
    private function setTitle(string $title)
    {
        $this->title = $title;

        return $this;
    }


    public function getTitle()
    {
        return $this->title;
    }


    /**
     * @param string $path
     * @return array|bool
     * @throws \yii\base\Exception
     */
    public function upload(string $path)
    {
        $this->imageFiles = UploadedFile::getInstances($this, 'imageFiles');
        $path = \Yii::getAlias("@app/web/uploads/images/$path/");
        FileHelper::createDirectory($path);
        if ($this->validate()) {
            $titles = [];
            foreach ($this->imageFiles as $file) {
                $fileName = md5($file->name) . '.' . $file->extension;
                $titles[] = $fileName;
                $newFile = $path . $fileName;
                $file->saveAs($newFile);
                $img = Imagick::open($newFile);
                if ($img->getWidth() > 1200 || $img->getHeight() > 800) {
                    Imagick::open($newFile)->resize(1200, 800)->saveTo($newFile);
                }

                $newFile = null;
            }
            $this->setTitles($titles);

            return true;
        } else {
            return false;
        }
    }

    /**
     * @param string $path
     * @return array|bool
     * @throws \yii\base\Exception
     */
    public function oneFileUpload(string $path)
    {
        $this->imageFiles = UploadedFile::getInstances($this, 'imageFiles');
        $path = \Yii::getAlias("@app/web/uploads/images/$path/");

        FileHelper::createDirectory($path);

        if ($this->validate()) {
            foreach ($this->imageFiles as $file) {
                $fileName = md5($file->name) . rand(1, 999) . '.' . $file->extension;
                $newFile = $path . $fileName;
                $file->saveAs($newFile);
                $img = Imagick::open($newFile);

                if ($img->getWidth() > 1200 || $img->getHeight() > 800) {
                    Imagick::open($newFile)->resize(1200, 800)->saveTo($newFile);
                }

                $newFile = null;
                $this->setTitle($fileName);

                return true;
            }

        } else {
            return false;
        }
    }

}
