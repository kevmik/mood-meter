<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * MeterSearch represents the model behind the search form about `app\models\Meter`.
 */
class MeterSearch extends Meter
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'building_id', 'energy', 'pleasantness'], 'integer'],
            [['date_created', 'date_meter'], 'safe'],
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
        $query = Meter::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=>[
	            'defaultOrder'=>['id'=> SORT_DESC],
            ],
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
            'building_id' => $this->building_id,
            'energy' => $this->energy,
            'pleasantness' => $this->pleasantness,
            'date_created' => $this->date_created,
            'date_meter' => $this->date_meter,
        ]);

        return $dataProvider;
    }
}
