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
/* @var $widget \codexten\yii\builder\widgets\FormBuilder */

$widget = $this->context;
?>

<?= \codexten\yii\builder\FormBuilder::widget([
    'model' => $widget->model,
    'form' => $widget->form,
    'attributes' => $attributes,
]) ?>
