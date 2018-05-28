<?php
namespace models;

use yii\base\Model;
use yii\web\UploadedFile;


class FileForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $jsonFile;

    public function rules()
    {
        return [
            [['jsonFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'p5d'],
        ];
    }

    public function upload($baseName)
    {
        if ($this->validate()) {
            $this->jsonFile->saveAs('/uploads/' . $baseName . '.p5d');
            return true;
        } else {
            return false;
        }
    }
}