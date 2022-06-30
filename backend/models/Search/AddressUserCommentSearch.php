<?php

namespace backend\models\Search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\AddressUserComment;

/**
 * AddressUserCommentSearch represents the model behind the search form about `common\models\AddressUserComment`.
 */
class AddressUserCommentSearch extends AddressUserComment
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'swim_address_id', 'user_id', 'score', 'status', 'create_time'], 'integer'],
            [['comment_date', 'comment', 'update_time'], 'safe'],
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
        $query = AddressUserComment::find()
            ->where(['status' => AddressUserComment::STATUS_VALID])
            ->orderBy(['id' => SORT_DESC]);

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
            'comment_date' => $this->comment_date,
            'user_id' => $this->user_id,
            'score' => $this->score,
            'status' => $this->status,
            'create_time' => $this->create_time,
            'update_time' => $this->update_time,
        ]);

        $query->andFilterWhere(['like', 'comment', $this->comment]);

        return $dataProvider;
    }
}
