<?php

namespace App\Domain\Contracts;


use App\Domain\Models\Holiday;

interface HydratorInterface
{
    public function hydrate(string $year): array ;
}