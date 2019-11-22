<?php


namespace codexten\yii\helpers;


use Yii;

class Url extends \yii\helpers\Url
{
    public static function ensureUrl($url)
    {
        if (parse_url($url,PHP_URL_PATH) !== parse_url(Yii::$app->request->absoluteUrl,PHP_URL_PATH)) {
            return Yii::$app->response->redirect($url, 301);
        }
    }

}