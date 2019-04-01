<?php
if (!defined('ROOT_DIR')) {
    fwrite(STDERR, 'WEBAPP_ROOT_DIR constant must be defined');
    exit(1);
}

if (!defined('VENDOR_DIR')) {
    foreach ([ROOT_DIR . '/vendor'] as $dir) {
        if (file_exists($dir . '/autoload.php')) {
            define('VENDOR_DIR', $dir);
            break;
        }
    }
}

if (!defined('VENDOR_DIR') || !file_exists(VENDOR_DIR . '/autoload.php')) {
    fwrite(STDERR, "Run composer to set up dependencies!\n");
    exit(1);
}

require_once VENDOR_DIR . '/autoload.php';

require_once hiqdev\composer\config\Builder::path('defines');
require_once VENDOR_DIR . '/yiisoft/yii2/Yii.php';

Yii::setAlias('@root', ROOT_DIR);
Yii::setAlias('@vendor', VENDOR_DIR);

require_once __DIR__ . '/helpers.php';
