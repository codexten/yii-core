<?php

namespace codexten\yii\db;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\Query;
use yii\helpers\ArrayHelper;

trait SearchModelTrait
{
    public $sort = [];
    /**
     * @var \yii\db\ActiveRecord
     */
    public $modelClass;

    public $addOnQuery;

    public $querySearchFields = [];
    public $q = '';
    public $dataProvider = [
        'class' => ActiveDataProvider::class,
        'pagination' => [
            'pageSizeLimit' => [1,100],
        ],
    ];
    public $totalCountLimit = false;

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

        /* @var $dataProvider ActiveDataProvider */
        $dataProvider = Yii::createObject(ArrayHelper::merge(
            $this->dataProvider, [
                'query' => $query,
                'sort' => $this->sort,
            ]
        ));

        if ($this->q && !empty($this->querySearchFields)) {
            $condition[] = 'or';
            $querySearchFields = is_array($this->querySearchFields) ? $this->querySearchFields : [$this->querySearchFields];
            foreach ($querySearchFields as $querySearchField) {
                $condition[] = ['like', $querySearchField, $this->q];
            }

            if (!isset($condition[2])) {
                $condition = $condition[1];
            }

            $query->andWhere($condition);
        }

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $this->addFilters($query);

        if ($this->totalCountLimit) {
            $countQuery = clone $query;
            $countQuery->limit($this->totalCountLimit);
            $totalCount = (new Query)->from($countQuery)->count('*');

            $dataProvider->setTotalCount($totalCount);
        }


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
