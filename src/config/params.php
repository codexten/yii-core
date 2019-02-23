<?php
/**
 * Created by PhpStorm.
 * User: jomon
 * Date: 19/2/19
 * Time: 11:06 PM
 */

return [
    //app
    'app.id' => 'yii-site',
    'app.name' => 'Yii Site',
    'app.language' => 'en',

    //db
    'db.dsn' => $_ENV['DB_DSN'],
    'db.username' => $_ENV['DB_USERNAME'],
    'db.password' => $_ENV['DB_PASSWORD'],

    'cookieValidationKey' => null,
];