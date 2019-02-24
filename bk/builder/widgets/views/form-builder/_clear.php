<?php
/**
 * Created by PhpStorm.
 * User: jomon
 * Date: 10/19/18
 * Time: 12:08 PM
 */

use kartik\form\ActiveForm;

/* @var $widget \entero\builder\widgets\FormBuilder */
/* @var $content string */

$widget = $this->context;
?>

<?= $content ?>

<?php ActiveForm::end(); ?>