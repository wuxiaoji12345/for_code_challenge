<?php

namespace backend\models\Search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\AddressCheck;

/**
 * AddressCheckSearch represents the model behind the search form about `common\models\AddressCheck`.
 */
class AddressCheckSearch extends AddressCheck
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'swim_address_id', 'status', 'create_time'], 'integer'],
            [['longitude', 'latitude'], 'number'],
            [['check_date', 'update_time'], 'safe'],
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
        $query = AddressCheck::find()
            ->andWhere([
                'status' => self::STATUS_VALID,
            ])
            ->orderBy(['check_date' => SORT_DESC]);
        $addressID = Yii::$app->user->getIdentity()->swim_address_id;
        if ($addressID > 0) {
            $query->andWhere([
                'swim_address_id' => $addressID,
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

        if ($this->check_date != '') {
            $query
                ->andWhere([
                    '>=', 'check_date', date('Y-m-d 00:00:00', strtotime($this->check_date))
                ])->andWhere([
                    '<=', 'check_date', date('Y-m-d 23:59:59', strtotime($this->check_date))
                ]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'swim_address_id' => $this->swim_address_id,
            'longitude' => $this->longitude,
            'latitude' => $this->latitude,
            'status' => $this->status,
            'create_time' => $this->create_time,
            'update_time' => $this->update_time,
        ]);

        return $dataProvider;
    }
}
