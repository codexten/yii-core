<?php

namespace codexten\yii\test;

class ActiveFixture extends \yii\test\ActiveFixture
{

    /**
     * {@inheritdoc}
     */
    protected function getData()
    {
        if ($this->dataFile === null) {
            if ($this->dataDirectory !== null) {
                $dataFile = $this->getTableSchema()->fullName . '.php';
            } else {
                $class = new \ReflectionClass($this);
                $tableName = str_replace(\Yii::$app->db->tablePrefix, '', $this->getTableSchema()->fullName);
                $this->dataFile = dirname($class->getFileName()) . '/data/' . $tableName . '.php';
            }
        }

        return parent::getData();
    }

}