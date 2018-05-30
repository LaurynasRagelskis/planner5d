<?php

namespace app\models;


class Room extends \yii\base\BaseObject
{
    public $id;
    public $data;
    public $color;

    public $walls = [];

    public function __construct(array $config = [])
    {
        parent::__construct($config);
        $data = $config['data'];
        if(isset($data->items)) {
            foreach ($data->items as $key => $item) {
                if ($item->className == 'Wall'){
                    $this->addWall($key, $item, [$data->x, $data->y]);
                }
            }
        }
        $this->color = $data->materials->floor->color ? $data->materials->floor->color : null;
        $this->data = null;
    }

    /**
     * Add new new Wall to Room object
     *
     * @param integer $key
     * @param stdObject $item JSON format object with rooms's wall data
     * @param [integer] $offset fixing wall coordinates by room position
     * @return void
     */
    private function addWall($key, $item, $offset)
    {
        $this->walls[] = new Wall(['id' => $key, 'data' => $item, 'offset' => $offset, 'hidden' => $item->hidden]);
    }

}
