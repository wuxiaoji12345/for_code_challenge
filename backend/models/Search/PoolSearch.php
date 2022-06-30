<?php

namespace backend\models\Search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Pool;

/**
 * PoolSearch represents the model behind the search form about `backend\models\Pool`.
 */
class PoolSearch extends Pool
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'sid', 'weight', 'status'], 'integer'],
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
     * @throws
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Pool::find()
            ->andWhere([
                'status' => self::STATUS_VALID,
            ])
            ->orderBy([
                'id' => SORT_DESC,
            ]);
        $addressID = Yii::$app->user->getIdentity()->swim_address_id;
        if ($addressID > 0) {
            $query->andWhere([
                'sid' => $addressID,
            ]);
        }
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
            'sid' => $this->sid,
            'weight' => $this->weight,
            'status' => $this->status,
            'update_time' => $this->update_time,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
