<?php

namespace App\Http\Controllers;

use Illuminate\Support\Collection;

class Day6Controller extends Controller
{
    public Collection $stream;

    public function one()
    {
        return $this->findMarkerWithSize(4);
    }

    public function two()
    {
        return $this->findMarkerWithSize(14);
    }

    private function findMarkerWithSize($size)
    {
        return (int) $this->data()->keys()
            ->filter(fn ($index) => $this->checkMarkerAt($index, $size))
            ->first() + $size;
    }

    private function checkMarkerAt($index, $size)
    {
        return $this->stream->skip($index)->take($size)->unique()->count() === $size;
    }

    private function data()
    {
        $file = file(public_path('inputs/6-1.txt'), FILE_IGNORE_NEW_LINES)[0];
        $this->stream = collect(str_split($file));

        return $this->stream;
    }
}
