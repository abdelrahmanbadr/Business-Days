<?php

namespace Tests\Domain\Hydrators;

use App\Domain\Hydrators\HolidaysHydrator;
use Tests\TestCase;
use  Mockery;
use App\Domain\Services\FileReaderService;
use App\Domain\Models\Holiday;

class HolidaysHydratorTest extends TestCase
{

    private $fileReaderMock;

    public function setup(): void
    {
        parent::setUp();
        $this->fileReaderMock = Mockery::mock(FileReaderService::class);
    }

    /** @test * */
    function testHydrate(): void
    {
        $json = '{"USA":[{"year": 2019,"dates": ["2019-01-01", "2019-01-02"]}]}';
        $this->fileReaderMock->shouldReceive('readFileContent')->andReturn($json);
        $holidaysHydrator = new HolidaysHydrator($this->fileReaderMock);
        $holidaysHydrator->hydrate();
        $holidaysArray = [];
        $holiday = new Holiday();
        $holiday->setYear("2019");
        $holiday->setDates(["2019-01-01", "2019-01-02"]);
        $holidaysArray[] = $holiday;
        $this->assertEquals($holidaysHydrator->hydrate(), $holidaysArray);
    }

}