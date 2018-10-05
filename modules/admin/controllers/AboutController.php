<?php

namespace app\modules\admin\controllers;

use app\models\MultipleUploadImg;
use Yii;
use app\modules\admin\models\About;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\models\User;

/**
 * AboutController implements the CRUD actions for About model.
 */
class AboutController extends Controller
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


    public function actionUpdate()
    {
        $model = About::findOne(1);

        $multiUpload = new MultipleUploadImg();
      
        if ($model->load(Yii::$app->request->post()) && $multiUpload->load(Yii::$app->request->post())) {
            if ($multiUpload->oneFileUpload('about')) {
                $link = Yii::getAlias("@app/web/uploads/images/about/$model->image");
                file_exists($link) ? unlink($link) : false;
                $model->image = $multiUpload->getTitle();
            }
            $model->save();

            return $this->redirect(['update', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'photo' => $multiUpload
        ]);
    }


}
