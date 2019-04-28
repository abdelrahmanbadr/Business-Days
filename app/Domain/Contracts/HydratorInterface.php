<?php

namespace App\Domain\Contracts;


interface HydratorInterface
{
    public function hydrate(): array;
}