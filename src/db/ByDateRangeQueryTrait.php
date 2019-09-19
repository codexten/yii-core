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
            list($startDate, $endDate) = explode(' - ', $dateRange);
            $startDateTimestamp = strtotime($startDate);
            $endDateTimestamp = strtotime($endDate);
            $this->andFilterWhere([
                'between',
                $field,
                $startDateTimestamp,
                $endDateTimestamp + (60 * 60 * 24),
            ]);
        }

        return $this;
    }
}
