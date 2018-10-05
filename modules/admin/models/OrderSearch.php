<?php

namespace app\modules\admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\admin\models\Order;

/**
 * OrderSearch represents the model behind the search form about `app\modules\admin\models\Order`.
 */
class OrderSearch extends Order
{
    public $profileName;
    public $paymentSum;
    public $serviceName;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['profileName', 'paymentSum', 'serviceName', 'id'], 'safe'],
            [['payment_status'], 'string'],
            [['name'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Order::find()->joinWith(['profile', 'service']);
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'paymentSum' => [
                    'asc' => ['service.price' => SORT_ASC],
                    'desc' => ['service.price' => SORT_DESC],
                    'label' => 'service price' 
                ],
                'profileName' => [
                    'asc' => ['profile.name' => SORT_ASC],
                    'desc' => ['profile.name' => SORT_DESC],
                    'label' => 'profile name'
                ],
                'serviceName' => [
                    'asc' => ['service.title_ru' => SORT_ASC],
                    'desc' => ['service.title_ru' => SORT_DESC],
                    'label' => 'service title_ru'
                ],
            ]
        ]);

        if (!($this->load($params) && $this->validate())) {
            /**
             *  greedy data loading Category
             * for sorting work.
             */
            return $dataProvider;
        }

        // filtration by category
        $query->joinWith(['profile' => function ($q) {
            $q->where('name LIKE "%' . $this->profileName. '%"');
        }]);
        $query->joinWith(['service' => function ($q) {
            $q->where('price LIKE "%' . $this->paymentSum. '%"');
        }]);
        $query->joinWith(['service' => function ($q) {
            $q->where('title_ru LIKE "%' . $this->serviceName. '%"');
        }]);
        // grid filtering conditions
        $query->andFilterWhere([
            'payment_status' => $this->payment_status,
        ]);

        return $dataProvider;
    }
}
