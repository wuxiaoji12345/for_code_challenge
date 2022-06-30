<?php

namespace backend\models\Search;

use backend\models\Match;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ScoreEnroll;
use backend\models\MatchSessionItem;

/**
 * ScoreEnrollSearch represents the model behind the search form about `common\models\SwimScoreEnroll`.
 */
class ScoreEnrollSearch extends ScoreEnroll
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'matchid', 'ssid', 'itemid', 'gender', 'order', 'point', 'additionaltime', 'ischeckin', 'type', 'status', 'create_time'], 'integer'],
            [['number', 'chipid', 'name', 'unit', 'phone', 'idcard', 'additionalreason', 'extrainfo', 'update_time'], 'safe'],
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
        $query = ScoreEnroll::find()
            ->where([
                'status' => ScoreEnroll::STATUS_VALID,
            ])
            ->orderBy([
                'id' => SORT_DESC
            ]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        if (isset($params['matchName'])) {
            $query->andWhere([
                'matchid' => (new Match())->getIDsByTitle($params['matchName']),
            ]);
        }
        if (isset($params['itemName'])) {
            $query->andWhere([
                'itemid' => (new MatchSessionItem())->getIDsByName($params['itemName']),
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
            'itemid' => $this->itemid,
            'gender' => $this->gender,
            'order' => $this->order,
            'point' => $this->point,
            'additionaltime' => $this->additionaltime,
            'ischeckin' => $this->ischeckin,
            'type' => $this->type,
            'status' => $this->status,
            'create_time' => $this->create_time,
            'update_time' => $this->update_time,
        ]);

        $query->andFilterWhere(['like', 'number', $this->number])
            ->andFilterWhere(['like', 'chipid', $this->chipid])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'unit', $this->unit])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'idcard', $this->idcard])
            ->andFilterWhere(['like', 'additionalreason', $this->additionalreason])
            ->andFilterWhere(['like', 'extrainfo', $this->extrainfo]);

        return $dataProvider;
    }
}
