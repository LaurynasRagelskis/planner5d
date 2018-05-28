<?php

namespace app\models;


class Wall extends \yii\base\BaseObject
{
    //data
    public $id;
    public $data;
    public $parent;
    public $floor;
    public $room;
    public $width = 10;
    public $color = '#000000';

    public $startPoint;
    public $endPoint;

    public function __construct(array $config = [])
    {
        parent::__construct($config);
        $data = $config['data'];
        foreach ($data->items as $key => $item) {
            if ($item->className == 'Point') {
                $this->addPoint($key,  $item);
            }
        }
        $this->data = null;
    }
    private function addPoint($key, $item)
    {
        if ($key === 0)
            $this->startPoint = [$item->x, $item->y];
        else
            $this->endPoint = [$item->x, $item->y];
    }
}
