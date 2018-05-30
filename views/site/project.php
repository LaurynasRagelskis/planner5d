<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'Project "' . $model->name . '"';
$this->params['breadcrumbs'][] = $this->title;
$arrFloors = json_encode($model->plan->floors);
$canvasSize = [$model->plan->canvasXdelta + $model->plan->offset * 2, $model->plan->canvasYdelta + $model->plan->offset * 2];
?>
<div class="site-project">
    <h1><?= Html::encode($this->title) ?> preview</h1>

    <p>
        This is 2D plan of project. At this moment rendered are only walls and floors.<br />
        <b>Id:</b> <?= $model->id ?>, <b>Updated: </b> <?= $model->timestamp ?>
        <?= $model->description ? '<br /><b>Short description:</b> '.$model->description : '' ?>
    </p>

    <div class="body-content">
        <div>
            <ul class="nav nav-tabs" role="tablist">
                <?php foreach ($model->plan->floors as $key => $floor) : ?>
                    <li role="presentation" class="<?= $floor->id === $model->plan->currentFloor ? 'active' : '' ?>"><a href="#floor_<?= $floor->id ?>" aria-controls="floor_<?= $floor->id ?>" role="tab" data-toggle="tab"><?= $floor->name ?></a></li>
                <?php endforeach; ?>
            </ul>

            <div class="tab-content">
                <?php foreach ($model->plan->floors as $key => $floor) : ?>
                    <div role="tabpanel" class="tab-pane <?= $floor->id === $model->plan->currentFloor ? 'active' : '' ?>" id="floor_<?= $floor->id ?>">
                        <div class="canvasPlan" style="width: 100%; position:relative">
                            <canvas id="myCanvas_<?= $floor->id ?>" width="<?= $canvasSize[0] ?>" height="<?= $canvasSize[1] ?>"
                                    style="border:1px solid #fff; z-index: 100; position: absolute;">
                            </canvas>
                            <canvas id="floorCanvas_<?= $floor->id ?>" width="<?= $canvasSize[0] ?>" height="<?= $canvasSize[1] ?>"
                                    style="border:1px solid #fff; background-color: <?= $model->plan->color ?>; z-index: 10; position:relative">
                            </canvas>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="row">
            <?php foreach($model->plan->floors as $key => $floor) : ?>
            <div class="col-md-4">
                <h3>Floor [id: <?= $floor->id ?>, name: <?= $floor->name ?>]</h3>
                <?php foreach($floor->rooms as $key => $room) : ?>
                    <h4>[Room <?=$key+1?>]</h4>
                    <?php foreach($room->walls as $key => $wall): ?>
                        <code>
                            [Wall <?=$key+1?>] <?='Start: ' . $wall->startPoint[0] .', '.$wall->startPoint[1] .' End: ' . $wall->endPoint[0] .', '.$wall->endPoint[1]?>
                        </code><br />
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <h3>Project file's Plan object 'as is'</h3>
                <pre><?php print_r($model->plan) ?></pre>
            </div>
        </div>

    </div>
</div>
<script>

    arrFloors = <?= $arrFloors ?>;

    for(var i=0; i < arrFloors.length; i++)
        drawFloor(i, arrFloors[i], <?= $model->plan->offset ?>);

    function drawFloor(floorId, floor, offset) {
        var arrRooms = floor.rooms;

        for(var i=0; i < arrRooms.length; i++){
            var ctx = document.getElementById('myCanvas_' + floorId).getContext('2d');
            var ctxF = document.getElementById('floorCanvas_' + floorId).getContext('2d');
            if(i === 0) {
                ctx.translate(offset, offset);
                ctxF.translate(offset, offset);
            }

            ctx.lineWidth = 10;
            ctx.lineJoin = 'mitter';
            ctx.lineCap = 'square';

            ctx.beginPath();
            ctxF.beginPath();

            var arrWalls = arrRooms[i].walls;
            for(var j=0; j < arrWalls.length; j++) {
                if(j === 0) {
                    ctx.shadowOffsetX = 1;
                    ctx.shadowOffsetY = 1;
                    ctx.shadowBlur = 5;
                    ctx.shadowColor = 'rgba(0, 0, 0, 1)';
                    ctx.font = '11px arial';
                    ctx.fillStyle = '#fff';
                    ctx.fillText((i+1) + ' Room', arrWalls[j].startPoint[0]+15, arrWalls[j].startPoint[1]+30);
                    ctx.shadowOffsetX = null;
                    ctx.shadowOffsetY = null;
                    ctx.shadowBlur = null;
                    ctx.shadowColor = null;

                    ctx.moveTo(arrWalls[j].startPoint[0], arrWalls[j].startPoint[1]);
                    ctxF.moveTo(arrWalls[j].startPoint[0], arrWalls[j].startPoint[1]);
                }
                ctx.strokeStyle = 'rgb(' + Math.floor(255 - 22.5 * i) + ', ' + Math.floor(255 - 22.5 * i) + ', 0)';
                ctx.lineWidth = arrWalls[j].width;
                ctx.lineTo(arrWalls[j].endPoint[0], arrWalls[j].endPoint[1]);

                ctxF.lineTo(arrWalls[j].endPoint[0], arrWalls[j].endPoint[1]);
            }

            if( arrRooms[i].color ) {
                ctxF.fillStyle = arrRooms[i].color;
                ctxF.fill();
            } else {
                var img = new Image();
                img.src = '/images/laminate_2_4.jpg';
                img.onload = function(){
                    var ptrn = ctxF.createPattern(img, 'repeat');
                    ctxF.fillStyle = ptrn;
                    ctxF.fill();
                };
            }

            ctx.stroke();
            ctx.closePath();
        }
    }
</script>
