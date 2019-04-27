<?php
/**
 * Created by PhpStorm.
 * User: abdelrahmanbadr
 * Date: 4/27/19
 * Time: 3:43 AM
 */

namespace Tests\Domain\Transformers;


use App\Domain\Transformers\BusinessDaysResponseTransformer;
use DateTime;
use Tests\TestCase;

class BusinessDaysResponseTransformerTest extends TestCase
{

    /** @test * */
    public function testTransform(): void
    {
        $businessTransformer = new BusinessDaysResponseTransformer();
        $businessDate = "2018-12-14T10:10:10Z";
        $weekendDays = 2;
        $holidayDays = 2;
        $delay = 2;
        $actual = $businessTransformer->transform((new DateTime($businessDate)), $weekendDays, $holidayDays, $delay);
        $expected = [
            "businessDate" => $businessDate,
            "totalDays" => $weekendDays + $holidayDays + $delay,
            "holidayDays" => $holidayDays,
            "weekendDays" => $weekendDays,
        ];
        $this->assertEquals($actual, $expected);
    }

}