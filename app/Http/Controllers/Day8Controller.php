<?php

namespace App\Http\Controllers;

use App\Entities\TreeTops;

class Day8Controller extends Controller
{
    public function one()
    {
        return $this->data()
            ->map(fn ($grid, $x, $y) => $grid->visible($x, $y))
            ->filter()
            ->count();
    }

    public function two()
    {
        return $this->data()
            ->map(fn ($grid, $x, $y) => $grid->count($x, $y))
            ->max();
    }

    private function data(): TreeTops
    {
        $file = file(public_path('inputs/8-1.txt'), FILE_IGNORE_NEW_LINES);
        $grid = collect($file)->map(fn ($line) => collect(str_split($line)));

        return new TreeTops($grid);
    }
}
