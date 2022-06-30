<?php

namespace backend\models\Search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\AddressCheckComment;

/**
 * AddressCheckCommentSearch represents the model behind the search form about `backend\models\AddressCheckComment`.
 */
class AddressCheckCommentSearch extends AddressCheckComment
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'swim_address_check_id', 'swim_address_id', 'bkurid', 'is_stadium', 'status', 'create_time'], 'integer'],
            [['imgurl', 'comment', 'update_time'], 'safe'],
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
        $query = AddressCheckComment::find()
            ->andWhere([
                'status' => 1,
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
            'swim_address_check_id' => $this->swim_address_check_id,
            'swim_address_id' => $this->swim_address_id,
            'bkurid' => $this->bkurid,
            'is_stadium' => $this->is_stadium,
            'status' => $this->status,
            'create_time' => $this->create_time,
            'update_time' => $this->update_time,
        ]);

        $query->andFilterWhere(['like', 'imgurl', $this->imgurl])
            ->andFilterWhere(['like', 'comment', $this->comment]);

        return $dataProvider;
    }
}
