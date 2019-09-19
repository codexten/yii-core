<?php

namespace codexten\yii\actions;

use codexten\yii\db\SearchModelInterface;
use Yii;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\data\DataFilter;
use yii\db\BaseActiveRecord;
use yii\di\Instance;
use yii\helpers\ArrayHelper;

trait IndexActionTrait
{
    /**
     * @var callable a PHP callable that will be called to prepare a data provider that
     * should return a collection of the models. If not set, [[prepareDataProvider()]] will be used instead.
     * The signature of the callable should be:
     *
     * ```php
     * function (IndexAction $action) {
     *     // $action is the action object currently running
     * }
     * ```
     *
     * The callable should return an instance of [[ActiveDataProvider]].
     *
     * If [[dataFilter]] is set the result of [[DataFilter::build()]] will be passed to the callable as a second parameter.
     * In this case the signature of the callable should be the following:
     *
     * ```php
     * function (IndexAction $action, mixed $filter) {
     *     // $action is the action object currently running
     *     // $filter the built filter condition
     * }
     * ```
     */
    public $prepareDataProvider;
    /**
     * @var DataFilter|null data filter to be used for the search filter composition.
     * You must setup this field explicitly in order to enable filter processing.
     * For example:
     *
     * ```php
     * [
     *     'class' => 'yii\data\ActiveDataFilter',
     *     'searchModel' => function () {
     *         return (new \yii\base\DynamicModel(['id' => null, 'name' => null, 'price' => null]))
     *             ->addRule('id', 'integer')
     *             ->addRule('name', 'trim')
     *             ->addRule('name', 'string')
     *             ->addRule('price', 'number');
     *     },
     * ]
     * ```
     *
     * @see DataFilter
     *
     * @since 2.0.13
     */
    public $dataFilter;
    public $searchModel;
    /**
     * @var callable a PHP callable that will be called to create the new search model.
     * If not set, [[newSearchModel()]] will be used instead.
     * The signature of the callable should be:
     *
     * ```php
     * function ($action) {
     *     // $action is the action object currently running
     * }
     * ```
     *
     * The callable should return the new model instance.
     */
    public $newSearchModel;

    /**
     * @var callable
     * ```php
     * function ($query) {
     *  // $query
     * }
     * ```
     */
    public $queryModifier;

    public function init()
    {
        if ($this->searchModel) {
            if (!is_array($this->searchModel)) {
                $this->searchModel = ['class' => $this->searchModel];
            }
            $this->searchModel = ArrayHelper::merge(
                [
                    'modelClass' => $this->modelClass,
                ],
                $this->searchModel
            );
            $this->searchModel = Instance::ensure($this->searchModel, SearchModelInterface::class);
        }
        parent::init();
    }


    /**
     * Creates new search model instance.
     *
     * @return mixed|null
     */
    public function newSearchModel()
    {
        if ($this->newSearchModel !== null) {

            return call_user_func($this->newSearchModel, $this);
        }
        if ($this->controller->hasMethod('newSearchModel')) {

            return call_user_func([$this->controller, 'newSearchModel'], $this);
        }

        return $this->searchModel;
    }

    /**
     * Prepares the data provider that should return the requested collection of the models.
     *
     * @param $searchModel
     *
     * @return mixed|object|ActiveDataProvider|DataFilter|null
     * @throws InvalidConfigException
     */
    protected function prepareDataProvider($searchModel)
    {
        $requestParams = Yii::$app->getRequest()->getBodyParams();
        if (empty($requestParams)) {
            $requestParams = Yii::$app->getRequest()->getQueryParams();
        }

        $filter = null;
        if ($this->dataFilter !== null) {
            $this->dataFilter = Yii::createObject($this->dataFilter);
            if ($this->dataFilter->load($requestParams)) {
                $filter = $this->dataFilter->build();
                if ($filter === false) {
                    return $this->dataFilter;
                }
            }
        }

        if ($this->prepareDataProvider !== null) {
            return call_user_func($this->prepareDataProvider, $this, $filter);
        }
        if ($searchModel !== null) {
            return $searchModel->search(Yii::$app->request->queryParams);
        }


        /* @var $modelClass BaseActiveRecord */
        $modelClass = $this->modelClass;

        $query = $modelClass::find();

        if ($this->queryModifier !== null) {
            call_user_func($this->queryModifier, $query);
        }

        if (!empty($filter)) {
            $query->andWhere($filter);
        }

        return Yii::createObject([
            'class' => ActiveDataProvider::class,
            'query' => $query,
            'pagination' => [
                'params' => $requestParams,
            ],
            'sort' => [
                'params' => $requestParams,
            ],
        ]);
    }
}
