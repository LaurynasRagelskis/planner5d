<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

/**
 * ContactForm is the model behind the contact form.
 */
class UploadForm extends Model
{
    public $file;
    public $url;
    public $json;
    public $name;
    public $description;
    public $verifyCode;

    public $content;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required

            [['name', 'url', 'json', 'description', 'content'], 'trim'],
            [['name', 'url', 'json', 'description', 'content'], 'string'],
            [['file'], 'file'],
            [['name'], 'string', 'max' => 45],
            [['description'], 'string', 'max' => 512],
            [['url'], 'validateJsonUrl'],
            ['verifyCode', 'captcha'],
            ['content', 'required']
        ];
    }

    public function validateJsonUrl($attribute, $params)
    {
        if( preg_match('/^https?:\/\/.*(\.p5d|\.json)$/', strtolower($this->$attribute)) != 1 ) {
            $this->addError($attribute, 'Not matched. Please enter correct URL to remote JSON file with .pd5 or .json extension.');
        }
    }
    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Project name',
            '$description' => 'Short project description',
            'verifyCode' => 'Verification Code',
        ];
    }
}
