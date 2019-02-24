<?php
/**
 * @link https://entero.co.in/
 * @copyright Copyright (c) 2012 Entero Software Solutions Pvt.Ltd
 * @license https://entero.co.in/license/
 */

namespace entero\behaviors;

use Yii;

/**
 * Class UploadBehavior
 *
 * @package entero\behaviors
 * @author Jomon Johnson <jomon@entero.in>
 */
class UploadBehavior extends \trntv\filekit\behaviors\UploadBehavior
{
    public $baseUrlAttribute = false;

}