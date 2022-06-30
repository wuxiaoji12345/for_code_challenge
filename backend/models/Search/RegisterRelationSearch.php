<?php

namespace backend\models\Search;

use backend\models\Match;
use backend\models\RegisterDetail;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\RegisterRelation;
use backend\models\RegisterInfo;

/**
 * RegisterRelationSearch represents the model behind the search form about `common\models\SwimRegisterRelation`.
 */
class RegisterRelationSearch extends RegisterRelation
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'urid', 'matchid', 'typeid', 'state', 'paytype', 'sendnotice', 'lastpaytime', 'type', 'ischeck', 'gnum', 'app', 'create_time'], 'integer'],
            [['order_no', 'trade_no', 'typename', 'paytime', 'payinfo', 'name', 'mobile', 'regname', 'unit', 'leader', 'leadermobile', 'groupcode', 'groupinfos', 'update_time'], 'safe'],
            [['orgfees', 'fees'], 'number'],
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
        //默认进入时显示已支付数据
        if (empty($params)) {
            $params =[
                'RegisterRelationSearch' => [
                    'state' => RegisterRelation::STATE_PAID,
                ]
            ];
        }
        $query = RegisterRelation::find()
            ->orderBy([
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
        if (isset($params['ssid']) && !empty($params['ssid'])) {
            $query->leftJoin(RegisterDetail::tableName(), 'rrid=swim_register_relation.id')
                ->andWhere([
                'ssid' => $params['ssid'],
            ]);
        }
        if (isset($params['userName']) || isset($params['userMobile'])) {
            $name = isset($params['userName']) ? $params['userName'] : '';
            $mobile = isset($params['userMobile']) ? $params['userMobile'] : '';
            $query->andWhere([
                'swim_register_relation.id' => (new RegisterInfo())->getUridsByNameMobile($name, $mobile),
            ]);
        }

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'swim_register_relation.id' => $this->id,
            'urid' => $this->urid,
            'matchid' => $this->matchid,
            'typeid' => $this->typeid,
            'orgfees' => $this->orgfees,
            'fees' => $this->fees,
            'state' => $this->state,
            'paytype' => $this->paytype,
            'sendnotice' => $this->sendnotice,
            'lastpaytime' => $this->lastpaytime,
            'paytime' => $this->paytime,
            'type' => $this->type,
            'ischeck' => $this->ischeck,
            'gnum' => $this->gnum,
            'app' => $this->app,
            'create_time' => $this->create_time,
            'update_time' => $this->update_time,
        ]);

        $query->andFilterWhere(['like', 'order_no', $this->order_no])
            ->andFilterWhere(['like', 'trade_no', $this->trade_no])
            ->andFilterWhere(['like', 'typename', $this->typename])
            ->andFilterWhere(['like', 'payinfo', $this->payinfo])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'mobile', $this->mobile])
            ->andFilterWhere(['like', 'regname', $this->regname])
            ->andFilterWhere(['like', 'unit', $this->unit])
            ->andFilterWhere(['like', 'leader', $this->leader])
            ->andFilterWhere(['like', 'leadermobile', $this->leadermobile])
            ->andFilterWhere(['like', 'groupcode', $this->groupcode])
            ->andFilterWhere(['like', 'groupinfos', $this->groupinfos]);

        return $dataProvider;
    }
}
