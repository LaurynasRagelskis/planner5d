<?php

namespace app\models;


class Floor extends \yii\base\BaseObject
{
    //data
    public $id;
    public $name;
    public $rooms = [];
}
