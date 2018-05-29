<?php

namespace app\models;


class Room extends \yii\base\BaseObject
{
    //data
    public $id;
    public $name;
    public $data;
    public $floor;
    public $color;

    public $walls = [];

    public function __construct(array $config = [])
    {
        parent::__construct($config);
        $data = $config['data'];
//        print_r($data->items); die;
//        print_r($data); die;
        if(isset($data->items))
            foreach ($data->items as $key => $item) {
                if ($item->className == 'Wall'){
                    $this->addWall($key, $item, [$data->x, $data->y]);
                }
            }
        $this->color = $data->materials->floor->color ? $data->materials->floor->color : null;
        $this->data = null;
    }

    private function addWall($key, $item, $offset)
    {
        $this->walls[] = new Wall(['id' => $key, 'data' => $item, 'offset' => $offset, 'hidden' => $item->hidden]);
    }

}
