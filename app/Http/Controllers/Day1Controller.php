<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;

class Day1Controller extends Controller
{
    public function one()
    {
        return $this->data()->max();
    }

    public function two()
    {
        return $this->data()->sortDesc()->take(3)->sum();
    }

    private function data()
    {
        $file = File::get(public_path('inputs/1-1.txt'), 'r');

        return collect(explode(PHP_EOL . PHP_EOL, $file))
            ->map(fn ($calories) => array_sum(explode(PHP_EOL, $calories)));
    }
}
