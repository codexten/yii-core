<?php
/**
 * @link https://entero.co.in/
 * @copyright Copyright (c) 2012 Entero Software Solutions Pvt.Ltd
 * @license https://entero.co.in/license/
 * @author Jomon Johnson <jomon@entero.in>
 */

use entero\helpers\ArrayHelper;
use entero\helpers\ColorHelper;
use entero\module\user\models\User;
use yii\base\InvalidConfigException;
use yii\base\InvalidParamException;
use yii\db\Transaction;
use yii\di\NotInstantiableException;

/**
 * @return BaseApplication|ConsoleApplication|WebApplication|\yii\console\Application|\yii\web\Application
 */
function app()
{
    return Yii::$app;
}

/**
 * @return mixed|User|\enyii\web\User
 */
function user()
{
    return Yii::$app->user;
}

/**
 * @return int|string
 */
function getMyId()
{
    return Yii::$app->user->getId();
}

/**
 * @param $username string
 *
 * @return bool|mixed id of user
 */
function getIdByUsername($username)
{
    $model = \eii\models\User::find()->select('id')->where(['username' => $username])->asArray()->one();

    return $model ? $model['id'] : false;
}

/**
 * @return bool Whether the current user is a guest.
 */
function isGuest()
{
    return Yii::$app->user->isGuest;
}

/**
 * @param string $view
 * @param array $params
 *
 * @return string
 */
function render($view, $params = [])
{
    return Yii::$app->controller->render($view, $params);
}

/**
 * @param $url
 * @param int $statusCode
 *
 * @return \yii\web\Response
 */
function redirect($url, $statusCode = 302)
{
    return Yii::$app->controller->redirect($url, $statusCode);
}

/**
 * @param $form \yii\widgets\ActiveForm
 * @param $model
 * @param $attribute
 * @param array $inputOptions
 * @param array $fieldOptions
 *
 * @return string
 */
function activeTextinput($form, $model, $attribute, $inputOptions = [], $fieldOptions = [])
{
    return $form->field($model, $attribute, $fieldOptions)->textInput($inputOptions);
}


if (!function_exists('env')) {
    /**
     * Gets the value of an environment variable.
     *
     * @param  string $key
     * @param  mixed $default
     *
     * @return mixed
     */
    /**
     * @param string $key
     * @param mixed $default
     *
     * @return mixed
     */
    function env($key, $default = false)
    {

        $value = getenv($key);

        if ($value === false) {
            return $default;
        }

        switch (strtolower($value)) {
            case 'true':
            case '(true)':
                return true;

            case 'false':
            case '(false)':
                return false;
        }

        return $value;
    }
}


/**
 * Returns the actual URL for the specified asset.
 * The actual URL is obtained by prepending either [[AssetBundle::$baseUrl]] or [[AssetManager::$baseUrl]] to the given asset path.
 *
 * @param \yii\web\AssetBundle $bundle the asset bundle which the asset file belongs to
 * @param string $asset the asset path. This should be one of the assets listed in [[AssetBundle::$js]] or [[AssetBundle::$css]].
 *
 * @return string the actual URL for the specified asset.
 */
function getAssetUrl($bundle, $asset)
{
    return Yii::$app->view->assetManager->getAssetUrl($bundle, $asset);
}

function controllerId()
{
    return Yii::$app->controller->id;
}

function actionId()
{
    return Yii::$app->controller->action->id;
}

/**
 * Checking user have specified roles
 *
 * @param $role
 * @param string $usedId
 *
 * @return bool
 */
function checkRole($role, $usedId = '')
{
    if (empty($usedId) && Yii::$app->user->isGuest) {
        return false;
    }

    $usedId = $usedId ? $usedId : getMyId();
    $auth = Yii::$app->authManager;

    $roles = [];
    if (!is_array($role)) {
        $roles[] = $role;
    } else {
        $roles = $role;
    }

    foreach ($roles as $item) {
        if ($auth->checkAccess($usedId, $item)) {
            return true;
        }
    }

    return false;

}


/**
 * Sets a flash message.
 * A flash message will be automatically deleted after it is accessed in a request and the deletion will happen
 * in the next request.
 * If there is already an existing flash message with the same key, it will be overwritten by the new one.
 *
 * @param string $key the key identifying the flash message. Note that flash messages
 * and normal session variables share the same name space. If you have a normal
 * session variable using the same name, its value will be overwritten by this method.
 * @param mixed $value flash message
 * @param bool $removeAfterAccess whether the flash message should be automatically removed only if
 * it is accessed. If false, the flash message will be automatically removed after the next request,
 * regardless if it is accessed or not. If true (default value), the flash message will remain until after
 * it is accessed.
 *
 * @see getFlash()
 * @see addFlash()
 * @see removeFlash()
 */
function setFlash($key, $value = true, $removeAfterAccess = true)
{
    Yii::$app->session->setFlash($key, $value, $removeAfterAccess);
}

function getHelpLink()
{
    return '#';
}

/**
 * Returns POST parameter with a given name. If name isn't specified, returns an array of all POST parameters.
 *
 * @param string $name the parameter name
 * @param mixed $defaultValue the default parameter value if the parameter does not exist.
 *
 * @return array|mixed
 */
function post($name = null, $defaultValue = null)
{
    return Yii::$app->request->post($name, $defaultValue);
}

/**
 * Returns GET parameter with a given name. If name isn't specified, returns an array of all GET parameters.
 *
 * @param string $name the parameter name
 * @param mixed $defaultValue the default parameter value if the parameter does not exist.
 *
 * @return array|mixed
 */
function get($name = null, $defaultValue = null)
{
    return Yii::$app->request->get($name, $defaultValue);
}

function currentUrl()
{
    return Yii::$app->request->url;
}

/**
 * @return bool $isAjax Whether this is an AJAX (XMLHttpRequest) request. This property is read-only
 */
function isAjax()
{
    return Yii::$app->request->isAjax;
}

/**
 * Retrieves the child module of the specified ID.
 * This method supports retrieving both child modules and grand child modules.
 *
 * @param string $id module ID (case-sensitive). To retrieve grand child modules,
 * use ID path relative to this module (e.g. `admin/content`).
 * @param bool $load whether to load the module if it is not yet loaded.
 *
 * @return \yii\base\Module|null the module instance, `null` if the module does not exist.
 * @see hasModule()
 */
function getModule($id, $load = true)
{
    if (Yii::$app->hasModule($id)) {
        return Yii::$app->getModule($id, $load);
    }
    $id = 'admin/' . $id;
    if (Yii::$app->hasModule($id)) {
        return Yii::$app->getModule($id, $load);
    }

    return false;
}

function getModuleConfig($moduleId, $key, $default = null)
{
    $module = getModule($moduleId);

    return $module ? ArrayHelper::getValue($module, $key, $default) : $default;
}

/**
 * @return string an ID that uniquely identifies this module among other modules which have the same [[module|parent]].
 */
function moduleId()
{
    return Yii::$app->controller->module->id;
}

/**
 * Translates a message to the specified language.
 *
 * This is a shortcut method of [[\yii\i18n\I18N::translate()]].
 *
 * The translation will be conducted according to the message category and the target language will be used.
 *
 * You can add parameters to a translation message that will be substituted with the corresponding value after
 * translation. The format for this is to use curly brackets around the parameter name as you can see in the following example:
 *
 * ```php
 * $username = 'Alexander';
 * echo \Yii::t('app', 'Hello, {username}!', ['username' => $username]);
 * ```
 *
 * Further formatting of message parameters is supported using the [PHP intl extensions](http://www.php.net/manual/en/intro.intl.php)
 * message formatter. See [[\yii\i18n\I18N::translate()]] for more details.
 *
 * @param string $category the message category.
 * @param string $message the message to be translated.
 * @param array $params the parameters that will be used to replace the corresponding placeholders in the message.
 * @param string $language the language code (e.g. `en-US`, `en`). If this is null, the current
 * [[\yii\base\Application::language|application language]] will be used.
 *
 * @return string the translated message.
 */
function t($message, $category = null, $params = [], $language = null)
{
//    $category = $category ?: moduleId();
    if ($category === null) {
        $category = 'common';
    }

    return Yii::t($message, $category, $params, $language);
}

/**
 * @@return string the application name.
 */
function getAppName()
{
    return Yii::$app->name;
}

/**
 * Translates a path alias into an actual path.
 *
 * The translation is done according to the following procedure:
 *
 * 1. If the given alias does not start with '@', it is returned back without change;
 * 2. Otherwise, look for the longest registered alias that matches the beginning part
 *    of the given alias. If it exists, replace the matching part of the given alias with
 *    the corresponding registered path.
 * 3. Throw an exception or return false, depending on the `$throwException` parameter.
 *
 * For example, by default '@yii' is registered as the alias to the Yii framework directory,
 * say '/path/to/yii'. The alias '@yii/web' would then be translated into '/path/to/yii/web'.
 *
 * If you have registered two aliases '@foo' and '@foo/bar'. Then translating '@foo/bar/config'
 * would replace the part '@foo/bar' (instead of '@foo') with the corresponding registered path.
 * This is because the longest alias takes precedence.
 *
 * However, if the alias to be translated is '@foo/barbar/config', then '@foo' will be replaced
 * instead of '@foo/bar', because '/' serves as the boundary character.
 *
 * Note, this method does not check if the returned path exists or not.
 *
 * @param string $alias the alias to be translated.
 * @param bool $throwException whether to throw an exception if the given alias is invalid.
 * If this is false and an invalid alias is given, false will be returned by this method.
 *
 * @return string|bool the path corresponding to the alias, false if the root alias is not previously registered.
 * @throws InvalidParamException if the alias is invalid while $throwException is true.
 * @see setAlias()
 */
function getAlias($alias, $throwException = true)
{
    $path = Yii::getAlias($alias, $throwException);

    return is_link($path) ? realpath($path) : $path;
}

/**
 * Registers a path alias.
 *
 * A path alias is a short name representing a long path (a file path, a URL, etc.)
 * For example, we use '@yii' as the alias of the path to the Yii framework directory.
 *
 * A path alias must start with the character '@' so that it can be easily differentiated
 * from non-alias paths.
 *
 * Note that this method does not check if the given path exists or not. All it does is
 * to associate the alias with the path.
 *
 * Any trailing '/' and '\' characters in the given path will be trimmed.
 *
 * @param string $alias the alias name (e.g. "@yii"). It must start with a '@' character.
 * It may contain the forward slash '/' which serves as boundary character when performing
 * alias translation by [[getAlias()]].
 * @param string $path the path corresponding to the alias. If this is null, the alias will
 * be removed. Trailing '/' and '\' characters will be trimmed. This can be
 *
 * - a directory or a file path (e.g. `/tmp`, `/tmp/main.txt`)
 * - a URL (e.g. `http://www.yiiframework.com`)
 * - a path alias (e.g. `@yii/base`). In this case, the path alias will be converted into the
 *   actual path first by calling [[getAlias()]].
 *
 * @throws InvalidParamException if $path is an invalid alias.
 * @see getAlias()
 */
function setAlias($alias, $path)
{
    return Yii::setAlias($alias, $path);
}

//DB helpers
/**
 * Starts a transaction.
 *
 * @param string|null $isolationLevel The isolation level to use for this transaction.
 * See [[Transaction::begin()]] for details.
 *
 * @return Transaction the transaction initiated
 */
function beginTransaction($isolationLevel = null)
{
    return Yii::$app->db->beginTransaction($isolationLevel);
}


/**
 * Creates an absolute URL using the given route and query parameters.
 *
 * This method prepends the URL created by [[createUrl()]] with the [[hostInfo]].
 *
 * Note that unlike [[\yii\helpers\Url::toRoute()]], this method always treats the given route
 * as an absolute route.
 *
 * @param string|array $params use a string to represent a route (e.g. `site/index`),
 * or an array to represent a route with query parameters (e.g. `['site/index', 'param1' => 'value1']`).
 * @param string|null $scheme the scheme to use for the URL (either `http`, `https` or empty string
 * for protocol-relative URL).
 * If not specified the scheme of the current request will be used.
 *
 * @return string the created URL
 * @see createUrl()
 */
function createAbsoluteUrl($params, $scheme = null)
{
    return Yii::$app->urlManager->createAbsoluteUrl($params, $scheme);
}

/**
 * @param $path
 * @param array $params
 */
function outputImage($path, $params = [])
{
    return Yii::$app->glide->outputImage($path, $params);
}

/**
 * @param array $params
 * @param bool $scheme
 *
 * @param $path
 *
 * @return bool|string
 * @throws InvalidConfigException
 */
function renderImage($path, $params = [], $placeHolder = [])
{

    if (!$path && is_string($placeHolder)) {
        return $placeHolder;
    }

    if (!$path && (ArrayHelper::getValue($params, 'w') || ArrayHelper::getValue($params, 'h'))) {
        $h = ArrayHelper::getValue($params, 'h');
        $w = ArrayHelper::getValue($params, 'w');
        $params = ArrayHelper::merge([
            'w' => $w ?: $h,
            'h' => $h ?: $w,
            'text' => 'no image',
            'fg' => 'ffffff',
            'bg' => ColorHelper::randomHexcode(''),
            'size' => '',
        ], $placeHolder);

        return env('PLACEHOLDER_URL',
                'http://palceholder.domainbrandable.com') . "/{$params['w']}x{$params['h']}/{$params['bg']}/{$params['fg']}?text={$params['text']}";
    }

    $params = ArrayHelper::merge([
        'glide/index',
        'path' => $path,
        'fit' => 'crop-center',
    ], $params);

    return Yii::$app->glide->createSignedUrl($params, true);
}

/**
 * Formats the value as a datetime.
 *
 * @param int|string|DateTime $value the value to be formatted. The following
 * types of value are supported:
 *
 * - an integer representing a UNIX timestamp
 * - a string that can be [parsed to create a DateTime object](http://php.net/manual/en/datetime.formats.php).
 *   The timestamp is assumed to be in [[defaultTimeZone]] unless a time zone is explicitly given.
 * - a PHP [DateTime](http://php.net/manual/en/class.datetime.php) object
 *
 * @param string $format the format used to convert the value into a date string.
 * If null, [[dateFormat]] will be used.
 *
 * This can be "short", "medium", "long", or "full", which represents a preset format of different lengths.
 * It can also be a custom format as specified in the [ICU manual](http://userguide.icu-project.org/formatparse/datetime).
 *
 * Alternatively this can be a string prefixed with `php:` representing a format that can be recognized by the
 * PHP [date()](http://php.net/manual/en/function.date.php)-function.
 *
 * @return string the formatted result.
 * @throws InvalidParamException if the input value can not be evaluated as a date value.
 * @throws InvalidConfigException if the date format is invalid.
 * @see datetimeFormat
 */
function asDatetime($value, $format = null)
{
    return Yii::$app->formatter->asDatetime($value, $format);
}

/**
 * Formats the value as a date.
 *
 * @param int|string|DateTime $value the value to be formatted. The following
 * types of value are supported:
 *
 * - an integer representing a UNIX timestamp
 * - a string that can be [parsed to create a DateTime object](http://php.net/manual/en/datetime.formats.php).
 *   The timestamp is assumed to be in [[defaultTimeZone]] unless a time zone is explicitly given.
 * - a PHP [DateTime](http://php.net/manual/en/class.datetime.php) object
 *
 * @param string $format the format used to convert the value into a date string.
 * If null, [[dateFormat]] will be used.
 *
 * This can be "short", "medium", "long", or "full", which represents a preset format of different lengths.
 * It can also be a custom format as specified in the [ICU manual](http://userguide.icu-project.org/formatparse/datetime).
 *
 * Alternatively this can be a string prefixed with `php:` representing a format that can be recognized by the
 * PHP [date()](http://php.net/manual/en/function.date.php)-function.
 *
 * @return string the formatted result.
 * @throws InvalidParamException if the input value can not be evaluated as a date value.
 * @throws InvalidConfigException if the date format is invalid.
 * @see dateFormat
 */
function asDate($value, $format = null)
{
    return Yii::$app->formatter->asDate($value, $format);
}

function getImageUrl($path)
{
    return getAlias('@storageUrl/source/' . $path);
}


//Array Functions

/**
 * Retrieves the value of an array element or object property with the given key or property name.
 * If the key does not exist in the array or object, the default value will be returned instead.
 *
 * The key may be specified in a dot format to retrieve the value of a sub-array or the property
 * of an embedded object. In particular, if the key is `x.y.z`, then the returned value would
 * be `$array['x']['y']['z']` or `$array->x->y->z` (if `$array` is an object). If `$array['x']`
 * or `$array->x` is neither an array nor an object, the default value will be returned.
 * Note that if the array already has an element `x.y.z`, then its value will be returned
 * instead of going through the sub-arrays. So it is better to be done specifying an array of key names
 * like `['x', 'y', 'z']`.
 *
 * Below are some usage examples,
 *
 * ```php
 * // working with array
 * $username = \yii\helpers\ArrayHelper::getValue($_POST, 'username');
 * // working with object
 * $username = \yii\helpers\ArrayHelper::getValue($user, 'username');
 * // working with anonymous function
 * $fullName = \yii\helpers\ArrayHelper::getValue($user, function ($user, $defaultValue) {
 *     return $user->firstName . ' ' . $user->lastName;
 * });
 * // using dot format to retrieve the property of embedded object
 * $street = \yii\helpers\ArrayHelper::getValue($users, 'address.street');
 * // using an array of keys to retrieve the value
 * $value = \yii\helpers\ArrayHelper::getValue($versions, ['1.0', 'date']);
 * ```
 *
 * @param array|object $array array or object to extract value from
 * @param string|\Closure|array $key key name of the array element, an array of keys or property name of the object,
 * or an anonymous function returning the value. The anonymous function signature should be:
 * `function($array, $defaultValue)`.
 * The possibility to pass an array of keys is available since version 2.0.4.
 * @param mixed $default the default value to be returned if the specified array key does not exist. Not used when
 * getting value from an object.
 *
 * @return mixed the value of the element if found, default value otherwise
 */
function arrayGetValue($array, $key, $default = null)
{
    return ArrayHelper::getValue($array, $key, $default);
}

/**
 * Creates a new object using the given configuration.
 *
 * You may view this method as an enhanced version of the `new` operator.
 * The method supports creating an object based on a class name, a configuration array or
 * an anonymous function.
 *
 * Below are some usage examples:
 *
 * ```php
 * // create an object using a class name
 * $object = Yii::createObject('yii\db\Connection');
 *
 * // create an object using a configuration array
 * $object = Yii::createObject([
 *     'class' => 'yii\db\Connection',
 *     'dsn' => 'mysql:host=127.0.0.1;dbname=demo',
 *     'username' => 'root',
 *     'password' => '',
 *     'charset' => 'utf8',
 * ]);
 *
 * // create an object with two constructor parameters
 * $object = \Yii::createObject('MyClass', [$param1, $param2]);
 * ```
 *
 * Using [[\yii\di\Container|dependency injection container]], this method can also identify
 * dependent objects, instantiate them and inject them into the newly created object.
 *
 * @param string|array|callable $type the object type. This can be specified in one of the following forms:
 *
 * - a string: representing the class name of the object to be created
 * - a configuration array: the array must contain a `class` element which is treated as the object class,
 *   and the rest of the name-value pairs will be used to initialize the corresponding object properties
 * - a PHP callable: either an anonymous function or an array representing a class method (`[$class or $object, $method]`).
 *   The callable should return a new instance of the object being created.
 *
 * @param array $params the constructor parameters
 *
 * @return object the created object
 * @throws InvalidConfigException if the configuration is invalid.
 * @see \yii\di\Container
 */
function createObject($type, array $params = [])
{
    return \Yii::createObject($type, $params);
}

/**
 * Returns an instance of the requested class.
 *
 * You may provide constructor parameters (`$params`) and object configurations (`$config`)
 * that will be used during the creation of the instance.
 *
 * If the class implements [[\yii\base\Configurable]], the `$config` parameter will be passed as the last
 * parameter to the class constructor; Otherwise, the configuration will be applied *after* the object is
 * instantiated.
 *
 * Note that if the class is declared to be singleton by calling [[setSingleton()]],
 * the same instance of the class will be returned each time this method is called.
 * In this case, the constructor parameters and object configurations will be used
 * only if the class is instantiated the first time.
 *
 * @param string $class the class name or an alias name (e.g. `foo`) that was previously registered via [[set()]]
 * or [[setSingleton()]].
 * @param array $params a list of constructor parameter values. The parameters should be provided in the order
 * they appear in the constructor declaration. If you want to skip some parameters, you should index the remaining
 * ones with the integers that represent their positions in the constructor parameter list.
 * @param array $config a list of name-value pairs that will be used to initialize the object properties.
 *
 * @return object an instance of the requested class.
 * @throws InvalidConfigException if the class cannot be recognized or correspond to an invalid definition
 * @throws NotInstantiableException If resolved to an abstract class or an interface (since 2.0.9)
 */
function getClass($class, $params = [], $config = [])
{
    return Yii::$container->get($class, $params, $config);
}

/**
 * @return User
 */
function userIdentity()
{
    return user()->identity;
}

/**
 * Formats the value as a length in human readable form for example `12 meters`.
 * Check properties [[baseUnits]] if you need to change unit of value as the multiplier
 * of the smallest unit and [[systemOfUnits]] to switch between [[UNIT_SYSTEM_METRIC]] or [[UNIT_SYSTEM_IMPERIAL]].
 *
 * @param float|int $value value to be formatted.
 * @param int $decimals the number of digits after the decimal point.
 * @param array $numberOptions optional configuration for the number formatter. This parameter will be merged with [[numberFormatterOptions]].
 * @param array $textOptions optional configuration for the number formatter. This parameter will be merged with [[numberFormatterTextOptions]].
 *
 * @return string the formatted result.
 * @throws InvalidArgumentException if the input value is not numeric or the formatting failed.
 * @throws InvalidConfigException when INTL is not installed or does not contain required information.
 * @see asLength
 * @since 2.0.13
 */
function asLength($value, $decimals = null, $numberOptions = [], $textOptions = [])
{
    return ($value / 1000) . ' km';

    return Yii::$app->formatter->asLength($value, $decimals, $numberOptions, $textOptions);
}