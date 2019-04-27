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

}