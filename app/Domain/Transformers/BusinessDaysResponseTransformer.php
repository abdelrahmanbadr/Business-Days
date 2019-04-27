<?php

namespace App\Domain\Transformers;

use DateTime;

/**
 * Class BusinessDaysResponseTransformer
 * @package App\Domain\Transformers
 *
 * This class will be responsible for transforming the response
 * to match the supplied one by product team
 *
 */
class BusinessDaysResponseTransformer
{
    public function transform(DateTime $businessDate, int $weekendDays, int $holidayDays, int $delay): array
    {
        return [
            "businessDate" => $businessDate->format('Y-m-d\TH:i:s\Z'),
            "totalDays" => $weekendDays + $holidayDays + $delay,
            "holidayDays" => $holidayDays,
            "weekendDays" => $weekendDays,
        ];
    }

}