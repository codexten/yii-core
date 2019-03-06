<?php
/**
 * Created by PhpStorm.
 * User: jomon
 * Date: 10/19/18
 * Time: 2:52 PM
 */

namespace codexten\yii\db;

use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * Class ActiveRecord
 *
 * @package entero\db
 */
class ActiveRecord extends \yii\db\ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        if ($this->hasProperty('created_by') || $this->hasProperty('updated_by')) {
            $behaviors['blameable'] = ['class' => BlameableBehavior::class,];
            if ($this->hasProperty('created_by')) {
                $behaviors['blameable']['createdByAttribute'] = 'created_by';
            }
            if ($this->hasProperty('updated_by')) {
                $behaviors['blameable']['updatedByAttribute'] = 'updated_by';
            }
        }

        if ($this->hasProperty('created_at') || $this->hasProperty('updated_at')) {
            $behaviors['blameable'] = ['class' => TimestampBehavior::class,];
            if ($this->hasProperty('created_at')) {
                $behaviors['blameable']['createdAtAttribute'] = 'created_at';
            }
            if ($this->hasProperty('updated_at')) {
                $behaviors['blameable']['updatedAtAttribute'] = 'updated_at';
            }
        }

        return $behaviors;
    }


    public function canCreate()
    {
        return true;
    }

    public function canUpdate()
    {
        return true;
    }

    public function canView()
    {
        return true;
    }

    public function canDelete()
    {
        return true;
    }

    public function getMeta()
    {
        return [
            'canCreate' => $this->canCreate(),
            'canUpdate' => $this->canUpdate(),
            'canView' => $this->canView(),
            'canDelete' => $this->canDelete(),
        ];
    }

    public function toArray(array $fields = [], array $expand = [], $recursive = true)
    {
        $data = parent::toArray($fields, $expand, $recursive);
        if ($this->hasMethod('getMeta')) {
            $data['_meta'] = $this->getMeta();
        }

        return $data;
    }

    /**
     * @inheritdoc
     */
    public function delete()
    {
        if (!$this->canDelete()) {
            return false;
        }

        return parent::delete();
    }
}