<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Page;

/**
 * PageSearch represents the model behind the search form of `app\models\Page`.
 */
class PageSearch extends Page
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'rating', 'access'], 'integer'],
            [['author', 'slug', 'category', 'header', 'creation_date', 'mod_date', 'brief_content', 'content'], 'safe'],
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
        $query = Page::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'rating' => $this->rating,
            'access' => $this->access,
        ]);

        $query->andFilterWhere(['like', 'author', $this->author])
            ->andFilterWhere(['like', 'slug', $this->slug])
            ->andFilterWhere(['like', 'category', $this->category])
            ->andFilterWhere(['like', 'header', $this->header])
            ->andFilterWhere(['like', 'creation_date', $this->creation_date])
            ->andFilterWhere(['like', 'mod_date', $this->mod_date])
            ->andFilterWhere(['like', 'brief_content', $this->brief_content])
            ->andFilterWhere(['like', 'content', $this->content]);

        return $dataProvider;
    }
}
