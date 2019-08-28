<?php


namespace codexten\yii\db;


trait ByDateRangeQueryTrait
{

    /**
     * @param $field
     * @param $dateRange
     *
     * @return $this
     */
    public function byDateRange($field, $dateRange)
    {
        return $this->byTimestampRange("UNIX_TIMESTAMP($field)", $dateRange);
    }

    /**
     * @param $field
     * @param $dateRange
     *
     * @return $this
     */
    public function byTimestampRange($field, $dateRange)
    {
        if (!empty($dateRange) && strpos($dateRange, '-') !== false) {
            list($start_date, $end_date) = explode(' - ', $dateRange);

            $this->andFilterWhere([
                'between',
                $field,
                strtotime($start_date),
                strtotime($end_date),
            ]);
        }

        return $this;
    }
}
