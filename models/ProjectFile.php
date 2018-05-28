<?php

namespace app\models;

use yii\db\ActiveRecord;

class ProjectFile extends ActiveRecord
{
    /**
     * @return string название таблицы, сопоставленной с этим ActiveRecord-классом.
     */
    public static function tableName()
    {
        return 'files';
    }

    public function rules() {
        return [
            [['content', 'name'], 'trim'],
            [['content', 'name'], 'required'],
            [['content'], 'string'],
            [['name'], 'string', 'max' => 45],
//            [['pvs'],
//                function($attribute, $params, $validator) {
//                    if( preg_match('/^https?:\/\/.*(\.jpg|\.JPG|\.PNG|\.png|\.jpeg|\.gif|\.bmp)$/', $this->$attribute) != 1 ) {
//                        $this->addError($attribute, 'Tai nėra nuoroda į paveiksliuko failą.');
//                    }
//                }
//            ],

        ];
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public function setFileContent($json)
    {
        $this->content = $json;
        return null;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public function setFileName($name = null)
    {
        $this->name = $name ? : $this->parseNameFromContent();
    }

    private function parseNameFromContent() {
        return $this->content;
    }

    public function printWalls() {
        return $this->content;
    }

    public function printFloor() {
        return $this->content;
    }
}
