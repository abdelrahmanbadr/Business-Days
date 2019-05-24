<?php

namespace App\Domain\Hydrators;


use App\Domain\Models\Holiday;
use App\Domain\Contracts\{FileReaderInterface, HydratorInterface};
use Illuminate\Support\Facades\Log;
use App\Exceptions\YearHolidaysNotFoundException;

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
     * @param string $year
     * @return array
     * @throws YearHolidaysNotFoundException
     */
    public function hydrate(string $year): array
    {
        $content = $this->jsonFileReaderService->readFileContent();
        $holidaysArray = json_decode($content, true);
        $countryName = config("business_days.business_country");
        if (!isset($holidaysArray[$countryName])) {
            Log::error("BUSINESS_COUNTRY added in .env file not found in holidays.json file $countryName");
            return [];
        }

        //@todo try to make better search algorithm rather than linear search
        $hydratedHoliday = [];
        foreach ($holidaysArray[$countryName] as $holiday) {

            if ($holiday['year'] == $year) {
                $hydratedHoliday = $holiday['dates'];
            }
        }

        if (empty($hydratedHoliday)) {
            Log::error("year holidays not found " . $year);
            throw new YearHolidaysNotFoundException("year holidays not found");
        }

        return $hydratedHoliday;
    }

}