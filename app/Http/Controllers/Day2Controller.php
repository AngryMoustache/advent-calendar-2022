<?php

namespace App\Http\Controllers;

class Day2Controller extends Controller
{
    public $outcomeMultiplier = [
        'X' => 0,
        'Y' => 1,
        'Z' => 2,
    ];

    public $combinations = [
        'A' => [ // Rock
            'X' => 3, // Rock
            'Y' => 6, // Paper
            'Z' => 0, // Scissors
        ],
        'B' => [ // Paper
            'X' => 0, // Rock
            'Y' => 3, // Paper
            'Z' => 6, // Scissors
        ],
        'C' => [ // Scissors
            'X' => 6, // Rock
            'Y' => 0, // Paper
            'Z' => 3, // Scissors
        ],
    ];

    public function one()
    {
        return $this->data()->map (function ($round) {
            return ($this->outcomeMultiplier[$round[1]] + 1)
                + $this->combinations[$round[0]][$round[1]];
        })->sum();
    }

    public function two()
    {
        return $this->data()->map (function ($round) {
            $roundScore = ($this->outcomeMultiplier[$round[1]] * 3);
            $chosen = array_flip($this->combinations[$round[0]]);

            return $roundScore + ($this->outcomeMultiplier[$chosen[$roundScore]] + 1);
        })->sum();
    }

    private function data()
    {
        $file = file(public_path('inputs/2-1.txt'), FILE_IGNORE_NEW_LINES);

        return collect($file)->map(fn ($line) => explode(' ', $line));
    }
}
