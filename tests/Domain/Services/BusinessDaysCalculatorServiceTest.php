<?php


namespace Tests\Domain\Services;

use App\Domain\Services\BusinessDaysCalculatorService;
use Tests\TestCase;
use App\Domain\Models\Holiday;
use DateTime;

class BusinessDaysCalculatorServiceTest extends TestCase
{
    /**
     * @var array/[]Holiday
     */
    private $holidaysArray;

    public function setup(): void
    {
        parent::setUp();
        $holidaysArray = [];
        $holiday = new Holiday();
        $holiday->setYear("2019");
        $holiday->setDates(["2019-01-01", "2019-04-02", "2019-04-25", "2019-05-01", "2019-05-02"]);
        $holidaysArray[] = $holiday;
        $holiday = new Holiday();
        $holiday->setYear("2018");
        $holiday->setDates(["2018-12-25"]);
        $holidaysArray[] = $holiday;
        $this->holidaysArray = $holidaysArray;
    }

    /**
     * @return array
     */
    public function isBusinessDayDataProvider()
    {
        return [
            [
                "initialDate" => "2019-01-01",
                "result" => false,
            ],
            [
                "initialDate" => "2019-01-02",
                "result" => true,
            ],
            [
                "initialDate" => "2019-01-03",
                "result" => true,
            ],
            [
                "initialDate" => "2019-01-06",
                "result" => false,
            ],
            [
                "initialDate" => "2019-01-07",
                "result" => false,
            ],

        ];
    }

    /**
     * @param string $initialDate
     * @param bool $result
     *
     * @dataProvider isBusinessDayDataProvider
     * @return void
     */
    public function testIsBusinessDay(string $initialDate, bool $result)
    {
        $initialDate = new DateTime($initialDate);
        $calculator = new BusinessDaysCalculatorService(
            $initialDate,
            $this->holidaysArray
        );
        $this->assertEquals($calculator->isBusinessDay($initialDate), $result);
    }

    /**
     * @return array
     */
    public function dataProvider()
    {

        return [
            [
                "initialDate" => "2018-11-10T00:00:00Z",
                "delay" => 3,
                "result" => [
                    "businessDate" => "2018-11-14T00:00:00Z",
                    "totalDays" => 5,
                    "holidayDays" => 0,
                    "weekendDays" => 2
                ],
            ],
            [
                "initialDate" => "2018-12-25T00:00:00Z",
                "delay" => 2,
                "result" => [
                    "businessDate" => "2018-12-27T00:00:00Z",
                    "totalDays" => 3,
                    "holidayDays" => 1,
                    "weekendDays" => 0
                ],

            ],
            [
                "initialDate" => "2018-12-25T00:00:00Z",
                "delay" => 20,
                "result" => [
                    "businessDate" => "2019-01-23T00:00:00Z",
                    "totalDays" => 30,
                    "holidayDays" => 2,
                    "weekendDays" => 8
                ],

            ],
            [
                "initialDate" => "2019-01-01T00:00:10Z",
                "delay" => 2,
                "result" => [
                    "businessDate" => "2019-01-03T00:00:10Z",
                    "totalDays" => 3,
                    "holidayDays" => 1,
                    "weekendDays" => 0
                ],

            ],
            [
                "initialDate" => "2019-04-23T15:15:10Z",
                "delay" => 3,
                "result" => [
                    "businessDate" => "2019-04-26T15:15:10Z",
                    "totalDays" => 4,
                    "holidayDays" => 1,
                    "weekendDays" => 0
                ],
            ],
            [
                "initialDate" => "2019-01-01T00:00:10Z",
                "delay" => 3,
                "result" => [
                    "businessDate" => "2019-01-04T00:00:10Z",
                    "totalDays" => 4,
                    "holidayDays" => 1,
                    "weekendDays" => 0
                ],

            ],
            [
                "initialDate" => "2019-01-17T10:10:10Z",
                "delay" => 1,
                "result" => [
                    "businessDate" => "2019-01-17T10:10:10Z",
                    "totalDays" => 1,
                    "holidayDays" => 0,
                    "weekendDays" => 0
                ],
            ],
            [
                "initialDate" => "2019-04-23T15:15:10Z",
                "delay" => 3,
                "result" => [
                    "businessDate" => "2019-04-26T15:15:10Z",
                    "totalDays" => 4,
                    "holidayDays" => 1,
                    "weekendDays" => 0
                ],
            ],
            [
                "initialDate" => "2019-01-01T00:00:10Z",
                "delay" => 5,
                "result" => [
                    "businessDate" => "2019-01-08T00:00:10Z",
                    "totalDays" => 8,
                    "holidayDays" => 1,
                    "weekendDays" => 2
                ],

            ],
            [
                "initialDate" => "2019-01-19T10:10:10Z",
                "delay" => 2,
                "result" => [
                    "businessDate" => "2019-01-22T10:10:10Z",
                    "totalDays" => 4,
                    "holidayDays" => 0,
                    "weekendDays" => 2
                ],
            ],
            [
                "initialDate" => "2019-01-01T00:00:10Z",
                "delay" => 5,
                "result" => [
                    "businessDate" => "2019-01-08T00:00:10Z",
                    "totalDays" => 8,
                    "holidayDays" => 1,
                    "weekendDays" => 2
                ],
            ],
            [
                "initialDate" => "2019-04-30T00:00:10Z",
                "delay" => 2,
                "result" => [
                    "businessDate" => "2019-05-03T00:00:10Z",
                    "totalDays" => 4,
                    "holidayDays" => 2,
                    "weekendDays" => 0
                ],

            ],
            [
                "initialDate" => "2019-04-30T00:00:10Z",
                "delay" => 1,
                "result" => [
                    "businessDate" => "2019-04-30T00:00:10Z",
                    "totalDays" => 1,
                    "holidayDays" => 0,
                    "weekendDays" => 0
                ],

            ],
        ];

    }

    /**
     * @param string $initialDate
     * @param int $delay
     * @param array $result
     *
     * @dataProvider dataProvider
     * @return void
     */
    public function testBusinessDaysService(string $initialDate, int $delay, array $result)
    {
        $initialDate = new DateTime($initialDate);
        $calculator = new BusinessDaysCalculatorService(
            $initialDate,
            $this->holidaysArray
        );

        $calculator->addBusinessDays($delay);
        $this->assertEquals($result["businessDate"], $calculator->getBusinessDate()->format('Y-m-d\TH:i:s\Z'));
        $this->assertEquals($result["holidayDays"], $calculator->getHolidayDays());
        $this->assertEquals($result["weekendDays"], $calculator->getWeekendDays());
    }


}