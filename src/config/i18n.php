<?php
/**
 * Created by PhpStorm.
 * User: jomon
 * Date: 6/12/18
 * Time: 10:16 PM
 */

return [
    'class' => \yii\i18n\I18N::class,
    'translations' => [
        'codexten' => [
            'class' => \yii\i18n\PhpMessageSource::class,
            'basePath' => '@codexten/yii/messages',
        ],
        '*' => [
            'class' => yii\i18n\PhpMessageSource::class,
            'basePath' => '@codexten/yii/messages',
//            'fileMap' => [
//                'common' => 'common.php',
//                'backend' => 'backend.php',
//                'frontend' => 'frontend.php',
//            ],
//            'on missingTranslation' => [backend\modules\translation\Module::class, 'missingTranslation'],
        ],
    ],
];