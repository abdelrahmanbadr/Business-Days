<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Business Days Config
    |--------------------------------------------------------------------------

    */


    'weekend_days' => env('WEEKEND_DAYS', App\Constants\WeekDays::WEEKENDS_DAYS),
    'holidays_country' => env('HOLIDAYS_COUNTRY', "USA"),


];
