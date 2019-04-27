<?php

namespace App\Domain\Hydrators;

use App\Domain\Models\Holiday;
use App\Domain\Contracts\FileReaderInterface;

/**
 * Class HolidaysHydrator
 * @package App\Domain\Hydrators
 *
 * The goal of this class is to hydrate the items saved in the json file
 * into array of domain model (Holiday)
 *
 */
class HolidaysHydrator
{
    private $jsonFileReaderService;

    function __construct(FileReaderInterface $jsonFileReaderService)
    {
        $this->jsonFileReaderService = $jsonFileReaderService;
    }

    /**
     * @return array
     */
    public function hydrate(): array
    {
        $content = $this->jsonFileReaderService->readFileContent();
        $holidaysArray = json_decode($content, true);
        $hydratedHolidays = [];
        foreach ($holidaysArray as $holiday) {
            $hydratedHoliday = new Holiday();
            $hydratedHoliday->setYear($holiday['year'] ?? '');
            $hydratedHoliday->setDates($holiday['dates'] ?? []);
            $hydratedHolidays[] = $hydratedHoliday;
        }

        return $hydratedHolidays;
    }

}