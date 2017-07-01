<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Meter */

$this->title = 'Create Meter';
$this->params['breadcrumbs'][] = ['label' => 'Meters', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="meter-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
