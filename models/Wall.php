<?php

namespace app\models;


class Wall extends \yii\base\BaseObject
{
    public $id;
    public $data;
    public $offset = [];
    public $width = 10;
    public $color = '#000000';
    public $hidden;

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
        $this->width = $this->data->w;
        $this->data = null;
        $this->offset = null;
    }

    /**
     * Add new new Points to Wall object
     *
     * @param integer $key
     * @param stdObject $item JSON format object with wall's points data
     * @return void
     */
    private function addPoint($key, $item)
    {
        $point = [$item->x + $this->offset[0], $item->y + $this->offset[1]];
        if ($key === 0)
            $this->startPoint = $point;
        else
            $this->endPoint = $point;
    }
}
