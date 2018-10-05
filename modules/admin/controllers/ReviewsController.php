<?php

namespace app\modules\admin\controllers;

use app\models\MultipleUploadImg;
use app\models\Profile;
use app\models\User;
use Yii;
use app\modules\admin\models\Reviews;
use app\modules\admin\models\ReviewsSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * ReviewsController implements the CRUD actions for Reviews model.
 */
class ReviewsController extends Controller
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
     * Lists all Reviews models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ReviewsSearch();
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
        $model = new Reviews();
        $multiUpload = new MultipleUploadImg();
        $multiUpload->width = 162;
        $multiUpload->height = 162;

        if ($model->load(Yii::$app->request->post()) && $multiUpload->load(Yii::$app->request->post())) {
            if ($multiUpload->oneFileUpload('reviews')) {

                $profile =  Profile::findOne(['user_id' =>  Yii::$app->user->getId()]);
                Yii::$app->user->getId();
                $model->profile_id = $profile->id;
                $model->image = $multiUpload->getTitle();
                $model->save();

                return $this->redirect(['index']);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'photo' => $multiUpload
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $multiUpload = new MultipleUploadImg();
        $multiUpload->width = 162;
        $multiUpload->height = 162;

        if ($model->load(Yii::$app->request->post()) && $multiUpload->load(Yii::$app->request->post())) {
            if ($multiUpload->oneFileUpload('reviews')) {
                $model->image = $multiUpload->getTitle();
                $model->save();

                return $this->redirect(['update', 'id' => $model->id]);
            }

            $model->save();

            return $this->redirect(['update', 'id' => $model->id]);


        }

        return $this->render('update', [
            'model' => $model,
            'photo' => $multiUpload
        ]);
    }


    /**
     * @param int $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws \Exception
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete(int $id)
    {
        $reviews = $this->findModel($id);
        if ($reviews->active) {
            $reviews->active = 0;
            $reviews->update();
        } else {
            $reviews->active = 1;
            $reviews->update();
        }

        return $this->redirect(['index']);
    }


    public function actionRemove(int $id)
    {
        $reviews = $this->findModel($id);

        $reviews->delete();

        return $this->redirect(['index']);
    }


    /**
     * Finds the Reviews model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Reviews the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Reviews::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
