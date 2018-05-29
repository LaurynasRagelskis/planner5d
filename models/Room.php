<?php

namespace app\models;


class Room extends \yii\base\BaseObject
{
    //data
    public $id;
    public $name;
    public $data;
    public $floor;

    public $walls = [];

    public function __construct(array $config = [])
    {
        parent::__construct($config);
        $data = $config['data'];
        foreach ($data->items as $key => $item) {
            if ($item->className == 'Wall'){
//                $this->floors[] = ['id' => $key, 'name' => $item->name, 'height' => $item->h ];
//                $this->parseFloorWalls($item, $key);
                $this->addWall($key, $item, [$data->x, $data->y]);
            }
        }
        $this->data = null;

//        $this->currentFloor = $data->currentFloor; //$this->floors[0];
//        $this->checkCanvasSize();
//        $this->checkOffset();
    }

    private function addWall($key, $item, $offset)
    {
        $this->walls[] = new Wall(['id' => $key, 'data' => $item, 'offset' => $offset]);
    }


    public function parseWalls() {
        foreach ($this->data->items as $key => $item) {
            if ($item->className == 'Wall'){
                $wallPoints = $this->parseWallPoints($item);
                $this->walls[] = new Wall([
                    'parent' => &$this,
                    'room' => $key, // $this->data->puid,
                    'width' => $item->w,
                    'startPoint' => $wallPoints[0],
                    'endPoint'   => $wallPoints[1],
                    'color'      => '#00000' //$item->materials->indoor->color
                ]);
            }
        }
    }



}
