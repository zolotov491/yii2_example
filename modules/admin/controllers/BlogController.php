<?php

namespace app\modules\admin\controllers;

use app\models\MultipleUploadImg;
use app\models\User;
use Yii;
use app\modules\admin\models\Blog;
use app\modules\admin\models\BlogSearch;
use yii\filters\AccessControl;
use yii\helpers\FileHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * BlogController implements the CRUD actions for Blog model.
 */
class BlogController extends Controller
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
     * Lists all Blog models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BlogSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @return string|\yii\web\Response
     * @throws \yii\base\Exception
     */
    public function actionCreate()
    {
        $model = new Blog();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            /** @var  $file UploadedFile */
            $file = UploadedFile::getInstances($model, 'img');
            if (!empty($file)) {
                $path = \Yii::getAlias("@app/web/uploads/images/blog/");
                FileHelper::createDirectory($path);

                $model->img = md5($file[0]->name) . rand(1, 999) . '.' . $file[0]->extension;
                $file[0]->saveAs($path . $model->img);
            }
            $model->save();

            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }


    /**
     * @param $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     * @throws \yii\base\Exception
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $img =  $model->img;
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            /** @var  $file UploadedFile */
            $file = UploadedFile::getInstances($model, 'img');
            if (!empty($file)) {
                $path = \Yii::getAlias("@app/web/uploads/images/blog/");
                FileHelper::createDirectory($path);

                if ($model->img !== $file[0]->name && !empty($model->img)) {
                    $link = Yii::getAlias('@app/web/uploads/images/blog/' . $model->img);
                    file_exists($link) ? unlink($link) : false;
                }
                $model->img = md5($file[0]->name) . rand(1, 999) . '.' . $file[0]->extension;
                $file[0]->saveAs($path . $model->img);
                $model->save();

                return $this->refresh();
            }

            $model->img = $img;
            $model->save();

            return $this->refresh();
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws \Exception
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $blog = $this->findModel($id);
        if ($blog->active) {
             $blog->active = 0;
            $blog->update();
        } else {
            $blog->active = 1;
            $blog->update();
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Blog model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Blog the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Blog::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
