<?php

namespace codexten\yii\db;


use yii\base\Model;

/**
 * Class SearchModel
 *
 * @package codexten\yii\db
 */
class SearchModel extends Model implements SearchModelInterface
{
    use SearchModelTrait;
}
