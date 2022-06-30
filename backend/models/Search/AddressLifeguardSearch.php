<?php

namespace backend\models\Search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\AddressLifeguard;

/**
 * AddressLifeguardSearch represents the model behind the search form about `backend\models\AddressLifeguard`.
 */
class AddressLifeguardSearch extends AddressLifeguard
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'swim_address_id', 'gender', 'cert_type', 'status', 'create_time'], 'integer'],
            [['name', 'mobile', 'id_card', 'cert_level', 'update_time'], 'safe'],
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
        $query = AddressLifeguard::find()
            ->andWhere([
                'status' => self::STATUS_VALID,
            ])
            ->orderBy([
                'id' => SORT_DESC,
            ]);

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

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'swim_address_id' => $this->swim_address_id,
            'gender' => $this->gender,
            'cert_type' => $this->cert_type,
            'status' => $this->status,
            'create_time' => $this->create_time,
            'update_time' => $this->update_time,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'mobile', $this->mobile])
            ->andFilterWhere(['like', 'id_card', $this->id_card])
            ->andFilterWhere(['like', 'cert_level', $this->cert_level]);

        return $dataProvider;
    }
}
