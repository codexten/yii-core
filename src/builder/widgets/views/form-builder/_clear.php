<?php
/**
 * Created by PhpStorm.
 * User: jomon
 * Date: 10/19/18
 * Time: 12:08 PM
 */

use codexten\yii\builder\widgets\FormBuilder;
use kartik\form\ActiveForm;

/* @var $widget FormBuilder */
/* @var $content string */

$widget = $this->context;
?>

<?= $content ?>

<?php ActiveForm::end(); ?>