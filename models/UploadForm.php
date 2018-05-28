<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class UploadForm extends Model
{
    public $name;
    public $url;
    public $file;
    public $comment;
    public $verifyCode;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['name', 'url', 'file', 'comment'], 'string'],
            // verifyCode needs to be entered correctly
            ['verifyCode', 'captcha'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Project name',
            'url' => 'Remote file URL',
            'file' => 'Select file from PC',
            'comment' => 'Short project description',
            'verifyCode' => 'Verification Code',
        ];
    }
}
