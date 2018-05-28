<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ProjectFile */
/* @var $formModel app\models\UploadForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = 'Planner5D';
?>
<div class="site-index">


    <?php if (Yii::$app->session->hasFlash('projectFileUploaded')): ?>
        <div class="alert alert-success">
            Thank you for new project! Now you can preview project in 2D.
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-5">
            <h1>Upload new project file</h1>

            <?php $form = ActiveForm::begin(['id' => 'project-form']); ?>

            <?= $form->field($formModel, 'name')->textInput(['autofocus' => true]) ?>

            <?= $form->field($formModel, 'url') ?>

            <?= $form->field($formModel, 'file') ?>

            <?= $form->field($formModel, 'comment')->textarea(['rows' => 6]) ?>

            <?= $form->field($formModel, 'verifyCode')->widget(Captcha::className(), [
                'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
            ]) ?>

            <div class="form-group">
                <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
        <div class="col-md-6 col-md-offset-1">
            <h1>Project's list</h1>
            <table class="table">
                <thead>
                    <tr>
                        <td>ID</td>
                        <td>Name</td>
                        <td>Last changes</td>
                        <td>Action</td>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($model as $project) : ?>
                        <tr>
                            <td><?= $project->id ?></td>
                            <td><?= $project->name ?></td>
                            <td><?= $project->timestamp ?></td>
                            <td>
                                <?= Html::a('Preview', '/web/site/project?id=' . $project->id, ['class' => 'btn btn-success'])  ?>
                                <a class="btn btn-danger">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <pre><?php print_r($model) ?></pre>
        </div>
    </div>


</div>
