<?php

namespace backend\models\Search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\UserChannelExtra;
use common\helpers\Utils;

/**
 * SwimUserChannelExtraSearch represents the model behind the search form about `common\models\SwimUserChannelExtra`.
 */
class UserChannelExtraSearch extends UserChannelExtra
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_channel_id', 'is_checker', 'status', 'create_time'], 'integer'],
            [['update_time'], 'safe'],
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
        $query = UserChannelExtra::find()
            ->where([
                'status' => self::STATUS_VALID
            ])
            ->orderBy([
                'id' => SORT_DESC
            ]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        if (isset($params['user_channel_id_encrypt']) && !empty($params['user_channel_id_encrypt'])) {
            $query->andWhere([
                'user_channel_id' => Utils::ecbDecrypt(\Yii::$app->params['channelIDKey'], $params['user_channel_id_encrypt']),
//                'user_channel_id' => $params['user_channel_id_encrypt'],
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
            'user_channel_id' => $this->user_channel_id,
            'is_checker' => $this->is_checker,
            'status' => $this->status,
            'update_time' => $this->update_time,
            'create_time' => $this->create_time,
        ]);

        return $dataProvider;
    }
}
