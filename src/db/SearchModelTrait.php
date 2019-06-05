<?php


namespace codexten\yii\db;


use yii\base\Model;

trait SearchModelTrait
{
    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }
}
