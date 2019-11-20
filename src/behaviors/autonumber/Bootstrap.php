<?php

namespace codexten\yii\behaviors\autonumber;

use yii\base\BootstrapInterface;
use yii\validators\Validator;
/**
 * Description of Bootstrap
 *
 */
class Bootstrap implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        Validator::$builtInValidators['nextValue'] = __NAMESPACE__ . '\AutonumberValidator';
        Validator::$builtInValidators['autonumber'] = __NAMESPACE__ . '\AutonumberValidator';
    }
}