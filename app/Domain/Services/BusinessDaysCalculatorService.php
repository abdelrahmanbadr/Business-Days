<?php

namespace App\Domain\Services;

use App\Domain\Contracts\BusinessDaysCalculatorServiceInterface;
use App\Domain\Models\Holiday;
use DateTime;


/**
 * Class BusinessDaysCalculatorService
 * @package App\Domain\Services
 *
 * This class will be responsible for calculating amount of business days
 * past the date after which the settlement will reach the bank account
 *
 */
class BusinessDaysCalculatorService implements BusinessDaysCalculatorServiceInterface
{
    /**
     * @var DateTime
     */
    private $initialDate;
    /**
     * @var array|Holiday[]
     */
    private $holidaysArray;
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
     * @param DateTime $initialDate Date to start calculations from
     * @param Holiday[] $holidaysArray Array of holidays
     */
    public function __construct(DateTime $initialDate, array $holidaysArray)
    {
        $this->holidayDaysCount = $this->weekendDaysCount = 0;
        $this->initialDate = $initialDate;
        $this->holidaysArray = $holidaysArray;
        $this->weekends = explode(',', config("business_days.weekend_days"));
    }

    /**
     * @return bool
     */
    public function isBusinessDay(): bool
    {
        return !$this->isHoliday() && !$this->isWeekendDay();
    }

    public function isWeekendDay(): bool
    {
        $isWeekend = in_array($this->initialDate->format('N'), $this->weekends);
        if ($isWeekend) {
            $this->weekendDaysCount++;
            return true;
        }

        return false;
    }

    public function isHoliday(): bool
    {
        /** @var  $holiday Holiday */
        foreach ($this->holidaysArray as $holiday) {
            foreach ($holiday->getDates() as $date) {
                if ($date == $this->initialDate->format("Y-m-d")) {
                    $this->holidayDaysCount++;
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * @param int $delay
     */
    public function addBusinessDays(int $delay)
    {
        $i = 0;
        while ($i < $delay) {
            if ($this->isBusinessDay()) {
                $i++;
            }
            $this->initialDate->modify("+1 day");
        }
    }

    /**
     * @return DateTime
     */
    public function getBusinessDate(): DateTime
    {
        //because initial day is considered a business day
        $this->initialDate->modify("-1 day");
        return $this->initialDate;
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