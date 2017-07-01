<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Building;
use app\models\Meter;

class SiteController extends Controller {
	/**
	 * @inheritdoc
	 */
	public function behaviors() {
		return [
			'access' => [
				'class' => AccessControl::className(),
				'only'  => [ 'logout' ],
				'rules' => [
					[
						'actions' => [ 'logout' ],
						'allow'   => true,
						'roles'   => [ '@' ],
					],
				],
			],
			'verbs'  => [
				'class'   => VerbFilter::className(),
				'actions' => [
					'logout' => [ 'post' ],
				],
			],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function actions() {
		$this->layout = 'error';

		return [
			'error'   => [
				'class' => 'yii\web\ErrorAction',
			],
			'captcha' => [
				'class'           => 'yii\captcha\CaptchaAction',
				'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
			],
		];
	}

	/**
	 * Displays homepage.
	 *
	 * @return string
	 */
	public function actionIndex( $buildingUrl = '' ) {
		if ( ! $buildingUrl ) {
			$this->layout = 'error';

			return 'Building is not selected';
		}
		$building = Building::find()->where( [ 'url' => $buildingUrl ] )->one();
		if ( ! $building ) {
			$this->layout = 'error';

			return 'Building is not found';
		}
		$this->layout                   = 'meter';
		$this->view->params['building'] = $building;

		return $this->render( 'index' );
	}


	public function actionX() {
		return $this->render( 'x' );
	}

	/**
	 * Login action.
	 *
	 * @return Response|string
	 */
	public function actionLogin() {
		if ( ! Yii::$app->user->isGuest ) {
			return Yii::$app->getResponse()->redirect('/admin');
		}

		$model = new LoginForm();
		if ( $model->load( Yii::$app->request->post() ) && $model->login() ) {
			return Yii::$app->getResponse()->redirect('/admin');
		}

		return $this->render( 'login', [
			'model' => $model,
		] );
	}

	/**
	 * Logout action.
	 *
	 * @return Response
	 */
	public function actionLogout() {
		Yii::$app->user->logout();

		return $this->goHome();
	}

	/**
	 * Displays contact page.
	 *
	 * @return Response|string
	 */
	public function actionContact() {
		$model = new ContactForm();
		if ( $model->load( Yii::$app->request->post() ) && $model->contact( Yii::$app->params['adminEmail'] ) ) {
			Yii::$app->session->setFlash( 'contactFormSubmitted' );

			return $this->refresh();
		}

		return $this->render( 'contact', [
			'model' => $model,
		] );
	}

	/**
	 * Displays about page.
	 *
	 * @return string
	 */
	public function actionAbout() {
		return $this->render( 'about' );
	}

	public function actionMeter() {
		$bid      = $_POST['bid'];
		$building = Building::find()->where( [ 'id' => $bid ] )->one();
		if ( ! $building ) {
			return 'Building is not found';
		}
		$meter               = new Meter();
		$meter->building_id  = $bid;
		$meter->pleasantness = $_POST['pleasantness'];
		$meter->energy       = $_POST['energy'] ;
		if ( $meter->save() ) {
			return 'ok';
		}

		return 'save error';

	}
}
