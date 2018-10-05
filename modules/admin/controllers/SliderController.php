<?php

namespace app\modules\admin\controllers;

use app\models\MultipleUploadImg;
use app\models\User;
use Yii;
use app\modules\admin\models\Slider;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SliderController implements the CRUD actions for Slider model.
 */
class SliderController extends Controller
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

    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Slider::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * @return string|\yii\web\Response
     * @throws \yii\base\Exception
     */
    public function actionCreate()
    {
        $model = new Slider();
        $multiUpload = new MultipleUploadImg();
      
        if ($multiUpload->load(Yii::$app->request->post()) ) {
            if ($multiUpload->oneFileUpload('slider')) {
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

    /**
     * @param $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     * @throws \yii\base\Exception
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $multiUpload = new MultipleUploadImg();

        if ($multiUpload->load(Yii::$app->request->post())) {
            $link = Yii::getAlias("@app/web/uploads/images/slider/$model->image");
            if(file_exists($link)) {
                unlink($link);
            }

            if ($multiUpload->oneFileUpload('slider')) {

                $model->image = $multiUpload->getTitle();
                $model->save();

                return $this->redirect(['update', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'photo' => $multiUpload
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
        $slider = $this->findModel($id);
        if ($slider->active) {
            $slider->active = 0;
            $slider->update();
        } else {
            $slider->active = 1;
            $slider->update();
        }

        return $this->redirect(['index']);
    }


    /**
     * Finds the Slider model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Slider the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Slider::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
