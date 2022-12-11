<?php

namespace App\Entities;

use Illuminate\Support\Collection;

class Monkey
{
    public Collection $items;
    public int $inspections = 0;

    public string $test;
    public string $operation;
    public array $results = [];

    public function inspect(int $item, bool $worry = false)
    {
        eval("\$item = {$this->operation};");
        return (int) (($worry) ? floor($item / 3) : $item);
    }

    public function test(int $item)
    {
        return $this->results[(int) ($item % $this->test == 0)];
    }
}
