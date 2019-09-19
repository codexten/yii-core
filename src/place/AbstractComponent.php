<?php
/**
 * @link https://entero.co.in/
 * @copyright Copyright (c) 2012 Entero Software Solutions Pvt.Ltd
 * @license https://entero.co.in/license/
 */

namespace codexten\yii\place;

use codexten\yii\helpers\ArrayHelper;
use codexten\yii\place\Client\AbstractClient;
use yii\base\Component;
use yii\base\InvalidConfigException;

/**
 * Class AbstractComponent
 *
 * @package enyii\place\place
 * @author Jomon Johnson <jomon@entero.in>
 */
abstract class AbstractComponent extends Component
{
    /**
     * @var string response format. Can be json or xml.
     */
    public $format = 'json';
    /**
     * @var string your API key
     */
    public $key;
    /**
     * @var AbstractClient
     */
    protected $client;

    /**
     * Wraps PlaceClient methods.
     *
     * @param string $name
     * @param array $params
     *
     * @return mixed
     */
    public function __call($name, $params)
    {
        if (method_exists($this->getClient(), $name)) {
            return call_user_func_array([$this->getClient(), $name], $params);
        }

        return parent::__call($name, $params);
    }

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        if (empty($this->key) || empty($this->format)) {
            throw new InvalidConfigException('"key" and/or "format" cannot be empty.');
        }

        if (strpos($this->key,',')) {
            $key = explode(',', $this->key);
            $this->key = ArrayHelper::randomValue($key);
        }

        parent::init();
    }

    /**
     * @return AbstractClient|PlaceClient|SearchClient
     */
    abstract public function getClient();
}
