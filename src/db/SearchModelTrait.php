<?php

namespace codexten\yii\db;


use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

trait SearchModelTrait
{
    public $sort = [];
    /**
     * @var \yii\db\ActiveRecord
     */
    public $modelClass;

    public $addOnQuery;

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search(array $params)
    {
        $query = $this->getBaseQuery();
        if ($this->addOnQuery && is_callable($this->addOnQuery)) {
            call_user_func_array($this->addOnQuery, [&$query]);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => $this->sort,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $this->addFilters($query);

        return $dataProvider;
    }

    /**
     * @return ActiveQuery
     */
    protected function getBaseQuery()
    {
        $modelClass = $this->modelClass;

        return $modelClass::find();
    }

    protected function addFilters(ActiveQuery &$query)
    {

    }
}
