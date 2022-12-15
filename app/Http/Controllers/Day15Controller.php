<?php

namespace App\Http\Controllers;

use App\Entities\Sensor;

class Day15Controller extends Controller
{
    public function one()
    {
        // Gather the data
        $sensors = $this->findAtDepth(10);

        // Count the number of spots that are covered
        $count = count(range($sensors->min('from'), $sensors->max('to')));
        $beacons = $sensors->pluck('beacon')->filter()->unique();

        return $count - $beacons->count();
    }

    public function two()
    {
        foreach (range(0, 20) as $y) {
            $sensors = $this->findAtDepth($y);

            $from = $sensors->min('from');
            $to = $sensors->max('to');
            $range = range($from, $to);

            foreach ($sensors as $sensor) {
                $range = array_diff($range, range($sensor['from'], $sensor['to']));
                if (count($range) === 0) {
                    continue 2;
                }
            }

            return (4000000 * array_values($range)[0]) + $y;
        }
    }

    private function findAtDepth(int $depth)
    {
        return $this->data()
            ->map(fn ($sensor) => $sensor->countAlong($depth))
            ->filter();
    }

    private function data()
    {
        $file = file(public_path('inputs/15-2.txt'), FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES);

        return collect($file)->map(fn ($line) => new Sensor(json_decode($line)));
    }
}
