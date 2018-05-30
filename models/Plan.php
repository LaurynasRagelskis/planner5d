<?php

namespace app\models;
use app\models\Floor;

class Plan extends \yii\base\BaseObject
{
    public $data;

    public $width;
    public $height;
    public $currentFloor;
    public $color = 'silver';

    public $canvasSize = [1000, 1000];

    public $canvasX = 0;
    public $canvasY = 0;
    public $canvasXdelta = 1000;
    public $canvasYdelta = 1000;

    public $offset = 0;

    public $floors = [];

    public function __construct(array $config = [])
    {
        parent::__construct($config);
        $data = $config['data'];
        foreach ($data->items as $key => $item) {
            if ($item->className == 'Floor'){
                $this->addFloor($key, $item);
            }
        }
        $this->currentFloor = $data->currentFloor; //$this->floors[0];
        $this->width = $data->width;
        $this->height = $data->height;
        $this->color = $data->ground->color == '#fff' ? 'silver' : $data->ground->color;

        $this->normaliseCanvas();
        $this->normaliseWalls();
        $this->data = null;
    }

    /**
     * Add new Floor to Plan object
     *
     * @param integer $key
     * @param stdObject $item JSON format object with floor's rooms data
     * @return void
     */
    private function addFloor($key, $item)
    {
        $this->floors[] = new Floor(['id' => $key,  'data' => $item, 'name' => $item->name, 'height' => $item->h]);
    }

    /**
     * Recalculate canvas size for rendering
     *
     * @return void
     */
    private function normaliseCanvas () {
        $x = $y = $xD = $yD = $this->floors[0]->rooms[0]->walls[0]->startPoint[0];
        $offset = 0;
        foreach ($this->floors as $floor) :
            foreach ($floor->rooms as $room) :
                foreach ($room->walls as $wall) :
                    $x = $x > $wall->startPoint[0] ? $wall->startPoint[0] : $x;
                    $y = $y > $wall->startPoint[1] ? $wall->startPoint[1] : $y;
                    $xD = $xD < $wall->endPoint[0] ? $wall->endPoint[0] : $xD;
                    $yD = $yD < $wall->endPoint[1] ? $wall->endPoint[1] : $yD;
                    $offset = $offset < $wall->width ? $wall->width : $offset;
                endforeach;
            endforeach;
        endforeach;
        $this->canvasX = $x;
        $this->canvasY = $y;
        $this->canvasXdelta = $xD - $x;
        $this->canvasYdelta = $yD - $y;
        $this->offset = $offset * 2;
    }

    /**
     * Recalculate walls coordinates
     *
     * @return void
     */
    private function normaliseWalls () {
        foreach ($this->floors as $floor) :
            foreach ($floor->rooms as $room) :
                foreach ($room->walls as $wall) :
                    $wall->startPoint = [ $wall->startPoint[0] - $this->canvasX, $wall->startPoint[1] - $this->canvasY ];
                    $wall->endPoint = [ $wall->endPoint[0] - $this->canvasX, $wall->endPoint[1] - $this->canvasY ];
                endforeach;
            endforeach;
        endforeach;
    }
}
