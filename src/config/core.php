<?php
/**
 * Created by PhpStorm.
 * User: jomon
 * Date: 17/2/19
 * Time: 1:12 PM
 */

use codexten\yii\components\EventManager;
use codexten\yii\db\ActiveRecord;

return [
    'name' => $params['app.name'],
    'language' => $params['app.language'],
    'vendorPath' => '@root/vendor',
    'bootstrap' => array_filter([
        'log' => 'log',
        'eventManager',
    ]),
    'viewPath' => '@app/views',
    'layoutPath' => '@app/views/layouts',
    'params' => $params + [
//            'mail.sender' => [
//                $params['mail.sender.email'] => $params['mail.sender.name'],
//            ],
        ],
    'components' => [
        'db' => require(__DIR__ . '/_db.php'),
        'eventManager' => [
            'class' => EventManager::class,
        ],
//        'place' => [
//            'class' => 'codexten\yii\place\Places',
//            // TODO : temp fix, find good solution
//            'key' => $params['google.apiKey'],
//        ],
//        'cache' => [
//            'class' => \yii\caching\FileCache::class,
//        ],
        'i18n' => $i18n,
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'itemTable' => '{{%rbac_auth_item}}',
            'itemChildTable' => '{{%rbac_auth_item_child}}',
            'assignmentTable' => '{{%rbac_auth_assignment}}',
            'ruleTable' => '{{%rbac_auth_rule}}',
        ],
    ],
    'container' => [
        'definitions' => [
            \yii\db\ActiveRecord::class => [
                'class' => ActiveRecord::class,
            ],
        ],
    ],
];
