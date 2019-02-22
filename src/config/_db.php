<?php
/**
 * Created by PhpStorm.
 * User: jomon
 * Date: 26/9/18
 * Time: 1:03 PM
 */

$db = [
    'class' => 'yii\db\Connection',
    'dsn' => $params['db.dsn'],
    'username' => $params['db.username'],
    'password' => $params['db.password'],
    'tablePrefix' => 'tbl_',
    'charset' => 'utf8',
    'enableSchemaCache' => ($_ENV['ENV'] === 'prod'),
];

if (strstr($params['db.dsn'], 'pgsql')) {
    $db['schemaMap'] = [
        'pgsql' => [
            'class' => 'yii\db\pgsql\Schema',
            'defaultSchema' => $_ENV['DB_NAME'],
        ],
    ];
}


return $db;