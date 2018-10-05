<?php

namespace app\modules\admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\admin\models\Category;

/**
 * CategorySearch represents the model behind the search form about `app\modules\admin\models\Category`.
 */
class CategorySearch extends Category
{
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['title_ru', 'title_us'], 'safe'],
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
        $query = Category::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $dataProvider->setSort([
            'attributes' => [
                'categoryName' => [
                    'asc' => ['category.name' => SORT_ASC],
                    'desc' => ['category.name' => SORT_DESC],
                    'label' => 'Category Name'
                ]
            ]
        ]);

        if (!($this->load($params) && $this->validate())) {
            /**
             *  greedy data loading Category
             * for sorting work.
             */
            return $dataProvider;
        }
        $query->joinWith(['category' => function ($q) {
            $q->where('category.name LIKE "%' . $this->categoryName . '%"');
        }]);
        

        $query->andFilterWhere(['like', 'title_ru', $this->title_ru]);

        return $dataProvider;
    }
}
