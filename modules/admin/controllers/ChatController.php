<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use app\models\ChatSettings;
use app\models\User;
use yii\data\ActiveDataProvider;

/**
 * Description of ChatController
 *
 * @author Dmytro S. <freelancerua@protonmail.com>
 */
class ChatController extends Controller
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
    
    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        $this->layout = 'main.php'; //your layout name
        return parent::beforeAction($action);
    }
    
    /**
     * Chat settings
     * @return mixed
     */
    public function actionIndex()
    {
        // Price
        $price = 0;
        $priceObj = ChatSettings::find()
                ->where(['name' => ChatSettings::SETTINGS_PRICE])
                ->one();
        
        // Update settings
        if(Yii::$app->request->isPost) {
            // If price
            $price = abs(Yii::$app->request->post('price', 0));
            if($price > 0 && $priceObj) {
                $priceObj->value = (string) $price;
                $priceObj->update();
            }
        }

        // Create price settings or get value
        if(!$priceObj) {
            $priceObj = new ChatSettings();
            $priceObj->name = ChatSettings::SETTINGS_PRICE;
            $priceObj->value = (string) 0;
            if(! $priceObj->save()) {
                return var_dump($priceObj->errors);
            }
           
        } else {
            $price = (int) $priceObj->value;
        }
        
        return $this->render('index', [
            'price' => $price,
        ]);
    }
    
    /**
     * 
     */
    public function actionUserBan()
    {
        $query = User::find();
        $user = new User();
        $user->scenario = 'search';
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        $user->load(Yii::$app->request->queryParams);

        if (!$user->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        
         // grid filtering conditions
        $query->andFilterWhere([
            'id' => $user->id,
            'chat_banned' => $user->chat_banned,
        ]);
        
         $query->andFilterWhere(['like', 'email', $user->email]);

        return $this->render('userban', [
            'searchModel' => $user,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    /**
     * Toggle user ban status
     * @param integer id
     */
    public function actionToggleBan($id)
    {
        $user = User::findOne((int)$id);

        if(!$user) {
            throw new \yii\web\NotFoundHttpException('User not found');
        }
        
        // Toggle state
        if($user->chat_banned > 0) {
            $user->chat_banned = 0;
        } else {
            $user->chat_banned = 1;
        }
        
        $user->update();
        $this->redirect(['chat/user-ban']);
    }
}
