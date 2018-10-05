<?php

namespace app\modules\admin\controllers;

use app\models\UploadFile;
use app\models\User;
use app\modules\admin\models\Category;
use app\modules\admin\models\CategorySearch;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * CategoryController implements the CRUD actions for Category model.
 */
class CategoryController extends Controller
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
     * Lists all Category models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CategorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Category model.
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
     * Creates a new Category model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Category();
        $uploadFile = new UploadFile(['extensions' => 'png, jpg, jpeg']);
        if ($model->load(Yii::$app->request->post()) && $model->validate() && $uploadFile->beforeSave("category")) {
            $model->img = $uploadFile->file->name;
            $model->save();
            
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
                'file' => $uploadFile
            ]);
        }
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $uploadFile = new UploadFile(['extensions' => 'png, jpg, jpeg']);
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($uploadFile->beforeSave('category'))   {
                if ($model->img != null) {
                    $link = Yii::getAlias('@app/web/uploads/category/' . $model->img );
                    file_exists($link) ? unlink($link) : false;
                }
                /** set new uploading image */
                $model->img = $uploadFile->file->name;
            }

            if ($model->save()) {
                Yii::$app->session->addFlash('success', 'Категория успешно обнавлена');

                return $this->refresh();
            } else {
                Yii::$app->session->addFlash('danger', 'Ошибка');
            }
        }

        return $this->render('update', [
            'model' => $model,
            'file' => $uploadFile
        ]);
    }

    public function actionDelete($id)
    {
        $category = Category::findOne($id);
        if ($category->active) {
            $category->active = 0;
            $category->update();
        } else {
            $category->active = 1;
            $category->update();
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Category model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Category the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Category::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}


