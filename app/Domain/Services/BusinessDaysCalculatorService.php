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
        $this->weekends = explode(',', config("business_days.weekend_days"));
    }

    /**
     * @param DateTime $date
     * @return bool
     */
    public function isBusinessDay(DateTime $date): bool
    {
        if (in_array((int)$date->format('N'), $this->weekends)) {
            $this->weekendDaysCount++;
            return false; //Date is a weekend.
        }

        /** @var Holiday $holiday */
        foreach ($this->holidays as $holiday) {
            foreach ($holiday->getDates() as $holidayDate) {
                $holidayDate = (new DateTime($holidayDate))->format('Y-m-d');
                if ($date->format('Y-m-d') == $holidayDate) {
                    $this->holidayDaysCount++;
                    return false; //Date is a holiday.
                }
            }

        }
        return true;
    }

    /**
     * @param int $delay
     */
    public function addBusinessDays(int $delay)
    {
        $i = 0;
        while ($i < $delay) {
            if ($this->isBusinessDay($this->date)) {
                $i++;
            }
            $this->date->modify("+1 day");
        }
    }

    /**
     * @return DateTime
     */
    public function getBusinessDate(): DateTime
    {
        //because initial day is considered a business day
        $this->date->modify("-1 day");
        return $this->date;
    }

    /**
     * @return int
     */
    public function getHolidayDays(): int
    {
        return $this->holidayDaysCount;
    }

    /**
     * @return int
     */
    public function getWeekendDays(): int
    {
        return $this->weekendDaysCount;
    }


}