<?php

namespace App\Domain\Services;

use App\Domain\Models\Holiday;
use App\Constants\WeekDays;
use DateTime;


/**
 * Class BusinessDaysCalculatorService
 * @package App\Domain\Services
 *
 * This class will be responsible for calculating amount of business days
 * past the date after which the settlement will reach the bank account
 *
 */
class BusinessDaysCalculatorService
{
    /**
     * @var DateTime
     */
    private $date;
    /**
     * @var array|Holiday[]
     */
    private $holidays;
    /**
     * @var array
     */
    private $weekends;
    /**
     * @var int
     */
    private $weekendDaysCount;
    /**
     * @var int
     */
    private $holidayDaysCount;


    /**
     * @param DateTime $startDate Date to start calculations from
     * @param Holiday[] $holidays Array of holidays
     */
    public function __construct(DateTime $startDate, array $holidays)
    {
        $this->holidayDaysCount = $this->weekendDaysCount = 0;
        $this->date = $startDate;
        $this->holidays = $holidays;
        $this->weekends = WeekDays::WEEKENDS;
    }

    /**
     * @param DateTime $date
     * @return bool
     */
    public function isBusinessDay(DateTime $date): bool
    {
        return true;
    }

}