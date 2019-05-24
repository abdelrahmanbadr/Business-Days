<?php

namespace App\Http\Controllers;


use App\Domain\Models\Holiday;
use App\Domain\Services\FileReaderService;
use App\Domain\Hydrators\HolidaysHydrator;
use App\Domain\Services\BusinessDaysCalculatorService;
use App\Domain\Transformers\BusinessDaysResponseTransformer;
use App\Exceptions\DateNotValidException;
use App\Exceptions\YearHolidaysNotFoundException;
use DateTime, Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class businessDateController extends Controller
{

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\YearHolidaysNotFoundException
     */
    public function getBusinessDates(Request $request)
    {

        $validator = Validator::make($request->all(), [
                'initialDate' => "required|date",
                'delay' => "required|integer|min:1",
            ]
        );

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $delay = $request->delay;
        $initialDate = new DateTime($request->initialDate);
        $year = $initialDate->format("Y");

        try {
            $holidaysArray = $this->getHolidaysArray($year);
        } catch (YearHolidaysNotFoundException $e) {
            //@todo move to constants
            return response()->json("Data Not Found", 200);
        }

        $businessDaysCalculator = new BusinessDaysCalculatorService($initialDate, [$holidaysArray]);
        $businessDaysCalculator->addBusinessDays($delay);
        $weekendDays = $businessDaysCalculator->getWeekendDays();
        $holidayDays = $businessDaysCalculator->getHolidayDays();
        $businessDate = $businessDaysCalculator->getBusinessDate();

        $transformedData = (new BusinessDaysResponseTransformer())->transform($businessDate, $weekendDays, $holidayDays, $delay);
        return response()->json($transformedData, 200);
    }

    public function isBusinessDay($initialDate)
    {
        try {
            $initialDate = new DateTime($initialDate);
        } catch (\Exception $e) {

            throw new DateNotValidException(sprintf('Input initial date is not valid date %s', $initialDate));
        }

        $holidaysArray = $this->getHolidaysArray();
        $businessDaysCalculator = new BusinessDaysCalculatorService($initialDate, [$holidaysArray]);
        return response()->json($businessDaysCalculator->isBusinessDay(), 200);
    }

    /**
     * @param string $year
     * @return array
     * @throws \App\Exceptions\YearHolidaysNotFoundException
     */
    private function getHolidaysArray(string $year): Holiday
    {
        //@todo cache holidays file in redis
        $filePath = storage_path() . '/data/holidays.json';
        $fileReaderService = new FileReaderService($filePath);
        $hydrator = new HolidaysHydrator($fileReaderService);

        //@todo move the key to constant
        if (Cache::Has("holidays_" . $year)) {
            $cachedHolidays = Cache::get("holidays_" . $year);
            $yearHolidays = json_decode($cachedHolidays, true);

        } else {
            $yearHolidays = $hydrator->hydrate($year);
            //@todo move to config and .env
            Cache::Put("holidays_" . $year, json_encode($yearHolidays), 2000);
        }
        $holidayObject = new Holiday();
        $holidayObject->setYear($year);
        $holidayObject->setDates($yearHolidays);

        return $holidayObject;
    }

}
