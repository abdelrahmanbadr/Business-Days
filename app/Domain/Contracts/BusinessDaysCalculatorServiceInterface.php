<?php

namespace App\Domain\Contracts;

use DateTime;

interface BusinessDaysCalculatorServiceInterface
{
    public function isBusinessDay(): bool;

    public function addBusinessDays(int $delay);

    public function getBusinessDate(): DateTime;

    public function getHolidayDays(): int;

    public function getWeekendDays(): int;
}