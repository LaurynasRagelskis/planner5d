<?php

namespace app\models;
use app\models\Wall;

class Plan extends \yii\base\BaseObject
{
    //data
    public $width;
    public $height;
    public $currentFloor;

    public $data;

    public $canvasSize = [];

    public $canvasX = 0;
    public $canvasY = 0;
    public $canvasXdelta = 0;
    public $canvasYdelta = 0;

    public $xOffset = 0;
    public $yOffset = 0;

    //data->items[0..n]

    public $floors = []; //issiparsinti visus items if item->className == 'Floor'

    public $rooms = []; //issiparsinti visus items if floor->items->className == 'Room'

    public $walls = []; //issiparsinti visus items if room->items->className == 'Wall'

    //wall { w, points [x,y] }

    public function __construct(array $config = [])
    {
        parent::__construct($config);
        $data = $config['data'];
        foreach ($data->items as $key => $item) {
            if ($item->className == 'Floor'){
                $this->floors[] = ['id' => $key, 'name' => $item->name, 'height' => $item->h ];
                $this->parseFloorWalls($item, $key);
            }
        }
        $this->currentFloor = $data->currentFloor; //$this->floors[0];
        $this->checkCanvasSize();
        $this->checkOffset();
    }

    private function parseFloorWalls($floor, $keyFloor)
    {
        foreach ($floor->items as $key => $item) {
            if($item->className == 'Room') // || $item->className == 'Ground')
            {
                $this->parseRoomWalls($item, $keyFloor);
            }
        }
    }
    private function parseRoomWalls($room, $floorKey) {
        if(!isset($room->items))
            return null;

        $walls = [];
        foreach ($room->items as $item) {
            //$walls[] =  [$item->w, $this->parseWallPoints($item)];
            if ($item->className == 'Wall') {
                $wallPoints = $this->parseWallPoints($item);
                $walls[] = new Wall([
                        'floor' => $floorKey,
                        'room' => $room->puid,
                        'width' => $item->w,
                        'startPoint' => $wallPoints[0],
                        'endPoint'   => $wallPoints[1],
                        'color'      => '#00000' //$item->materials->indoor->color
                    ]);
            }
        }

        $this->walls = array_merge( $this->walls, $walls);
    }
    private function addWall(&$floor, $width, $points){
        $object = (object) [
            'w' => $width,
            'start' => $points[0],
            'end' => $points[1],
        ];
        $floor[] = $object;
    }


    private function parseFloorRooms($floor) {
        $rooms = [];
        foreach ($floor->items as $item) {
            if ($item->className == 'Room')
                $rooms[] =  $item;
        }
        return $rooms;
    }


    private function parseWallPoints($wall)
    {
        $points = [];
        foreach ($wall->items as $item) {
            if ($item->className == 'Point')
                $points[] =  [$item->x, $item->y];
        }

        return $points;
    }

    private function checkCanvasSize()
    {
        foreach ($this->walls as $wall) :
            $bRecalculate = false;

            if($this->canvasX > $wall->startPoint[0]) {
                $this->canvasX = $wall->startPoint[0];
                $this->canvasSize[0] = ($this->canvasX < 0 ? -$this->canvasX : $this->canvasX) + $this->canvasXdelta;

            } elseif($this->canvasXdelta < $wall->endPoint[0]) {
                $this->canvasXdelta = $wall->endPoint[0];
                $this->canvasSize[0] = ($this->canvasX < 0 ? -$this->canvasX : $this->canvasX) + $this->canvasXdelta;
            }

            if($this->canvasY > $wall->startPoint[1]) {
                $this->canvasY = $wall->startPoint[1];
                $this->canvasSize[1] = ($this->canvasY < 0 ? -$this->canvasY : $this->canvasY) + $this->canvasYdelta;
            } elseif($this->canvasYdelta < $wall->endPoint[1]) {
                $this->canvasYdelta = $wall->endPoint[1];
                $this->canvasSize[1] = ($this->canvasY < 0 ? -$this->canvasY : $this->canvasY) + $this->canvasYdelta;
            }
        endforeach;
    }
    private function checkOffset()
    {
        if($this->canvasX < 0) {
            $this->xOffset = -$this->canvasX;
        }
        if($this->canvasY < 0) {
            $this->yOffset = -$this->canvasY;
        }
    }

    private static $users = [
        '100' => [
            'id' => '100',
            'username' => 'admin',
            'password' => 'admin',
            'authKey' => 'test100key',
            'accessToken' => '100-token',
        ],
        '101' => [
            'id' => '101',
            'username' => 'demo',
            'password' => 'demo',
            'authKey' => 'test101key',
            'accessToken' => '101-token',
        ],
    ];


    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === $password;
    }
}