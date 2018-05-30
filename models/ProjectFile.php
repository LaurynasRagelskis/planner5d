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
            [['content', 'name', 'description', 'plan'], 'trim'],
            [['content', 'name', 'plan'], 'required'],
            [['content', 'plan'], 'string'],
            [['name'], 'string', 'max' => 45],
            [['description'], 'string', 'max' => 512]
        ];
    }
}
