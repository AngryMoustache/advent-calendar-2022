<?php

namespace App\Http\Controllers;

use App\Entities\HeightMap;

class Day12Controller extends Controller
{
    public function one()
    {
        return $this->data()->calculate()->min();
    }

    public function two()
    {
        return $this->data()->flip()->calculate()->min();
    }

    private function data()
    {
        $file = file(public_path('inputs/12-1.txt'), FILE_IGNORE_NEW_LINES);

        return new HeightMap(collect($file)->map(function ($line) {
            return collect(str_split($line))->map(function ($cell) {
                return match ($cell) {
                    'S' => 0,
                    'E' => 27,
                    default => array_flip(range('a', 'z'))[$cell] + 1,
                };
            });
        }));
    }
}
