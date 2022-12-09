<?php

namespace App\Http\Controllers;

use App\Entities\Rope;

class Day9Controller extends Controller
{
    public Rope $rope;

    public function one()
    {
        return $this->data(1)->count();
    }

    public function two()
    {
        return $this->data(9)->count();
    }

    private function data(int $length)
    {
        $file = file(public_path('inputs/9-1.txt'), FILE_IGNORE_NEW_LINES);
        $this->rope = new Rope($length);

        collect($file)
            ->map(fn ($line) =>explode(' ', $line))
            ->each(fn ($step) => $this->rope->moveHead(...$step));

        return $this->rope;
    }
}
