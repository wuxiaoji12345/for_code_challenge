<?php

namespace backend\models\Search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Banners;

/**
 * BannersSearch represents the model behind the search form about `common\models\Banners`.
 */
class BannersSearch extends Banners
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'position', 'jumptype', 'status', 'create_time', 'weight'], 'integer'],
            [['imgurl', 'jumpurl', 'jumpvalue', 'starttime', 'endtime', 'update_time'], 'safe'],
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
        $query = Banners::find()
            ->where([
                'status' => Banners::STATUS_VALID
            ])
            ->orderBy([
                'id' => SORT_DESC
            ]);

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
            'position' => $this->position,
            'jumptype' => $this->jumptype,
            'starttime' => $this->starttime,
            'endtime' => $this->endtime,
            'status' => $this->status,
            'create_time' => $this->create_time,
            'update_time' => $this->update_time,
            'weight' => $this->weight,
        ]);

        $query->andFilterWhere(['like', 'imgurl', $this->imgurl])
            ->andFilterWhere(['like', 'jumpurl', $this->jumpurl])
            ->andFilterWhere(['like', 'jumpvalue', $this->jumpvalue]);

        return $dataProvider;
    }
}
