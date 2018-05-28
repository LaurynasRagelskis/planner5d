<?php

namespace app\models;


class Floor extends \yii\base\BaseObject
{
    //data
    public $id;
    public $name;
    public $data;
    public $height;

    public $rooms = [];

    public function __construct(array $config = [])
    {
        parent::__construct($config);
        $data = $config['data'];
        foreach ($data->items as $key => $item) {
            if ($item->className == 'Room'){
                //print_r($item); die;
                $this->addRoom($key, $item);
            }
        }
        $this->data = null;
    }
    private function addRoom($key, $item)
    {
        $this->rooms[] = new Room(['id' => $key, 'data' => $item]);
    }
}
