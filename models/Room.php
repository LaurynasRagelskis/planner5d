<?php

namespace app\models;


class Room extends \yii\base\BaseObject
{
    //data
    public $id;
    public $name;
    public $walls = [];
}
