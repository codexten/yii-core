<?php

namespace codexten\yii\rest;

use codexten\yii\actions\IndexActionInterface;
use codexten\yii\actions\IndexActionTrait;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\rest\Action;

class IndexAction extends Action implements IndexActionInterface
{
    use IndexActionTrait;

    /**
     * @return ActiveDataProvider
     * @throws InvalidConfigException
     */
    public function run()
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }

        $searchModel = $this->newSearchModel();

        return $this->prepareDataProvider($searchModel);
    }
}
