<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\MeterSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="meter-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'building_id') ?>

    <?= $form->field($model, 'energy') ?>

    <?= $form->field($model, 'pleasantness') ?>

    <?= $form->field($model, 'date_created') ?>

    <?php // echo $form->field($model, 'date_meter') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
