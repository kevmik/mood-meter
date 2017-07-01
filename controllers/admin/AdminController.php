<?php

namespace app\controllers\admin;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\Building;

class AdminController extends Controller {
	/**
	 * @inheritdoc
	 */
	public function behaviors() {
		return [
			'access' => [
				'class'        => \yii\filters\AccessControl::className(),
				'rules'        => [
					[
						'allow'   => true,
						'actions' => [ 'index', 'view', 'create', 'update', 'line', 'block', 'deblock', 'bubble' ],
						'roles'   => [ '@' ]
					],
					[
						'allow' => false
					]
				],
				'denyCallback' => function (  ) {
					return Yii::$app->response->redirect( [ '/admin/login' ] );
				},
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
	public function actionIndex() {
		$buildings = Building::find()->all();

		return $this->render( 'index', [ 'buildings' => $buildings ] );
	}


	/**
	 * Displays about page.
	 *
	 * @return string
	 */
	public function actionAbout() {
		return $this->render( 'about' );
	}

	public function actionBubble() {
		if ($_POST['from']) {
			$from = substr( $_POST['from'], 0, 10 );
		} else {
			$from='2017-01-01';
		}
		if ($_POST['to']) {
			$to = substr( $_POST['to'], 0, 10 );
		} else {
			$to = date('Y-m-d', time()+24*3600);
		}
		$bids = $_POST['bids'];

		$where = "date_created >= '$from' AND left(date_created,10)<='$to' ";
		if ( ! in_array( 'all', $bids ) ) {
			$where .= " AND building_id in (" . implode( ',', $bids ) . ") ";
		}
		$data   = ( new \yii\db\Query() )
			->select( " energy, pleasantness , count(*) as cnt " )
			->from( 'meter' )
			->where( $where )
			->groupBy( " energy, pleasantness  " )
			->all();
		$result = [];
		$max    = 0;
		foreach ( $data as $r ) {
			if ( $r['cnt'] > $max ) {
				$max = $r['cnt'];
			}
		}
		if ( $max > 0 ) {
			foreach ( $data as $r ) {
				$result[] = [
					'y' => $r['energy'],
					'x' => $r['pleasantness'],
					'r' => intval( $r['cnt'] * 20 / $max )
				];
			}
		}

		return json_encode( [ 'set1' => $result ] );
	}

	public function actionLine() {
		if ($_POST['from']) {
			$from = substr( $_POST['from'], 0, 10 );
		} else {
			$from='2017-01-01';
		}
		if ($_POST['to']) {
			$to = substr( $_POST['to'], 0, 10 );
		} else {
			$to = date('Y-m-d', time()+24*3600);
		}
		$g    = 10;
		if ( $from == $to ) {
			$g = 13;
		}
		$where = "date_created >= '$from' AND left(date_created,10)<='$to' ";
		$bids  = $_POST['bids'];


		if ( ! in_array( 'all', $bids ) ) {
			$where .= " AND building_id in (" . implode( ',', $bids ) . ") ";
		}
		$data   = ( new \yii\db\Query() )
			->select( " sum(energy) as energy, sum(pleasantness) as pleasantness , count(*) as cnt , 
				left(`date_created` , $g ) as `dt` " )
			->from( 'meter' )
			->where( $where )
			->groupBy( "dt" )
			->orderBy( "dt" )
			->all();
		$result = ['energy'=>[], 'pleasantness'=>[]];
		foreach ($data as $r){
			$result['energy'][] = ['x'=>$r['dt'],'y'=>$r['energy']/$r['cnt'] ];
			$result['pleasantness'][] = ['x'=>$r['dt'],'y'=>$r['pleasantness']/$r['cnt'] ];
		}
		return json_encode(['type'=>($g==10)?'day':'hour','result'=> $result]);
	}
}
