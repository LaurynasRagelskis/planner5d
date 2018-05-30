<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ProjectFile */
/* @var $formModel app\models\UploadForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
use yii\widgets\LinkPager;

$this->title = 'Planner5D';

if ($formModel->hasErrors()) {
    $this->registerJs(<<<JS
        var errorTabId = $('#file-input-tabs .has-error').first().parent()[0].id;
        $('#file-selection-tabs a[href="#'+errorTabId+'"]').tab('show');
JS
    , yii\web\View::POS_READY);
}
?>
<div class="site-index">
    <?php if(Yii::$app->session->hasFlash('alert')) : ?>
        <div class="alert alert-<?=Yii::$app->session->getFlash('alert')['type']?>">
            <?=Yii::$app->session->getFlash('alert')['msg']?>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-5">
            <h1>Upload new project file</h1>
            <?php $form = ActiveForm::begin(['id' => 'project-form', 'options' => ['enctype' => 'multipart/form-data']]); ?>
            <ul id="file-selection-tabs" class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#upload-file" aria-controls="upload-file" role="tab" data-toggle="tab">Upload file from PC</a></li>
                <li role="presentation"><a href="#upload-url" aria-controls="upload-url" role="tab" data-toggle="tab">Upload from URL</a></li>
                <li role="presentation"><a href="#upload-json" aria-controls="upload-json" role="tab" data-toggle="tab">Upload JSON string</a></li>
            </ul>
            <div id="file-input-tabs" class="tab-content">
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
                                <?= Html::a('Preview &raquo;', '/web/site/project?id=' . $project->id, ['class' => 'btn btn-success', 'target' => '_blank', 'title' => $project->description])  ?>
                                <?= Yii::$app->user->id != 100 ? '' : Html::a('Delete', '/web/site/delete?id=' . $project->id, ['class' => 'btn btn-danger'])  ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?= LinkPager::widget(['pagination' => $pages]);?>
        </div>

        <div class="col-md-5 col-md-offset-1">
            <h1>Test task description</h1>
            <p>Please read here what are done and what was expected:</p>
            <?= Html::a('Read more &raquo;', '/web/site/about', ['class' => 'btn btn-success'])  ?>
        </div>
    </div>

</div>