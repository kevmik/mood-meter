<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Meter */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="meter-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'building_id')->textInput() ?>

    <?= $form->field($model, 'energy')->textInput() ?>

    <?= $form->field($model, 'pleasantness')->textInput() ?>

    <?= $form->field($model, 'date_created')->textInput() ?>

    <?= $form->field($model, 'date_meter')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
