<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'Project "' . $model->name . '"';
$this->params['breadcrumbs'][] = $this->title;
$objFloors = json_encode($plan->floors);
$objWalls = json_encode($plan->walls);
//print_r($objWalls); die;
?>
<div class="site-project">
    <h1><?= Html::encode($this->title) ?> preview</h1>

    <p>
        This is 2D plan of project. At this moment rendered are only walls and floors.
    </p>

    <div class="body-content">
        <div class="row">
            <div class="col-lg-12">
                <?php foreach($plan->walls as $key => $wall) : ?>
                    <code>
                        [Wall <?=$key+1?>] Floor: <?=$wall->floor .' Start: ' . $wall->startPoint[0] .', '.$wall->startPoint[1] .' End: ' . $wall->endPoint[0] .', '.$wall->endPoint[1]?>
                    </code><br />
                <?php endforeach; ?>
            </div>
        </div>


        <div>


            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <?php foreach ($plan->floors as $key => $floor) : ?>
                    <li role="presentation" class="<?= $floor['id'] === $plan->currentFloor ? 'active' : '' ?>"><a href="#floor_<?= $floor['id'] ?>" aria-controls="floor_<?= $floor['id'] ?>" role="tab" data-toggle="tab"><?= $floor['name'] ?></a></li>
                <?php endforeach; ?>
            </ul>

            <div class="tab-content">
                <?php foreach ($plan->floors as $key => $floor) : ?>
                    <div role="tabpanel" class="tab-pane <?= $floor['id'] === $plan->currentFloor ? 'active' : '' ?>" id="floor_<?= $floor['id'] ?>">
                        <div class="canvasPlan" style="width: 100%">
                            <canvas id="myCanvas_<?= $floor['id'] ?>" width="<?= $plan->canvasSize[0] + $plan->xOffset + 30 ?>" height="<?= $plan->canvasSize[1] + $plan->yOffset + 30 ?>"
                                    style="border:1px solid #fff; background-color: <?= $plan->data->ground->color ?>">
                            </canvas>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <pre><?php print_r($plan->canvasSize) ?></pre>
                <pre><?php print_r($plan->floors) ?></pre>
                <pre><?php print_r($plan->data) ?></pre>
            </div>
        </div>

    </div>
</div>
<script>
    function drawFloorWalls(floorId, walls, xOffset, yOffset) {
        var ctx = document.getElementById('myCanvas_' + floorId).getContext('2d');
        ctx.lineWidth = 10;
        ctx.lineJoin = 'mitter';
        ctx.lineCap = 'square';
        ctx.beginPath();

        var currentRoom = false;
        for(var i=0; i < walls.length; i++){
            if(walls[i].floor == floorId) {

                if ( currentRoom != walls[i].room && currentRoom !== false)
                {
                    ctx.fillStyle = 'red';
                    ctx.fill();
                    ctx.stroke();
                }
                else
                    currentRoom = walls[i].room;

                drawWall(ctx, walls[i].startPoint, walls[i].endPoint, walls[i].color, i, xOffset, yOffset);

                if(i+1 == walls.length){
                    ctx.fillStyle = 'red';
                    ctx.fill();
                    ctx.stroke();
                }
            }
        }

    }

    function drawWall(ctx, startPoint, endPoint, color, number, xOffset, yOffset) {
        ctx.strokeStyle = color;
        if(startPoint[0] == 0)
            ctx.strokeStyle = 'green';

        if(number === 0)
            ctx.moveTo(startPoint[0] + xOffset, startPoint[1] + yOffset);
        ctx.lineTo(endPoint[0] + xOffset, endPoint[1] + yOffset);
    }

    var floors = <?= $objFloors ?>;
    var walls = <?= $objWalls ?>;

    for(var i=0; i < floors.length; i++)
        drawFloorWalls(i, walls, <?= 10 + $plan->xOffset ?>, <?= 10 + $plan->yOffset ?>);

    console.log(<?= $objWalls ?>);
</script>
