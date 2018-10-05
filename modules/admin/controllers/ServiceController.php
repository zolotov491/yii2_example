<?php

namespace app\modules\admin\controllers;

use app\models\UploadFile;
use app\models\User;
use app\modules\admin\models\Category;
use app\modules\admin\models\Service;
use app\modules\admin\models\ServiceSearch;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * ServiceController implements the CRUD actions for Service model.
 */
class ServiceController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'denyCallback' => function () {
                    return $this->redirect('/admin/index/login');
                },
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => [User::ROLE_ADMIN],
                    ],
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {
        $this->layout = 'main.php'; //your layout name
        return parent::beforeAction($action);
    }

    /**
     * Lists all Service models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ServiceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Service model.
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
     * Creates a new Service model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Service();
        $model->matching = 0;
        $category = Category::find()->where(['active' => 1])->all();
        $categories = ArrayHelper::map($category, 'id', 'title_ru');
        $uploadFile = new UploadFile(['extensions' => 'png, jpg']);

        if ($model->load(Yii::$app->request->post())
            && $model->validate()
            && $uploadFile->beforeSave('images/service/')
        ) {
            /** set uploading image */
            $model->img = $uploadFile->file->name;
            if (empty($model->price) && $model->matching == 0) {
                Yii::$app->session->addFlash('danger', 'Заполните цену');

                return $this->redirect(['service/create']);
            }
            if (!empty($model->price) && $model->matching == 0 || $model->matching) {

                $model->save();
                Yii::$app->session->addFlash('success', 'Услуга успешно добавлен');

                return $this->refresh();
            } else {
                Yii::$app->session->addFlash('danger', 'Что-то пошло не так');
            }


        }

        return $this->render('create', [
            'model' => $model,
            'categories' => $categories,
            'file' => $uploadFile
        ]);
    }

    /**
     * Updates an existing Service model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $category = Category::find()->where(['active' => 1])->all();
        $categories = ArrayHelper::map($category, 'id', 'title_ru');
        $uploadFile = new UploadFile(['extensions' => 'png, jpg']);
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($uploadFile->beforeSave('images/service/')) {
                if ($model->img != null) {
                    $link = Yii::getAlias('@app/web/uploads/images/service/' . $model->img);
                    file_exists($link) ? unlink($link) : false;
                }
                /** set new uploading image */
                $model->img = $uploadFile->file->name;
            }

            if (empty($model->price) && $model->matching == 0) {
                Yii::$app->session->addFlash('danger', 'Заполните цену');

                return $this->redirect(['service/update', 'id' => $id]);
            }
            if (!empty($model->price) && $model->matching == 0 || $model->matching) {

                $model->save();
                Yii::$app->session->addFlash('success', 'Услуга успешно добавлен');

                return $this->refresh();
            } else {
                Yii::$app->session->addFlash('danger', 'Что-то пошло не так');
            }
        }

        return $this->render('update', [
            'model' => $model,
            'file' => $uploadFile,
            'categories' => $categories,
        ]);
    }

    /**
     * Deletes an existing Service model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $service = Service::findOne(['id' => $id]);

        if ($service->active) {
            $service->active = 0;
            $service->update();
        } else {
            $service->active = 1;
            $service->update();
        }

        return $this->redirect(['index']);
    }
    
    public function actionExpectation($id)
    {
        $service = Service::findOne(['id' => $id]);

        if ($service->matching) {
            $service->matching = 0;
            $service->update();
        } else {
            $service->matching = 1;
            $service->update();
        }

        return $this->redirect(['index']);
    }
    
    /**
     * Finds the Service model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Service the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Service::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
