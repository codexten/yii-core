<?php

return [
    'id' => $params['app.id'],
    'basePath' => dirname(__DIR__),
    'aliases' => $aliases + [
//    'aliases' => [
            '@bower' => '@vendor/bower-asset',
            '@npm' => '@vendor/npm-asset',
            '@vendor/bower' => '@vendor/bower-asset',
            '@vendor/npm' => '@vendor/npm-asset',
            '@_runtime' => '@root/runtime',
        ],
];