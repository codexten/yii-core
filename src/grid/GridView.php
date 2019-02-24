<?php
/**
 * Created by PhpStorm.
 * User: jomon
 * Date: 9/1/19
 * Time: 2:46 PM
 */

namespace codexten\yii\grid;

class GridView extends \yii\grid\GridView
{
    public function init()
    {
        $this->columns = $this->columns ?: $this->columns();
        parent::init();
    }

    /**
     * Returns array of columns configurations that will be used by widget to create
     * data columns and render them.
     *
     * Array format:
     *  key - column alias
     *  value - column configuration array
     *
     * Example:
     *
     * ```php
     * return [
     *     'login_and_avatar' => [
     *         'format' => 'raw',
     *         'value' => function ($model) {
     *             return Html::img($model->avatar) . $model->username;
     *         }
     *     ]
     * ];
     * ```
     *
     * Despite model does not have a `login_and_avatar` attribute, the following widget call will
     * use the definition above to render value:
     *
     * ```php
     * echo GridView::widget([
     *     'dataProvider' => $dataProvider,
     *     'columns' => ['login_and_avatar', 'status', 'actions'],
     * ]);
     * ```
     *
     * @return array
     */
    public function columns()
    {
        return [];
    }
}