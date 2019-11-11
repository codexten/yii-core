<?php


namespace codexten\yii\helpers;


use Yii;

class Url extends \yii\helpers\Url
{
    public static function ensureUrl($url)
    {
        if ($url !== Yii::$app->request->absoluteUrl) {
            return Yii::$app->response->redirect($url, 301);
        }
    }

}