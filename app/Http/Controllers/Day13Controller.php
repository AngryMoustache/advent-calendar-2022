<?php

namespace App\Http\Controllers;

use Illuminate\Support\Arr;

class Day13Controller extends Controller
{
    public function one()
    {
        return $this->data()->chunk(2)->reduce(function ($carry, $packet, $key) {
            return $this->compare(...$packet) < 0
                ? $carry + ($key + 1)
                : $carry;
        });
    }

    public function two()
    {
        $data = $this->data()->push([[2]])->push([[6]])->toArray();
        usort($data, [$this, 'compare']);

        return collect($data)
            ->filter(fn ($packet) => in_array($packet, [[[2]], [[6]]]))
            ->keys()
            ->reduce(fn ($carry, $key) => $carry * ($key + 1), 1);
    }

    private function compare($left, $right): int
    {
        if (is_array($left) || is_array($right)) {
            $left = Arr::wrap($left);
            $right = Arr::wrap($right);

            for ($i = 0; $i < min(count($left), count($right)); $i++) {
                if ($left[$i] !== $right[$i]) {
                    $test = $this->compare($left[$i], $right[$i]);
                    if ($test === 0) continue;

                    return $test;
                }
            }

            return count($left) <=> count($right);
        }

        return $left <=> $right;
    }

    private function data()
    {
        $file = file(public_path('inputs/13-1.txt'), FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES);

        return collect($file)->map(fn ($line) => json_decode($line, true));
    }
}
