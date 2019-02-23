<?php
/**
 * Created by PhpStorm.
 * User: jomon
 * Date: 17/2/19
 * Time: 1:12 PM
 */

return [
    'id' => $params['app.id'],
    'name' => $params['app.name'],
    'language' => $params['app.language'],
    'basePath' => dirname(__DIR__),
    'vendorPath' => '@root/vendor',
    'bootstrap' => array_filter([
        'log' => 'log',
    ]),
    'viewPath' => '@app/views',
    'layoutPath' => '@app/views/layouts',
    'params' => $params + [
//            'mail.sender' => [
//                $params['mail.sender.email'] => $params['mail.sender.name'],
//            ],
        ],
    'aliases' => $aliases + [
            '@bower' => '@vendor/bower-asset',
            '@npm' => '@vendor/npm-asset',
            '@vendor/bower' => '@vendor/bower-asset',
            '@vendor/npm' => '@vendor/npm-asset',
            '@_runtime' => '@root/runtime',
        ],
    'components' => [
        'db' => require(__DIR__ . '/_db.php'),
    ],
];