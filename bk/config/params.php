<?php
/**
 * Created by PhpStorm.
 * User: jomon
 * Date: 5/10/18
 * Time: 12:13 PM
 */

return [
    'mailer' => 'gmail',
    'mailer.enabled' => YII_ENV === 'prod' ? true : null,

    'mail.sender.email' => 'developer.entero@gmail.com',
    'mail.sender.name' => 'entero developer',

    'google.apiKey' => isset($_ENV['GOOGLE_API_KEY']) ? $_ENV['GOOGLE_API_KEY'] : '',
];