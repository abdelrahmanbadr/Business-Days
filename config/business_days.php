<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Business Days Config
    |--------------------------------------------------------------------------

    */


    'weekend_days' => env('WEEKEND_DAYS', App\Constants\WeekDays::WEEKENDS_DAYS),
    'business_country' => env('BUSINESS_COUNTRY', "USA"),


];
