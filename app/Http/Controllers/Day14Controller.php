<?php

namespace App\Http\Controllers;

use App\Entities\SandCave;

class Day14Controller extends Controller
{
    public function one()
    {
        $cave = $this->data();
        $cave->dropSand(...$cave->origin);

        return $cave->countToAbyss;
    }

    public function two()
    {
        $cave = $this->data();
        $cave->dropSand(...$cave->origin);

        return $cave->count;
    }

    private function data()
    {
        $file = file(public_path('inputs/14-1.txt'), FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES);

        return new SandCave(collect($file), [500, 0]);
    }
}
