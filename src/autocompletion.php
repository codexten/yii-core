<?php
/**
 * Created by PhpStorm.
 * User: jomon
 * Date: 22/11/18
 * Time: 11:59 AM
 */

use codexten\yii\place\Client\PlaceClient;
use yii\BaseYii;

/**
 * Yii bootstrap file.
 * Used for enhanced IDE code autocompletion.
 * Note: To avoid "Multiple Implementations" PHPStorm warning and make autocomplete faster
 * exclude or "Mark as Plain Text" vendor/yiisoft/yii2/Yii.php file
 */
class Yii extends BaseYii
{
    /**
     * @var BaseApplication|WebApplication|ConsoleApplication the application instance
     */
    public static $app;
}

/**
 * Class BaseApplication
 * Used for properties that are identical for both WebApplication and ConsoleApplication
 *
 * @property PlaceClient $place
 */
abstract class BaseApplication extends yii\base\Application
{
}

///**
// * Class WebApplication
// * Include only Web application related components here
// *
// * @property User $user User component.
// */
//class WebApplication extends yii\web\Application
//{
//}
//
///**
// * Class ConsoleApplication
// * Include only Console application related components here
// */
//class ConsoleApplication extends yii\console\Application
//{
//}
//
///**
// * User component
// * Include only Web application related components here
// *
// * @property \common\models\User $identity User model.
// * @method \common\models\User getIdentity() returns User model.
// */
//class User extends \yii\web\User
//{
//}
