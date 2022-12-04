<?php

namespace App\Http\Controllers;

use Illuminate\Support\Collection;

class Day4Controller extends Controller
{
    public Collection $ranges;

    public function one()
    {
        return $this->data()->reject(function ($coords) {
            // Make sure the shortest array is the first one
            $coords = $coords->sortBy(fn ($range) => count($range));

            return !! count(array_diff(...$coords));
        })->count();
    }

    public function two()
    {
        return $this->data()
            ->reject(fn ($coords) => ! count(array_intersect(...$coords)))
            ->count();
    }

    private function data()
    {
        $file = file(public_path('inputs/4-1.txt'), FILE_IGNORE_NEW_LINES);

        return collect($file)
            ->map(fn ($line) => explode(',', $line))
            ->map(function ($coords) {
                return collect($coords)->map(fn ($range) => range(...explode('-', $range)));
            });
    }
}
