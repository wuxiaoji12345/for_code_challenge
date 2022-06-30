<?php

namespace backend\models\Search;

use backend\models\Match;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\RegisterType;

/**
 * RegisterTypeSearch represents the model behind the search form about `common\models\SwimRegisterType`.
 */
class RegisterTypeSearch extends RegisterType
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'matchid', 'mincount', 'maxcount', 'fmincount', 'fmaxcount', 'amount', 'num', 'type', 'needcheck', 'registerlimit', 'allforpay', 'weight', 'create_time'], 'integer'],
            [['title', 'notice', 'groupform', 'registerform', 'update_time'], 'safe'],
            [['fees'], 'number'],
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
        $query = RegisterType::find()
            ->orderBy([
                'weight' => SORT_DESC,
                'id' => SORT_DESC,
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

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'matchid' => $this->matchid,
            'mincount' => $this->mincount,
            'maxcount' => $this->maxcount,
            'fmincount' => $this->fmincount,
            'fmaxcount' => $this->fmaxcount,
            'fees' => $this->fees,
            'amount' => $this->amount,
            'num' => $this->num,
            'type' => $this->type,
            'needcheck' => $this->needcheck,
            'registerlimit' => $this->registerlimit,
            'allforpay' => $this->allforpay,
            'weight' => $this->weight,
            'create_time' => $this->create_time,
            'update_time' => $this->update_time,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'notice', $this->notice])
            ->andFilterWhere(['like', 'groupform', $this->groupform])
            ->andFilterWhere(['like', 'registerform', $this->registerform]);

        return $dataProvider;
    }
}
