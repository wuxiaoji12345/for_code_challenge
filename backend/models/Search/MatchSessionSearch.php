<?php

namespace backend\models\Search;

use backend\models\Address;
use backend\models\Match;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\MatchSession;


/**
 * MatchSessionSearch represents the model behind the search form about `common\models\SwimMatchSession`.
 */
class MatchSessionSearch extends MatchSession
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'matchid', 'lane', 'status', 'swim_address_id', 'register_count', 'create_time'], 'integer'],
            [['name', 'start_time', 'province', 'city', 'district', 'stadium', 'address', 'cert_template', 'update_time'], 'safe'],
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
        $query = MatchSession::find()
            ->where([
                'status' => MatchSession::STATUS_VALID
            ])
            ->orderBy([
                'id' => SORT_DESC
            ]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        if (isset($params['matchid'])) {
            $query->andWhere([
                'matchid' => $params['matchid'],
            ]);
        }
        if (isset($params['matchName'])) {
            $query->andWhere([
                'matchid' => (new Match())->getIDsByTitle($params['matchName']),
            ]);
        }
        if (isset($params['addressName'])) {
            $query->andWhere([
                'swim_address_id' => (new Address())->getIDsByName($params['addressName']),
            ]);
        }

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'matchid' => $this->matchid,
            'start_time' => $this->start_time,
            'longitude' => $this->longitude,
            'latitude' => $this->latitude,
            'lane' => $this->lane,
            'status' => $this->status,
            'swim_address_id' => $this->swim_address_id,
            'register_count' => $this->register_count,
            'create_time' => $this->create_time,
            'update_time' => $this->update_time,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'province', $this->province])
            ->andFilterWhere(['like', 'city', $this->city])
            ->andFilterWhere(['like', 'district', $this->district])
            ->andFilterWhere(['like', 'stadium', $this->stadium])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'cert_template', $this->cert_template]);

        return $dataProvider;
    }
}
