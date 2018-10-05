<?php

namespace app\modules\admin\controllers;

use app\form\ProfileEditForm;
use app\models\Country;
use app\models\Language;
use app\models\LanguageProfile;
use app\models\MultipleUploadImg;
use app\models\Photo;
use app\models\Profile;
use app\models\SignupForm;
use app\models\User;
use app\modules\admin\models\AdminSignupForm;
use app\modules\admin\models\LanguageProficiency;
use app\modules\admin\models\ProfileSearch;
use Yii;
use yii\base\Model;
use yii\db\Exception;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\modules\admin\models\Filtration;
use yii\web\UploadedFile;

/**
 * ProfileController implements the CRUD actions for Profile model.
 */
class ProfileController extends Controller
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
     * Lists all Profile models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProfileSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $photos = Photo::find()->where(['profile_id' => $id])->orderBy(['position' => 'SORT_DESC'])->all();

        return $this->render('view', [
            'photos' => $photos,
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * @return string|\yii\web\Response
     * @throws \yii\base\Exception
     */
    public function actionCreate()
    {
        $filtration = new Filtration();
        $model = new AdminSignupForm();
        $profile = new Profile();
        $multiUpload = new MultipleUploadImg();
        $languageProfile = new LanguageProfile();

        $languageProficiency = ArrayHelper::map(LanguageProficiency::find()->all(), 'id', 'name_ru');

        if ($model->load(Yii::$app->request->post())
            && $model->validate()
            && $multiUpload->load(Yii::$app->request->post())
            && $profile->load(Yii::$app->request->post())
            && $profile->validate()) {

            if ($user = $model->signup()) {
                $profile->user_id = $user->id;

                if (Yii::$app->language === 'ru') {
                    $profile->weight_lbs = round($profile->weight * 2.2);
                    $profile->growth_ft = round($profile->growth / 30.48);
                } else {
                    $profile->weight_lbs = $profile->weight;
                    $profile->growth_ft = $profile->growth;
                    $profile->weight = round($profile->weight / 2.2);
                    $profile->growth = $profile->growth * 30.48;
                }

                if (Yii::$app->request->post('children') == 'no') {
                    $profile->children = 'No children';
                    $profile->count_children = 'No children';
                } else if (Yii::$app->request->post('children') == 'yes') {
                    $profile->children = implode(',', Yii::$app->request->post('childrensAge'));
                    $profile->count_children = count(explode(',', $profile->children));
                }

                $profile->age = $profile->getAge();
                $save = $profile->save(false);

                $languages = Yii::$app->request->post('languageProfile');
                $languages = array_combine($languages['language'], $languages['level']);
                foreach ($languages as $language => $level) {
                    $languageProfile = new LanguageProfile();
                    $languageProfile->profile_id = $profile->id;
                    $languageProfile->language_id = $language;
                    $languageProfile->language_proficiency_id = $level;

                    $languageProfile->save();
                }


                if ($multiUpload->upload('photo/' . $user->id)) {
                    foreach ($multiUpload->getTitles() as $title) {
                        $photo = new Photo();
                        $photo->name = $title;
                        $photo->profile_id = $profile->id;
                        $photo->save(false);
                    }
                }

                if ($save) {
                    Yii::$app->session->addFlash('success', Yii::t('app', 'Registration completed successfully'));

                    return $this->redirect('index');
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
            'photos' => $multiUpload,
            'profile' => $profile,
            'countries' => $filtration->getCountries(),
            'days' => $filtration->getDays(),
            'months' => $filtration->getMonths(),
            'years' => $filtration->getYears(),
            'growth' => $filtration->getGrowth(),
            'weight' => $filtration->getWeight(),
            'languageProficiency' => $languageProficiency,
            'languages' => $filtration->getLanguages(),
            'languageProfile' => $languageProfile,
        ]);
    }


    public function actionPosition($profileId, $photoId)
    {
        $photos = Photo::findAll(['profile_id' => $profileId]);
        foreach ($photos as $photo) {
            if ($photo->id == $photoId) {
                $photo->position = 1;

                $photo->update(false);

            } else {
                $photo->position = 2;
                $photo->update(false);
            }

        }


        return $this->redirect(['profile/update', 'id' => $profileId]);
    }

    /**
     * @param int $id
     * @return \yii\web\Response
     * @throws \Exception
     * @throws \yii\db\StaleObjectException
     */
    public function actionDeleteImg(int $id)
    {
        $photo = Photo::findOne($id);

        if ($photo instanceof Photo) {
            $link = Yii::getAlias("@app/web/uploads/images/photo/$photo->profile_id/$photo->name");
            file_exists($link) ? unlink($link) : false;
            $photo->delete();

            return $this->redirect(['profile/view', 'id' => $photo->profile_id]);
        }

        return $this->redirect(['profile/view', 'id' => $photo->profile_id]);
    }

    /**
     * @param int $id
     * @return string|Response
     * @throws NotFoundHttpException
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\base\Exception
     * @throws \yii\db\StaleObjectException
     */
    public function actionUpdate(int $id)
    {
        $multiUpload = new MultipleUploadImg();
        $languageProfile = new LanguageProfile();
        $filtration = new Filtration();
        $languageProficiency = ArrayHelper::map(LanguageProficiency::find()->all(), 'id', 'name_ru');
        $profile = $this->findModel($id);
        $editProfile = new ProfileEditForm();

        if ($profile->load(Yii::$app->request->post()) && $profile->validate()) {


            if ($multiUpload->upload('photo/' . $profile->user_id)) {
                foreach ($multiUpload->getTitles() as $title) {
                    $photo = new Photo();
                    $photo->name = $title;
                    $photo->profile_id = $id;
                    $photo->save(false);
                }
            }

            if (Yii::$app->request->post('children') == 'no') {
                $profile->children = 'No children';
                $profile->count_children = 'No children';
            } else if (Yii::$app->request->post('children') == 'yes') {
                $profile->children = implode(',', Yii::$app->request->post('childrensAge'));
                $profile->count_children = (string)count(explode(',', $profile->children));
            }

            $profile->age = $profile->getAge();
            $profile->update(false);

            /** remove all languages */
            $profile->unlinkAll('languages', true);

            $languages = Yii::$app->request->post('languageProfile');

            if (is_array($languages['language']) && is_array($languages['level'])) {
                $languages = array_combine($languages['language'], $languages['level']);
                foreach ($languages as $language => $level) {
                    $languageProfile = new LanguageProfile();
                    $languageProfile->profile_id = $profile->id;
                    $languageProfile->language_id = $language;
                    $languageProfile->language_proficiency_id = $level;
                    $languageProfile->save();
                }
            }

            return $this->redirect(['view', 'id' => $profile->id]);
        }

        $childrens = explode(',', $profile->children);

        $photos = Photo::find()->where(['profile_id' => $profile->id])->orderBy(['position' => 'SORT_DESC'])->all();
        return $this->render('update', [
            'editProfile' => $editProfile,
            'profile' => $profile,
            'photos' => $photos,
            'multiUpload' => $multiUpload,
            'countries' => $filtration->getCountries(),
            'languages' => $filtration->getLanguages(),
            'days' => $filtration->getDays(),
            'months' => $filtration->getMonths(),
            'years' => $filtration->getYears(),
            'growth' => $filtration->getGrowth(),
            'weight' => $filtration->getWeight(),
            'languageProficiency' => $languageProficiency,
            'languageProfile' => $languageProfile,
            'childrens' => $childrens

        ]);
    }


    /**
     * @param int $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     * @throws \Exception
     * @throws \yii\db\StaleObjectException
     */
    public function actionNewLogin(int $id)
    {
        $model = $this->findModel($id);
        $signUpForm = new SignupForm();
        $signUpForm->email = $model->user->email;

        if ($signUpForm->load(Yii::$app->request->post())) {
            $model->user->setPassword($signUpForm->password);
            $model->user->email = $signUpForm->email;
            $model->user->update();
            $model->update();

            Yii::$app->session->addFlash('success', 'вход успешн изменен');

            return $this->refresh();
        }

        return $this->render('newLogin', [
            'model' => $model,
            'signupForm' => $signUpForm,
        ]);
    }


    public function actionDelete($id)
    {
        $profile = Profile::findOne(['id' => $id]);
        if ($profile->user->status) {
            $profile->user->status = 0;
            $profile->user->update();
        } else {
            $profile->user->status = 10;
            $profile->user->update();
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Profile model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Profile the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Profile::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
