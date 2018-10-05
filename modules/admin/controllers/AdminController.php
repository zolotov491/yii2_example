<?php

namespace app\modules\admin\controllers;

use app\models\AdminUpdateForm;
use app\models\PasswordResetRequestForm;
use app\models\User;
use app\modules\admin\models\SignupForm;
use app\modules\admin\models\UserSearch;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * UserController implements the CRUD actions for User model.
 */
class AdminController extends Controller
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
                        'roles' => [\app\models\User::ROLE_ADMIN],
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
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    /**
     * Creates a new Employee model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $signUpForm = new SignupForm();

        if ($signUpForm->load(Yii::$app->request->post()) && $signUpForm->validate()) {
            if ($user = $signUpForm->signup()) {
                $auth = Yii::$app->authManager;
                $auth->assign($auth->getRole(User::ROLE_ADMIN), $user->getId());
            }

            return $this->redirect('index');
        }

        return $this->render('create', [
            'signupForm' => $signUpForm,
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $adminUpdateForm = new AdminUpdateForm();
        $adminUpdateForm->email = $model->email;
        if ($adminUpdateForm->load(Yii::$app->request->post()) && $adminUpdateForm->validate()) {
            $model->email = $adminUpdateForm->email;
            $model->update();

            Yii::$app->session->setFlash('success', "Почта обновлена");
            
            return $this->refresh();
        } else {
            return $this->render('update', [
                'model' => $model,
                'adminUpdateForm' => $adminUpdateForm,
            ]);
        }
    }

    /**
     * @param $id
     * @return \yii\web\Response
     */
    public function actionDelete($id)
    {
        $user = User::findOne(['id' => $id]);
        $adminIds = Yii::$app->authManager->getUserIdsByRole(User::ROLE_ADMIN);
        $countAdmin = User::find()->where(['id' => $adminIds])->andWhere(['status' => 10])->count();

        if ($user->status && (int)$countAdmin !== 1) {
            $user->status = 0;
            $user->update();
        } else {
            $user->status = 10;
            $user->update();
        }

        return $this->redirect(['index']);
    }

    public function resetPassword()
    {

    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Requests password reset.
     *
     * @param int $id
     * @return string|\yii\web\Response
     */
    public function actionRequestPasswordReset(int $id)
    {
        $user = User::findOne($id);
        $model = new PasswordResetRequestForm();
        $model->email = $user->email;

        if ($model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', "Проверьте электронную почту $user->email для получения дальнейших инструкций.");

                return $this->redirect('index');
            } else {
                Yii::$app->session->setFlash('error', Yii::t('app', 'Sorry, we are unable to reset password for email provided.'));
            }
        }

        return $this->refresh();
    }

}
