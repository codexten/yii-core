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
    'app.env' => @$_ENV['ENV'],

    //db
    'db.dsn' => @$_ENV['DB_DSN'],
    'db.username' => @$_ENV['DB_USERNAME'],
    'db.password' => @$_ENV['DB_PASSWORD'],

    'cookieValidationKey' => null,

//    'mailer' => 'gmail',
//    'mailer.enabled' => YII_ENV === 'prod' ? true : null,
//
//    'mail.sender.email' => 'developer.entero@gmail.com',
//    'mail.sender.name' => 'entero developer',
//
//    'google.apiKey' => isset($_ENV['GOOGLE_API_KEY']) ? $_ENV['GOOGLE_API_KEY'] : '',

];