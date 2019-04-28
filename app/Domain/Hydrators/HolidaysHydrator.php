<?php

namespace App\Domain\Hydrators;


use App\Domain\Models\Holiday;
use App\Domain\Contracts\{FileReaderInterface, HydratorInterface};
use Illuminate\Support\Facades\Log;

/**
 * Class HolidaysHydrator
 * @package App\Domain\Hydrators
 *
 * The goal of this class is to hydrate the items saved in the json file
 * into array of domain model (Holiday)
 *
 */
class HolidaysHydrator implements HydratorInterface
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
        $countryName = config("business_days.business_country");
        if (!isset($holidaysArray[$countryName])) {
            Log::error("BUSINESS_COUNTRY added in .env file not found in holidays.json file $countryName");
            return [];
        }
        $hydratedHolidays = [];
        foreach ($holidaysArray[$countryName] as $holiday) {
            $hydratedHoliday = new Holiday();
            $hydratedHoliday->setYear($holiday['year'] ?? '');
            $hydratedHoliday->setDates($holiday['dates'] ?? []);
            $hydratedHolidays[] = $hydratedHoliday;
        }

        return $hydratedHolidays;
    }

}