<?php

namespace app\models;


class Wall extends \yii\base\BaseObject
{
    //data
    public $floor;
    public $room;
    public $width;

    public $startPoint;
    public $endPoint;

    public $color;
}
