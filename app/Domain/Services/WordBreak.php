<?php


namespace App\Domain\Services;


class WordBreak
{

    //make cache to store T or F foreach iteration round
    //for example "bobo" and ["ba","bo"]
    //   0 1 2 3
    //0[ F T F F ]
    //1[   F F F ]
    //2[     F T ]
    //3[       F ]
    //@todo search for better solution
    public function wordBreak(string $string, array $wordDict): bool
    {
        $cache = [];
        $count = strlen($string);
        for ($i = 0; $i <= $count; $i++) {
            for ($j = 1; $j <= $count - $i; $j++) {
                $cache[$i][$j] = in_array(substr($string, $i, $j), $wordDict);
            }
        }
        dd($cache);
    }

}