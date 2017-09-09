<?php

namespace app\controllers;

use app\models\User;
use Yii;
use app\models\Articles;
use app\models\ArticlesSearch;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\filters\AccessControl;

/**
 * ArticlesController implements the CRUD actions for Articles model.
 */
class ArticlesController extends Controller
{
    /**
     * @inheritdoc
     */
//    public function behaviors()
//    {
//        return [
//            'verbs' => [
//                'class' => VerbFilter::className(),
//                'actions' => [
//                    'delete' => ['POST'],
//                ],
//            ],
//        ];
//    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' => ['admin','manager'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['view'],
                        'roles' => ['admin','manager'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create'],
                        'roles' => ['admin','manager'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update'],
                        'roles' => ['admin','manager'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete'],
                        'roles' => ['admin','manager'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Articles models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ArticlesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if(Yii::$app->request->post('hasEditable'))
        {
            $article_id = Yii::$app->request->post('editableKey');
            $Article = Articles::findOne($article_id);

            $out = Json::encode(['output' => '', 'message' => '']);
            $post = [];
            $posted = current($_POST['Articles']);
            $post['Articles'] = $posted;
            if($Article->load($post))
            {
                $Article->save();
            }
            echo $out;
            return;
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,

        ]);
    }

    /**
     * Displays a single Articles model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionChangestatus()
    {
        if(Yii::$app->request->post('id'))
        {
            
            $Article = Articles::findOne(Yii::$app->request->post('id'));
            $Article->status = Yii::$app->request->post('index');
            $Article->save();
        }
    }

    /**
     * Creates a new Articles model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Articles();

        if ($model->load(Yii::$app->request->post())) {

            $model->file = UploadedFile::getInstance($model, 'file');
            $save_file = '';
            if($model->file){
                $imagepath = 'uploads/';
                $model->img = $imagepath .rand(10,100).'-'.$model->file->name;
                $save_file = 1;
            }

            $model->author_id = Yii::$app->user->id;
            $model->date = date('Y-m-d'); 

            if($model->save()){

                $Article = new Articles();                 //send event to trigger
                $Article->new_article = $model->id;
                $Article->articleSend(); 

                if($save_file){
                    $model->file->saveAs($model->img);
                }

                return $this->redirect(['index']);
            }

            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->renderAjax('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Articles model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {

            $model->file = UploadedFile::getInstance($model, 'file');
            $save_file = '';
            if($model->file){
                $imagepath = 'uploads/';
                $model->img = $imagepath .rand(10,100).'-'.$model->file->name;
                $save_file = 1;
            }

            if($model->save()){
                if($save_file){
                    $model->file->saveAs($model->img);
                }

            }

            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionDeleteimg($id, $field)
    {

        $img = $this->findModel($id)->$field;
        if($img){
            if (!unlink($img)) {
                return false;
            }
        }

        $img = $this->findModel($id);
        $img->$field = NULL;
        $img->update();

        return $this->redirect(['update', 'id' => $id]);
    }


    /**
     * Deletes an existing Articles model.
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
     * Finds the Articles model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Articles the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Articles::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
