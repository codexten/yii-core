<?php
/**
 * Created by PhpStorm.
 * User: jomon
 * Date: 8/11/18
 * Time: 2:49 PM
 */

return [
    'bootstrap' => array_filter([
        'eventManager',
    ]),
    'components' => [
        'eventManager' => [
            'class' => \entero\components\EventManager::class,
        ],
        'place' => [
            'class' => 'entero\place\Places',
            // TODO : temp fix, find good solution
            'key' => $params['google.apiKey'],
        ],
        'cache' => [
            'class' => \yii\caching\FileCache::class,
        ],
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
                'class' => \entero\db\ActiveRecord::class,
            ],
        ],
    ],
];