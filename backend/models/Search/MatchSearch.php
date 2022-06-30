<?php

namespace backend\models\Search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Match;

/**
 * MatchSearch represents the model behind the search form about `common\models\Match`.
 */
class MatchSearch extends Match
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'gid', 'userid', 'category_id', 'reg_start_time', 'reg_end_time', 'start_time', 'end_time', 'province_id', 'city_id', 'district_id', 'matchtype', 'weight', 'status', 'publish', 'create_time', 'swim_address_id'], 'integer'],
            [['title', 'icon', 'imgurl', 'intro', 'province', 'city', 'district', 'address', 'tips', 'disclaimer', 'qrcode', 'update_time', 'logo'], 'safe'],
            [['longitude', 'latitude'], 'number'],
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
        $query = Match::find()
            ->where([
                'status' => Match::STATUS_VALID,
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
            'gid' => $this->gid,
            'userid' => $this->userid,
            'category_id' => $this->category_id,
            'reg_start_time' => $this->reg_start_time,
            'reg_end_time' => $this->reg_end_time,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'province_id' => $this->province_id,
            'city_id' => $this->city_id,
            'district_id' => $this->district_id,
            'longitude' => $this->longitude,
            'latitude' => $this->latitude,
            'matchtype' => $this->matchtype,
            'weight' => $this->weight,
            'status' => $this->status,
            'publish' => $this->publish,
            'create_time' => $this->create_time,
            'update_time' => $this->update_time,
            'swim_address_id' => $this->swim_address_id,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'icon', $this->icon])
            ->andFilterWhere(['like', 'imgurl', $this->imgurl])
            ->andFilterWhere(['like', 'intro', $this->intro])
            ->andFilterWhere(['like', 'province', $this->province])
            ->andFilterWhere(['like', 'city', $this->city])
            ->andFilterWhere(['like', 'district', $this->district])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'tips', $this->tips])
            ->andFilterWhere(['like', 'disclaimer', $this->disclaimer])
            ->andFilterWhere(['like', 'qrcode', $this->qrcode])
            ->andFilterWhere(['like', 'logo', $this->logo]);

        return $dataProvider;
    }
}
