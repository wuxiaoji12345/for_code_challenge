<?php

namespace backend\models\Search;

use backend\models\Match;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\MatchSessionItem;

/**
 * MatchSessionItemSearch represents the model behind the search form about `common\models\SwimMatchSessionItem`.
 */
class MatchSessionItemSearch extends MatchSessionItem
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'matchid', 'ssid', 'type', 'gender', 'distance', 'agemin', 'agemax', 'status', 'weight', 'create_time'], 'integer'],
            [['name', 'update_time'], 'safe'],
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
        $query = MatchSessionItem::find()
            ->where([
                'status' => MatchSessionItem::STATUS_VALID,
            ])
            ->orderBy([
                'id' => SORT_DESC,
                'weight' => SORT_DESC,
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

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'matchid' => $this->matchid,
            'ssid' => $this->ssid,
            'type' => $this->type,
            'gender' => $this->gender,
            'distance' => $this->distance,
            'agemin' => $this->agemin,
            'agemax' => $this->agemax,
            'status' => $this->status,
            'weight' => $this->weight,
            'create_time' => $this->create_time,
            'update_time' => $this->update_time,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
