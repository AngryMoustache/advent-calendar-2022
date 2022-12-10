<?php

namespace App\Http\Controllers;

use App\Entities\ClockCircuit;

class Day10Controller extends Controller
{
    public ClockCircuit $circuit;

    public function one()
    {
        return $this->data()->strength();
    }

    public function two()
    {
        // For cleaner output
        echo '<style>body { line-height: .5 }</style>';

        return collect($this->data()->pixels)
            ->splitIn(6)->map(fn ($row) => $row->implode(''))
            ->join('<br>');
    }

    private function data()
    {
        $file = file(public_path('inputs/10-1.txt'), FILE_IGNORE_NEW_LINES);
        $this->circuit = new ClockCircuit;

        collect($file)->map(function ($instruction) {
            // Trying yield for the first time, not 100% sure what I'm doing
            return iterator_to_array($this->circuit->instruct($instruction))[0];
        });

        return $this->circuit;
    }
}
