<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Building */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="building-form">

    <?php $form = ActiveForm::begin([
	    'options' => ['enctype'=>'multipart/form-data']
    ]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

	<?= $model->isNewRecord ? '' : $form->field($model, 'avatar')->fileInput() ?>

    <?= $form->field($model, 'topText')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bottomText')->textInput(['maxlength' => true]) ?>

<!--    --><?//= $form->field($model, 'enabled')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
