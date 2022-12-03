<?php

namespace App\Http\Controllers;

use Illuminate\Support\Collection;

class Day3Controller extends Controller
{
    public Collection $ranges;

    public function one()
    {
        return $this->data()->map(function ($rucksack) {
            $rucksack = collect($rucksack)->splitIn(2);
            $item = $rucksack[0]->intersect($rucksack[1])->first();

            return $this->ranges[$item];
        })->sum();
    }

    public function two()
    {
        return $this->data()->chunk(3)->map(function ($group) {
            $badge = collect(array_intersect(...$group->toArray()))->first();

            return $this->ranges[$badge];
        })->sum();
    }

    private function data()
    {
        $this->ranges = collect([
            collect(range('a', 'z'))->mapWithKeys(fn ($letter, $value) => [$letter => $value + 1]),
            collect(range('A', 'Z'))->mapWithKeys(fn ($letter, $value) => [$letter => $value + 27])
        ])->mapWithKeys(fn ($i) => $i);

        $file = file(public_path('inputs/3-1.txt'), FILE_IGNORE_NEW_LINES);

        return collect($file)->map(fn ($line) => str_split($line));
    }
}
