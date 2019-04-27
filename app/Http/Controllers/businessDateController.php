<?php

namespace App\Http\Controllers;


use App\Domain\Services\FileReaderService;
use App\Domain\Hydrators\HolidaysHydrator;
use App\Domain\Services\BusinessDaysCalculatorService;
use App\Domain\Transformers\BusinessDaysResponseTransformer;
use App\Exceptions\DateNotValidException;
use DateTime, Validator;
use Illuminate\Http\Request;

class businessDateController extends Controller
{

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
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
        $holidaysArray = $this->getHolidaysArray();

        $businessDaysCalculator = new BusinessDaysCalculatorService($initialDate, $holidaysArray);
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
        $businessDaysCalculator = new BusinessDaysCalculatorService($initialDate, $holidaysArray);
        return response()->json($businessDaysCalculator->isBusinessDay($initialDate), 200);
    }

    /**
     * get holidays from json file in storage
     * @return array
     */
    private function getHolidaysArray(): array
    {
        //@todo cache holidays file in redis
        $filePath = storage_path() . '/data/holidays.json';
        $fileReaderService = new FileReaderService($filePath);
        $hydrator = new HolidaysHydrator($fileReaderService);
        return $hydrator->hydrate();
    }

}
