<?php
/**
 * Created by PhpStorm.
 * User: jomon
 * Date: 10/18/18
 * Time: 3:33 PM
 */

use codexten\yii\builder\widgets\FormBuilder;
use codexten\yii\metronic\widgets\Portlet;

/* @var $widget FormBuilder */

$widget = $this->context;
?>
<?php $this->beginContent($widget->getViewPath().'/form-builder/_clear.php'); ?>

<?php $portlet = Portlet::begin([
    'title' => $this->title,
]) ?>

<?php $portlet->beginContent('body') ?>

<?= $widget->renderContent('body') ?>

<?php $portlet->endContent() ?>

<?php $portlet->beginContent('actions') ?>

<?= $widget->actions() ?>

<?php $portlet->endContent() ?>

<?php $portlet->end() ?>

<?php $this->endContent() ?>
