<?php
namespace codexten\yii\base;

use yii\base\InvalidConfigException;
use yii\base\Controller;
use codexten\yii\helpers\ArrayHelper;

/**
 * Trait ModuleTrait
 *
 * @package codexten\yii\base
 * @author Jomon Johnson <jomon@entero.in>
 */
trait ModuleTrait
{
    /**
     * @var array
     */
    public $controllerNamespaces;

    /**
     * Creates a controller based on the given controller ID.
     *
     * The controller ID is relative to this module. The controller class
     * should be namespaced under [[controllerNamespace]].
     *
     * Note that this method does not check [[modules]] or [[controllerMap]].
     *
     * @param string $id the controller ID.
     *
     * @return Controller|null the newly created controller instance, or `null` if the controller ID is invalid.
     * @throws InvalidConfigException if the controller class and its file name do not match.
     * This exception is only thrown when in debug mode.
     * TODO: its just a temporary fix
     */
    public function createControllerByID($id)
    {
        ArrayHelper::ensure($this->controllerNamespaces);
        array_push($this->controllerNamespaces, $this->controllerNamespace);
        foreach ($this->controllerNamespaces as $controllerNamespace) {
            $this->controllerNamespace = $controllerNamespace;
            $controllerId = parent::createControllerByID($id);
            if ($controllerId !== null) {
                return $controllerId;
            }
        }

        return null;
    }
}