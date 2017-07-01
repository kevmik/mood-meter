<?php

namespace app\controllers\admin;

use Yii;
use app\models\Meter;
use app\models\MeterSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MeterController implements the CRUD actions for Meter model.
 */
class MeterController extends Controller
{
	private $session_state = 'meter';
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
	            'class' => \yii\filters\AccessControl::className(),
	            'rules' => [
		            [
			            'allow' => true,
			            'actions' => ['index', 'view', 'create', 'update', 'delete', 'block', 'deblock'],
			            'roles' => ['@']
		            ],
		            [
			            'allow' => false
		            ]
	            ],
	            'denyCallback' => function($rule, $action) {
		            return Yii::$app->response->redirect(['/admin/login']);
	            },
            ]
        ];
    }

    /**
     * Lists all Meter models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MeterSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Meter model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Meter model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Meter();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Meter model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
	    $session = \Yii::$app->session;
	    $session->open();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
	        if ($session->has($this->session_state)) {
		        $url = $session->get($this->session_state);
		        $session->remove($this->session_state);
		        return $this->redirect($url);
	        }
            return $this->redirect(['index', 'id' => $model->id]);
        } else {
	        $_SESSION[$this->session_state] = $_SERVER['HTTP_REFERER'];
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Meter model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Meter model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Meter the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Meter::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
