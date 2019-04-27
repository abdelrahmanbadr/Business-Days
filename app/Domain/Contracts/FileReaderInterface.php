<?php
/**
 * Created by PhpStorm.
 * User: abdelrahmanbadr
 * Date: 4/25/19
 * Time: 3:31 PM
 */

namespace App\Domain\Contracts;


interface FileReaderInterface
{
    /**
     * @return string
     */
    public function readFileContent(): string;

}