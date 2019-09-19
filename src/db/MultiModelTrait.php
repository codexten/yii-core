<?php
/**
 * Created by PhpStorm.
 * User: jomon
 * Date: 20/12/18
 * Time: 8:40 PM
 */

namespace codexten\yii\db;

use Exception;
use Throwable;
use Yii;
use yii\base\Model;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;
use yii\base\Component;
use yii\db\ActiveRecord;


trait MultiModelTrait
{
    abstract protected function models();

    /**
     * {@inheritdoc}
     */
    public function save($runValidation = true, $attributeNames = null)
    {
        $models = $this->processModels();

        $modelsGroups['before'] = ArrayHelper::getValue($models, 'before', []);
        $modelsGroups['primary'] = ArrayHelper::getValue($models, 'primary', []);
        $modelsGroups['after'] = ArrayHelper::getValue($models, 'after', []);

        $data = [];
        $isSaved = true;
        $transaction = Yii::$app->db->beginTransaction();
        try {
            foreach ($modelsGroups as $type => $models) {
                foreach ($models as $name => $relation) {
                    /* @var $model ActiveRecord */
                    $modelClass = $relation['modelClass'];
                    $links = ArrayHelper::getValue($relation, 'links', []);

                    $model = new $modelClass();
                    $model->load(post($name), '');
                    foreach ($links as $attribute => $value) {
                        $model->{$attribute} = $data[$value];
                    }
                    if ($model->save()) {
                        foreach ($model->attributes as $attribute => $value) {
                            $data[$name . '.' . $attribute] = $value;
                        }
                    } else {
                        $isSaved = false;
                        foreach ($model->errors as $attribute => $error) {
                            $this->addError($name . '.' . $attribute, $error);
                        }
                    }
                    if ($type == 'primary') {
                        $this->primaryModel = $model;
                    }
                }
            }
        } catch (Exception $e) {
            $transaction->rollBack();
            throw $e;
        } catch (Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
        if ($isSaved) {
            $transaction->commit();
        }

        return $isSaved;
    }

    protected function processModels()
    {
        $models = $this->models();

        reset($models);
        $name = key($models);
        /* @var $model ActiveRecord */
        $model = Yii::createObject([
            'class' => $models[$name]['modelClass'],
        ]);
        $processedModels['primary'][$name] = $models[$name];
        unset($models[$name]);

        foreach ($models as $name => $value) {
            /* @var $relation ActiveQuery */
            $relation = $model->getRelation($name);
            $link = $relation->link;
            $linkTarget = key($link);
            $linkAttribute = $link[$linkTarget];
            $position = $linkAttribute == $model::primaryKey() ? 'after' : 'before';
            $processedModels[$position][$name] = [
                'modelClass' => $value['modelClass'],
                'multiple' => $relation->multiple,
//                'link' => $link,
                'attributes' => ArrayHelper::getValue($value, 'attributes', []),
            ];
        }

        return $processedModels;
    }

    /**
     * {@inheritdoc}
     */
    public function attributes()
    {
        $attributes = [];
        foreach ($this->models() as $name => $relation) {
            $model = new $relation['modelClass']();
            foreach ($model->attributes() as $attribute) {
                $attributes[] = $name . '.' . $attribute;
            }
        }

        return $attributes;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        $attributeLabels = [];
        foreach ($this->models() as $name => $relation) {
            $model = Yii::createObject([
                'class' => $relation['modelClass'],
            ]);
            foreach ($model->attributeLabels() as $attribute => $label) {
                $attributeLabels[$name . '.' . $attribute] = $label;
            }
        }

        return $attributeLabels;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeHints()
    {
        $attributeLabels = [];
        foreach ($this->models() as $name => $relation) {
            /* @var $model Model */
            $model = Yii::createObject([
                'class' => $relation['modelClass'],
            ]);
            foreach ($model->attributeHints() as $attribute => $hint) {
                $attributeLabels[$name . '.' . $attribute] = $hint;
            }
        }

        return $attributeLabels;
    }
}