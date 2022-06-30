<?php

namespace backend\models\Search;

use common\models\Event;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Match;
use yii\db\Expression;

/**
 * MatchSearch represents the model behind the search form about `common\models\Match`.
 */
class EventSearch extends Event
{
    public $keywords;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'category_id', 'create_time', 'start_time', 'end_time', 'province_id', 'city_id', 'district_id', 'reg_start_time', 'reg_end_time','gid','weight','status'], 'integer'],
            [['title', 'update_time', 'address', 'intro', 'icon','keywords','date_range'], 'safe'],
            [['longitude', 'latitude'], 'number'],
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
    public function search($params,$where=[])
    {
        $query = Event::find();
        if($where) $query->andWhere($where);
        //$query->with('category');
        $query->select([
            '*',
            new Expression("(case  when start_time-unix_timestamp(now()) <86400 then '999999999' else (start_time-unix_timestamp(now()))  end) as betweentime"),
        ]);
        $query->orderBy(
            [
                new Expression('betweentime+0 asc'),
                'start_time'=>SORT_DESC
            ]
        );

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
//            'sort'=>[
//                'defaultOrder'=>[
//                    'beweenstarttime'=>SORT_ASC,
//                ],
//                "attributes"=>[
//                    'id',
//                    'start_time',
//                    'end_time',
//                    'create_time',
//                    'dead_time',
//                    'start_time-unix_timestamp(now()) as beweenstarttime'
//                ]
//            ]
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
            'category_id' => $this->category_id,
            'create_time' => $this->create_time,
            'update_time' => $this->update_time,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'province_id' => $this->province_id,
            'city_id' => $this->city_id,
            'gid' => $this->gid,
            'district_id' => $this->district_id,
            'reg_start_time' => $this->reg_start_time,
            'reg_end_time' => $this->reg_end_time,
            'longitude' => $this->longitude,
            'latitude' => $this->latitude,
            'weight' => $this->weight,
            'status'=>$this->status
        ]);



        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'intro', $this->intro])
            ->andFilterWhere(['like', 'icon', $this->icon]);

            $query->andFilterWhere(['or',
                ['like', 'title', $this->keywords],
                ['like', 'address', $this->keywords],
            ]);
            
          if($this->date_range)
          {
              $date_range           =   explode("--",$this->date_range);
              $start_time           =   strtotime($date_range[0]);
              $end_time             =   strtotime($date_range[1])+86399;
              
              $query->andFilterWhere(['and',
                  ['>','start_time',$start_time],
                  ['<','end_time',$end_time],
              ]);
              
          }
            
            
//            echo $query->createCommand()->getSql();exit;
            
        return $dataProvider;
    }
}
