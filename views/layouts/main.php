<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register( $this );
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<?= Html::csrfMetaTags() ?>
    <title><?= Html::encode( $this->title ) ?></title>
	<?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
	<?php
	if ( Yii::$app->user->isGuest ) {
		$items = [
			[ 'label' => 'Login', 'url' => [ '/admin/login' ] ]
		];
	} else {
		$items = [
			[ 'label' => 'Home', 'url' => [ '/admin/index' ] ],
			[ 'label' => 'About', 'url' => [ '/admin/about' ] ],
			[ 'label' => 'Building', 'url' => [ '/admin/building' ] ],
			[ 'label' => 'Meters', 'url' => [ '/admin/meter' ] ],
			'<li>'
			. Html::beginForm( [ '/admin/logout' ], 'post' )
			. Html::submitButton(
				'Logout (' . Yii::$app->user->identity->username . ')',
				[ 'class' => 'btn btn-link logout' ]
			)
			. Html::endForm()
			. '</li>'
		];
	}
	NavBar::begin( [
		'brandLabel' => 'Buildings Mood Meter',
		'brandUrl'   => Yii::$app->homeUrl,
		'options'    => [
			'class' => 'navbar-inverse navbar-fixed-top1',
		],
	] );
	echo Nav::widget( [
		'options' => [ 'class' => 'navbar-nav navbar-right' ],
		'items'   => $items
	] );
	NavBar::end();
	?>

    <div class="container">
		<?php
        Breadcrumbs::widget( [
			'links' => isset( $this->params['breadcrumbs'] ) ? $this->params['breadcrumbs'] : [],
		] )
        ?>
		<?= $content ?>


    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; Buildings Mood Meter <?= date( 'Y' ) ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>
