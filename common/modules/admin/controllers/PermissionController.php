<?php

namespace common\modules\admin\controllers;

use common\modules\admin\components\AuthItemController;
use yii\rbac\Item;

class PermissionController extends AuthItemController
{
    public $type = Item::TYPE_PERMISSION;
}