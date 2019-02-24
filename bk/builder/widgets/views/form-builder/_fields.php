<?php
/**
 * Created by PhpStorm.
 * User: jomon
 * Date: 10/19/18
 * Time: 11:32 AM
 */

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/* @var $attributes array */
/* @var $widget \entero\builder\widgets\FormBuilder */

$widget = $this->context;
?>

<?= \entero\builder\FormBuilder::widget([
    'model' => $widget->model,
    'form' => $widget->form,
    'attributes' => $attributes,
]) ?>
