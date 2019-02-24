<?php

namespace entero\components;

use yii\base\Component;
use yii\db\ActiveRecord;
use entero\helpers\ArrayHelper;
use yii\helpers\Inflector;
use yii\helpers\Json;
use yii\helpers\StringHelper;
use entero\web\widgets\settings\Form;

class Settings extends Component
{
    /**
     * @var ActiveRecord
     */
    public $modelClass = 'enyii\common\models\Setting';
    public $folder;
    public $condition = [];

    public $configFile;
    public $config;
    private $fields;

    protected $tabs = [];

    public function init()
    {
        parent::init();
        $this->setConfigFile($this->configFile);
        $this->setConfig();
        $this->setFields();
    }

    protected function setConfig()
    {
        $this->config = require($this->configFile);
    }

    protected function setConfigFile($configFile = false)
    {
        $this->configFile = $configFile;
    }


    protected function setFields()
    {
        $this->fields = ArrayHelper::recursiveFind($this->config, 'fields');
    }

    /**
     * @return mixed
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @param $key
     * @param bool $default
     *
     * @return bool|mixed|null
     */
    public function get($key, $default = false)
    {
        $field = ArrayHelper::getRow($this->fields, $key, 'key');
        if (!$field) {
            return false;
        }

        $condition = $this->condition;

        if (($value = $this->getFromDb($key, $condition)) !== null) {
            if (ArrayHelper::getValue($field, 'inputType') == Form::INPUT_TYPE_JS_BLOCK && is_array($value)) {
                $value = Json::encode($value);
            }

            return $value;
        }

        if ($default) {
            return $default;
        }

        return $this->getFromFile($key);
    }

    protected function getFromDb($key, $condition)
    {
        $query = call_user_func("{$this->modelClass}::find");
        $query->andWhere(['key' => $key]);

        if (!empty($condition)) {
            $query->andWhere($condition);
        }

        $model = $query->one();

        return $model ? $model->value : null;
    }

    protected function getFromFile($key)
    {
        $field = ArrayHelper::getRow($this->fields, $key, 'key');

        if (!$field) {
            return false;
        }

        $value = ArrayHelper::getValue($field, 'default');

        if (!$value) {

            $file = $this->folder . DIRECTORY_SEPARATOR . $key . '.php';

            if (file_exists($file)) {
                $value = require($file);
            }
        }

        return $value;
    }
}