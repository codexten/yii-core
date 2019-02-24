<?php
/**
 * Created by PhpStorm.
 * User: jomon
 * Date: 9/1/19
 * Time: 8:59 PM
 */

namespace codexten\yii\builder\widgets;

use codexten\yii\web\Widget;
use kartik\builder\Form;
use kartik\form\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;

class FormBuilder extends Widget
{
    public $model;
    /**
     * @var ActiveForm
     */
    public $form;
    public $formConfig;
    public $formBuilderClass = \codexten\yii\builder\FormBuilder::class;

    // layout
    const LAYOUT_DEFAULT = 'default';
    const LAYOUT_HORIZONTAL = 'horizontal';

    public $method = 'post';
    public $options = [
        'class' => 'm-form',
    ];
    public $cols = 1;
    public $fieldClasses = [
        'text' => 'codexten\yii\vue\widgets\fields\Text',
        'dropdownList' => 'codexten\yii\vue\widgets\fields\Text',
//        'email' => 'codexten\yii\vue\widgets\fields\Email',
//        'password' => 'codexten\yii\vue\widgets\fields\Password',
//        'select' => 'codexten\yii\vue\widgets\fields\Select',
//        'tel' => 'codexten\yii\vue\widgets\fields\Tel',
    ];
    public $commonFieldTypes = [
        'email' => 'email',
        'password' => 'password',
        'confirm_password' => 'password',
    ];
    public $fields = [];
    public $optionsUrl = ['options'];
    public $redirectUrl;

    private $_config = [];

    public function init()
    {
        parent::init();
        $this->form = ActiveForm::begin();
    }

    /**
     * {@inheritdoc}
     */
    public function getViewPath()
    {
        return '@entero/builder/widgets/views';
    }

    public function fields(array $options)
    {
        $options = ArrayHelper::merge([
            'columns' => $this->cols,
        ], $options);

        $options['attributes'] = $this->normalizeFields($options['fields']);
        ArrayHelper::remove($options, 'fields');
        ArrayHelper::remove($options, 'cols');

//        echo '<pre>';
//        var_dump($options);
//        echo '</pre>';
//        exit;

        return $this->render('_fields', $options);
    }

    public function field($field)
    {
        $field['config']['fieldConfig'] = ArrayHelper::getValue($field, 'fieldConfig', []);

        return $this->render('_field', ['field' => $field]);
    }

    /**
     * @param array $options
     *  - title : string
     *  - fields : array
     *
     * @return string
     * @throws \ReflectionException
     */
    public function section(array $options = [])
    {
        $options = ArrayHelper::merge([
            'cols' => $this->cols,
        ], $options);

        $options['fields'] = $this->normalizeFields($options['fields']);

        return $this->render('_section', $options);
    }

    public function actions()
    {
        return $this->render('_actions');
    }

    protected function normalizeFields($fields)
    {
        $items = [];
        foreach ($fields as $key => $field) {
            if (is_string($field)) {
                $field = ['attribute' => $field];
            }
            if (!isset($field['attribute'])) {
                $field['attribute'] = $key;
            }
            $items[$field['attribute']] = $this->normalizeField($field);
        }

        return $items;
    }

    protected function normalizeField($field)
    {
        if (is_string($field)) {
            $attribute = $field;
            unset($field);
            $field = [
                'attribute' => $attribute,
            ];
        }

        $field = $this->normalizeAttributeName($field);

        $field['type'] = $this->getFieldType($field);
//        $field['class'] = $this->getFieldClass($field);
//        $config = ArrayHelper::getValue($field, 'config', []);
//        $field['config'] = ArrayHelper::merge([
//            'attribute' => $field['attribute'],
//        ], $config);
//
        $type = ArrayHelper::getValue($field, 'type');
        $normalizeMethod = 'normalize' . Inflector::camelize($type) . 'field';
        if ($this->hasMethod($normalizeMethod)) {
            $field = $this->{$normalizeMethod}($field);
        }

        return $field;
    }

    protected function normalizeAttributeName($field)
    {
        $attributeItems = explode('|', $field['attribute']);
        $field['attribute'] = $attributeItems[0];
        foreach ($attributeItems as $attributeItem) {
            if ($attributeItem == 'row') {
                $field['row'] = true;
                continue;
            }
            if (strpos($attributeItem, ':')) {
                $attributeItem = explode(':', $attributeItem);
                $field[$attributeItem[0]] = $attributeItem[1];
                continue;
            }
        }

        return $field;
    }

    protected function normalizeDropdownListField($field)
    {
        if (isset($field['options'])) {
            $field['items'] = $field['options'];
            ArrayHelper::remove($field, 'options');
        }


        return $field;
    }

//    protected function normalizeDropdownListField($field)
//    {
////        $field['fieldConfig'] = [
////            'options' => [
////                'id' => '',
////                'name' => '',
////            ],
////            'url' => '',
////        ];
//        if (isset($field['options'])) {
//            $options = [];
//            foreach ($field['options'] as $id => $item) {
//                if (is_string($item)) {
//                    $option = ['id' => $id, 'text' => $item];
//                } else {
//                    $option['id'] = ArrayHelper::getValue($item, 'id', $id);
//                    $option['text'] = ArrayHelper::getValue($item, 'text', null);
//                    if ($option['text'] == null) {
//                        $option['text'] = ArrayHelper::getValue($item, 'name', null);
//                    } else {
//                        $option = $item;
//                    }
//                }
//
//                $options[$id] = $option;
//            }
//            $field['items'] = $options;
//            unset($field['options']);
//        }
//
//        if (isset($field['url'])) {
//            $url = $field['url'];
//            ArrayHelper::ensure($url);
//            $url['_widget'] = 'select2';
//            $field['fieldConfig']['url'] = Url::to($url);
//            unset($field['url']);
//        }
//
//        return $field;
//    }

    protected function normalizePasswordField($field)
    {

        return $field;
    }

    protected function getAttribute($field)
    {
        $attribute = ArrayHelper::getValue($field, 'attribute');
        if (strpos($attribute, '.')) {
            $attribute = explode('.', $attribute);
            $attribute = $attribute[1];
        }

        return $attribute;
    }

    protected function getFieldType($field)
    {
        $type = ArrayHelper::getValue($field, 'type');

        if (!$type) {
            if (isset($field['url']) || isset($field['options'])) {
                return Form::INPUT_DROPDOWN_LIST;
            }
            $type = ArrayHelper::getValue($this->commonFieldTypes, $this->getAttribute($field));
        }


        if ($type) {
            return $type;
        }

        if (strpos($this->getAttribute($field), 'password')) {
            return 'password';
        }

        return 'textInput';
    }

    protected function getFieldClass($field)
    {
        $class = ArrayHelper::getValue($field, 'class');

        if (!$class) {
            $class = $this->fieldClasses[$field['type']];
            if (empty($class)) {
                throw new InvalidConfigException("'{$field['attribute']}' not specified class or invalid type '{$field['type']}' ");
            }
        }

        if (!class_exists($class)) {
            throw new InvalidConfigException("Class not exist '{$class}'");
        }

        return $class;
    }

//    public function run()
//    {
//        $out = $this->render($this->layout);
//        $this->defineJsVariable('fieldConfig', $this->_config);
//
//        return $out;
//    }
}