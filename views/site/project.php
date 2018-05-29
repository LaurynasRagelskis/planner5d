<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'Project "' . $model->name . '"';
$this->params['breadcrumbs'][] = $this->title;
$arrFloors = json_encode($plan->floors);

//echo $plan->width; die;
//print_r($plan->floors[0]->rooms[0]->walls); die;
?>
<div class="site-project">
    <h1><?= Html::encode($this->title) ?> preview</h1>

    <p>
        This is 2D plan of project. At this moment rendered are only walls and floors.
    </p>

    <div class="body-content">
        <div class="row">
            <div class="col-lg-12">
                <?php foreach($plan->floors[0]->rooms as $key => $room) : ?>
                    <p>[Room <?=$key+1?>]</p>
                    <?php foreach($room->walls as $key => $wall): ?>
                    <code>
                        [Wall <?=$key+1?>] <?='Start: ' . $wall->startPoint[0] .', '.$wall->startPoint[1] .' End: ' . $wall->endPoint[0] .', '.$wall->endPoint[1]?>
                    </code><br />
                <?php endforeach; ?>
                <?php endforeach; ?>
            </div>
        </div>


        <div>


            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <?php foreach ($plan->floors as $key => $floor) : ?>
                    <li role="presentation" class="<?= $floor->id === $plan->currentFloor ? 'active' : '' ?>"><a href="#floor_<?= $floor->id ?>" aria-controls="floor_<?= $floor->id ?>" role="tab" data-toggle="tab"><?= $floor->name ?></a></li>
                <?php endforeach; ?>
            </ul>

            <div class="tab-content">
                <?php foreach ($plan->floors as $key => $floor) : ?>
                    <div role="tabpanel" class="tab-pane <?= $floor->id === $plan->currentFloor ? 'active' : '' ?>" id="floor_<?= $floor->id ?>">
                        <div class="canvasPlan" style="width: 100%">
                            <canvas id="myCanvas_<?= $floor->id ?>" width="<?= $plan->width /*$plan->canvasSize[0] + $plan->xOffset + 30*/ ?>" height="<?= $plan->height /*$plan->canvasSize[1] + $plan->yOffset + 30*/ ?>"
                                    style="border:1px solid #fff; background-color: <?= $plan->color ?>">
                            </canvas>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <pre><?php print_r($plan->canvasSize) ?></pre>
                <pre><?php //print_r($plan->floors) ?></pre>
                <pre><?php print_r($plan->data) ?></pre>
            </div>
        </div>

    </div>
</div>
<script>

    arrFloors = <?= $arrFloors ?>;

    for(var i=0; i < arrFloors.length; i++)
        drawFloor(i, arrFloors[i], <?= 10 + $plan->xOffset ?>, <?= 10 + $plan->yOffset ?>);

        //drawFloorWalls(i, arrFloors[i], <?= 10 + $plan->xOffset ?>, <?= 10 + $plan->yOffset ?>);

    function drawFloor(floorId, floor, xOffset, yOffset) {
        var arrRooms = floor.rooms;
        for(var i=0; i < arrRooms.length; i++){
            var ctx = document.getElementById('myCanvas_' + floorId).getContext('2d');
            ctx.lineWidth = 10;
            ctx.lineJoin = 'mitter';
            ctx.lineCap = 'square';

            ctx.beginPath();

            var arrWalls = arrRooms[i].walls;
            console.log(i + 'k. Sienu sk: ' + arrWalls.length);
            for(var j=0; j < arrWalls.length; j++){

                ctx.strokeStyle = 'rgb(' + Math.floor(255 - 42.5 * i) + ', ' +
                    Math.floor(255 - 42.5 * i) + ', 0)'; //arrWalls[i].color;
                if(j === 0) {
                    ctx.font = '16px serif';
                    ctx.fillStyle = 'blue';
                    ctx.fillText((i+1) + ' kambarys', arrWalls[j].startPoint[0]+15, arrWalls[j].startPoint[1]+30);
                }

                ctx.lineWidth = arrWalls[j].width;
                if(j === 0)
                    ctx.moveTo(arrWalls[j].startPoint[0], arrWalls[j].startPoint[1]);
                ctx.lineTo(arrWalls[j].endPoint[0], arrWalls[j].endPoint[1]);
            }

            ctx.stroke();
            ctx.fillStyle = 'red';
            ctx.fill();
        }
    }

</script>
