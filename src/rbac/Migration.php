<?php
/**
 * @link https://entero.co.in/
 * @copyright Copyright (c) 2012 Entero Software Solutions Pvt.Ltd
 * @license https://entero.co.in/license/
 */

namespace codexten\yii\rbac;

use Yii;
use yii\base\InvalidConfigException;
use yii\rbac\BaseManager;

/**
 * Class Migration
 *
 * @package codexten\yii\rbac
 * @author Ashlin A <ashlin@entero.in>
 */
class Migration extends \yii\db\Migration
{
    /**
     * @var string|BaseManager
     */
    public $auth = 'authManager';
    
    
    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();
        $this->auth = Yii::$app->get('authManager');
    }
    
    /**
     * This method contains the logic to be executed when applying this migration.
     * Child classes may override this method to provide actual migration logic.
     * @return boolean return a false value to indicate the migration fails
     * and should not proceed further. All other return values mean the migration succeeds.
     */
    public function up()
    {
    }
    
    /**
     * This method contains the logic to be executed when removing this migration.
     * The default implementation throws an exception indicating the migration cannot be removed.
     * Child classes may override this method if the corresponding migrations can be removed.
     * @return boolean return a false value to indicate the migration fails
     * and should not proceed further. All other return values mean the migration succeeds.
     */
    public function down()
    {
    }
}
