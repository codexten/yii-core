<?php

namespace codexten\yii\rbac\helpers;

use Yii;

class RbacHelper
{
    public static function checkRole($role, $usedId = '')
    {
        if (empty($usedId) && Yii::$app->user->isGuest) {
            return false;
        }

        $usedId = $usedId ? $usedId : getMyId();
        $auth = Yii::$app->authManager;

        $roles = [];
        if (!is_array($role)) {
            $roles[] = $role;
        } else {
            $roles = $role;
        }

        foreach ($roles as $item) {
            if ($auth->checkAccess($usedId, $item)) {
                return true;
            }
        }

        return false;
    }
}
