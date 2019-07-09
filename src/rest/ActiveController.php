<?php

namespace codexten\yii\rest;

//use yii\rest\IndexAction as IndexAction;

class ActiveController extends \yii\rest\ActiveController
{
    public $newSearchModel;

    /**
     * {@inheritDoc}
     */
    public function actions()
    {
        $actions = parent::actions();
        $actions['index']['class'] = IndexAction::class;
        $actions['index']['newSearchModel'] = $this->newSearchModel;

        return $actions;
    }
}
