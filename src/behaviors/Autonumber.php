<?php
/**
 * @link https://entero.co.in/
 * @copyright Copyright (c) 2012 Entero Software Solutions Pvt.Ltd
 * @license https://entero.co.in/license/
 */

namespace codexten\yii\behaviors;

use Exception;
use yii\db\BaseActiveRecord;
use yii\db\StaleObjectException;
use eii\models\AutoNumber as AutoNumberModel;

/**
 * Behavior use to generate formated autonumber.
 * Use at ActiveRecord behavior
 *
 * ~~~
 * public function behavior()
 * {
 *     return [
 *         ...
 *         [
 *             'class' => 'enyii\behaviors\Autonumber',
 *             'value' => date('Ymd').'.?', // ? will replace with generated number
 *             'digit' => 6, // specify this if you need leading zero for number
 *         ]
 *     ]
 * }
 * ~~~
 *
 * @author Jomon Johnson <jomon@entero.in>
 */
class Autonumber extends \yii\behaviors\AttributeBehavior
{
    /**
     * @var integer digit number of auto number
     */
    public $digit;
    /**
     * @var mixed Optional.
     */
    public $group;
    /**
     * @var boolean If set `true` number will genarate unique for owner classname.
     * Default `true`.
     */
    public $unique = true;
    /**
     * @var string
     */
    public $attribute;
    /**
     * @var int
     */
    public $startFrom = 1;

    /**
     * @inheritdoc
     */
    public function init()
    {
        if ($this->attribute !== null) {
            $this->attributes[BaseActiveRecord::EVENT_BEFORE_INSERT][] = $this->attribute;
        }
        parent::init();
    }


    /**
     * @inheritdoc
     */
    public function getValue($event)
    {
        if (is_string($this->value) && method_exists($this->owner, $this->value)) {
            $value = call_user_func([$this->owner, $this->value], $event);
        } else {
            $value = is_callable($this->value) ? call_user_func($this->value, $event) : $this->value;
        }

        $number = $this->getNextNumber(true);

        if ($value === null) {
            return $number;
        } else {
            return str_replace('?', $this->digit ? sprintf("%0{$this->digit}d", $number) : $number, $value);
        }
    }

    protected function getNextNumber($save = false)
    {
        $number = 1;

        $group = $this->getGroup();
        do {
            $repeat = false;
            try {
                $model = AutoNumberModel::findOne($group);
                if ($model) {
                    $number = $model->number + 1;
                } else {
                    $model = new AutoNumberModel([
                        'group' => $group,
                    ]);
                    $number = $this->startFrom;
                }
                $model->update_time = time();
                $model->number = $number;
                if ($save) {
                    $model->save(false);
                }
            } catch (Exception $exc) {
                if ($exc instanceof StaleObjectException) {
                    $repeat = true;
                } else {
                    throw $exc;
                }
            }
        } while ($repeat);

        return $number;
    }

    /**
     * Return encrypted string for group column
     * @return string
     */
    protected function getGroup()
    {
        return md5(serialize([
            'class' => get_class($this->owner),
            'group' => $this->group,
            'attribute' => $this->attribute,
        ]));
    }
}