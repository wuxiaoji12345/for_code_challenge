<?php

namespace backend\models\Search;

use backend\controllers\SwimEventRegisterInfoController;
use backend\models\RegisterInfo;
use common\models\Event;
use common\models\EventInfo;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\EventRelation;

/**
 * EventRelationSearch represents the model behind the search form about `backend\models\EventRelation`.
 */
class EventRelationSearch extends EventRelation
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'rgid', 'state', 'paytype', 'typeid', 'matchid', 'sendnotice', 'lastpaytime', 'gnum', 'seosource', 'urid', 'category_id', 'type', 'ischeck', 'gid', 'app', 'create_time'], 'integer'],
            [['fees', 'specfees', 'orgfees', 'specdiscount'], 'number'],
            [['trade_no', 'order_no', 'refund_no', 'out_refund_no', 'refund_desc', 'mobile', 'paytime', 'name', 'typename', 'speccode', 'payinfo', 'reqinfo', 'update_time', 'invitecode'], 'safe'],
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
        $query = EventRelation::find()
            ->leftJoin(EventInfo::tableName(), 'swim_event_relation.id=rrid')
            ->orderBy([
                'swim_event_relation.id' => SORT_DESC,
            ]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        if (isset($params['eventName'])) {
            $query->andWhere([
                'swim_event_relation.matchid' => (new Event())->getIDsByTitle($params['eventName']),
            ]);
        }

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'swim_event_relation.id' => $this->id,
            'rgid' => $this->rgid,
            'fees' => $this->fees,
            'swim_event_relation.state' => $this->state,
            'paytype' => $this->paytype,
            'typeid' => $this->typeid,
            'swim_event_relation.matchid' => $this->matchid,
            'sendnotice' => $this->sendnotice,
            'lastpaytime' => $this->lastpaytime,
            'paytime' => $this->paytime,
            'gnum' => $this->gnum,
            'seosource' => $this->seosource,
            'specfees' => $this->specfees,
            'urid' => $this->urid,
            'category_id' => $this->category_id,
            'orgfees' => $this->orgfees,
            'specdiscount' => $this->specdiscount,
            'type' => $this->type,
            'ischeck' => $this->ischeck,
            'gid' => $this->gid,
            'app' => $this->app,
            'swim_event_relation.create_time' => $this->create_time,
            'swim_event_relation.update_time' => $this->update_time,
        ]);

        $query->andFilterWhere(['like', 'trade_no', $this->trade_no])
            ->andFilterWhere(['like', 'order_no', $this->order_no])
            ->andFilterWhere(['like', 'refund_no', $this->refund_no])
            ->andFilterWhere(['like', 'out_refund_no', $this->out_refund_no])
            ->andFilterWhere(['like', 'refund_desc', $this->refund_desc])
            ->andFilterWhere(['like', 'swim_event_info.mobile', $this->mobile])
            ->andFilterWhere(['like', 'swim_event_info.name', $this->name])
            ->andFilterWhere(['like', 'typename', $this->typename])
            ->andFilterWhere(['like', 'speccode', $this->speccode])
            ->andFilterWhere(['like', 'payinfo', $this->payinfo])
            ->andFilterWhere(['like', 'reqinfo', $this->reqinfo])
            ->andFilterWhere(['like', 'invitecode', $this->invitecode]);

        return $dataProvider;
    }
}
