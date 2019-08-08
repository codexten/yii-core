<?php

namespace codexten\yii\rest;

//use yii\rest\IndexAction as IndexAction;

use codexten\yii\db\SearchModel;
use codexten\yii\helpers\ArrayHelper;

class ActiveController extends \yii\rest\ActiveController
{
    public $newSearchModel = null;
    public $searchModel = [];

    /**
     * {@inheritDoc}
     */
    public function actions()
    {
        $actions = parent::actions();
        $actions['index']['class'] = IndexAction::class;
        $actions['index']['newSearchModel'] = $this->newSearchModel;
        $searchModel = ArrayHelper::merge([
            'class' => SearchModel::class,
            'modelClass' => $this->modelClass,
            'q' => \Yii::$app->request->get('q'),
        ], $this->searchModel);

        $searchModel['querySearchFields'] = ArrayHelper::getValue($searchModel, 'querySearchFields', ['name']);
        $actions['index']['searchModel'] = $searchModel;

        return $actions;
    }
}
