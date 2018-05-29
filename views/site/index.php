<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ProjectFile */
/* @var $formModel app\models\UploadForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = 'Planner5D';

if ($formModel->hasErrors()) {
    $this->registerJs(<<<JS
    var errorTabId = $('.tab-content .has-error').first().parent()[0].id;;
    $('.nav-tabs a[href="#'+errorTabId+'"]').tab('show');
JS
    , yii\web\View::POS_READY);
}

?>
<div class="site-index">
    <?php if (Yii::$app->session->hasFlash('projectFileUploaded')): ?>
        <div class="alert alert-success">
            Thank you for new project! Now you can preview project in 2D.
        </div>
    <?php elseif(Yii::$app->session->hasFlash('projectFileUploadedError')) : ?>
        <div class="alert alert-danger">
            Sorry! Something gets wrong, please try again.
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-5">
            <h1>Upload new project file</h1>
            <?php $form = ActiveForm::begin(['id' => 'project-form', 'options' => ['enctype' => 'multipart/form-data']]); ?>

            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#upload-file" aria-controls="upload-file" role="tab" data-toggle="tab">Upload file from PC</a></li>
                <li role="presentation"><a href="#upload-url" aria-controls="upload-url" role="tab" data-toggle="tab">Upload from URL</a></li>
                <li role="presentation"><a href="#upload-json" aria-controls="upload-json" role="tab" data-toggle="tab">Upload JSON string</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" role="tabpanel" id="upload-file">
                    <?= $form->field($formModel, 'file')->fileInput()->label('') ?>
                </div>
                <div class="tab-pane" role="tabpanel" id="upload-url">
                    <?= $form->field($formModel, 'url')->label(''); ?>
                </div>
                <div class="tab-pane" role="tabpanel" id="upload-json">
                    <?= $form->field($formModel, 'json')->textarea(['rows' => 8])->label(''); ?>
                </div>
            </div>

            <?= $form->field($formModel, 'name')->textInput()->hint('If empty - it will be same as in project file') ?>

            <?= $form->field($formModel, 'description')->textarea(['rows' => 4]) ?>

            <?= $form->field($formModel, 'verifyCode')->widget(Captcha::className(), [
                'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
            ]) ?>

            <div class="form-group">
                <?= Html::submitButton('Submit new Project', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
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
                                <?= Yii::$app->user->IsGuest ? '' : Html::a('Delete', '/web/site/project?action=delete&id=' . $project->id, ['class' => 'btn btn-danger'])  ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        </div>
    </div>

</div>