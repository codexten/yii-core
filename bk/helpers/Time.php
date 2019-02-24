<?php
/**
 * @link https://entero.co.in/
 * @copyright Copyright (c) 2012 Entero Software Solutions Pvt.Ltd
 * @license https://entero.co.in/license/
 */

namespace entero\helpers;

/**
 * Class Time
 *
 * @package entero\helpers
 * @author Ashlin A <ashlin@entero.in>
 * @author Jomon Johnson <jomon@entero.in>
 */
class Time
{
    /**
     * @var integer
     */
    const SECONDS_IN_A_MINUTE = 60;
    /**
     * @var integer
     */
    const SECONDS_IN_AN_HOUR = 3600;
    /**
     * @var integer
     */
    const SECONDS_IN_A_DAY = 86400;
    /**
     * @var integer
     */
    const SECONDS_IN_A_WEEK = 604800;
    /**
     * @var integer
     */
    const SECONDS_IN_A_MONTH = 2592000;
    /**
     * @var integer
     */
    const SECONDS_IN_A_YEAR = 31536000;

    /**
     * @param $days
     * @param bool $timestamp
     * @param bool $format
     *
     * @return false|string
     */
    public static function plusDate($days, $timestamp = false, $format = false)
    {
        $timestamp = $timestamp ?: time();
        $format = $format ?: 'Y-m-d';

        $date = strtotime("+" . $days . " days", $timestamp);

        return date($format, $date);
    }

    /**
     * @param $days
     * @param bool $timestamp
     * @param bool $format
     *
     * @return false|string
     */
    public static function minusDate($days, $timestamp = false, $format = false)
    {
        $timestamp = $timestamp ?: time();
        $format = $format ?: 'Y-m-d';

        $date = strtotime("-" . $days . " days", $timestamp);

        return date($format, $date);
    }

    /**
     * get age from date of birth
     *
     * @param $dob
     *
     * @return integer
     */
    public static function getAge($dob)
    {
        return (date('Y') - date('Y', strtotime($dob)));
    }

    /**
     * find difference between two days
     *
     * @param bool $fromDate
     * @param bool $toDate
     * @param string $differenceFormat
     *
     * @return string
     */
    public static function dateDifference($fromDate = false, $toDate = false, $differenceFormat = '%a')
    {
        $fromDateTime = $fromDate ? date_create($fromDate) : date_create('NOW');
        $toDateTime = $toDate ? date_create($toDate) : date_create('NOW');
        $interval = date_diff($fromDateTime, $toDateTime);

        return $interval->format($differenceFormat);

    }
}