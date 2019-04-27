<?php


namespace App\Domain\Models;

/**
 * Class Holiday
 * @package App\Domain\Models
 */
class Holiday
{
    /**
     * @var array
     */
    private $dates;
    /**
     * @var string
     */
    private $year;


    /**
     * @param string $year
     */
    public function setYear(string $year)
    {
        $this->year = $year;
    }

    /**
     * @return string
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @param array $dates
     */
    public function setDates(array $dates)
    {
        $this->dates = $dates;
    }

    /**
     * @return array
     */
    public function getDates()
    {
        return $this->dates;
    }


}