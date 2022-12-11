<?php

namespace App\Http\Controllers;

use App\Entities\Monkey;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Day11Controller extends Controller
{
    public Collection $monkeys;
    public int $modulo = 1;

    public function one()
    {
        return $this->passTurns(20);
    }

    public function two()
    {
        return $this->passTurns(10000, false);
    }

    private function passTurns(int $turns, bool $worry = true)
    {
        $this->data();

        for ($round = 0; $round < $turns; $round++) {
            $this->monkeys->each(function (Monkey $monkey) use ($worry) {
                // Loop over the items and pass them to the other monkeys
                $monkey->items->each(function (int $item) use ($worry, $monkey) {
                    $item = $monkey->inspect($item, $worry) % $this->modulo;
                    $this->monkeys[$monkey->test($item)]->items->push($item);
                });

                // Increment the inspections and reset the monkeys items
                $monkey->inspections += $monkey->items->count();
                $monkey->items = collect();
            });
        }

        return $this->monkeys
            ->pluck('inspections')
            ->sortDesc()
            ->take(2)
            ->reduce(fn ($a, $b) => $a * $b, 1);
    }

    private function data()
    {
        $file = file(public_path('inputs/11-1.txt'), FILE_IGNORE_NEW_LINES);

        $this->monkeys = collect();

        collect($file)->each(function ($line) use (&$monkey) {
            $line = Str::of($line)->trim();

            if ($line->startsWith('Monkey ')) {
                $monkey = new Monkey;
            }

            if ($line->startsWith('Operation: ')) {
                $monkey->operation = $line->after('Operation: new = ')
                    ->replace('old', '$item')
                    ->toString();
            }

            if ($line->startsWith('Test: ')) {
                $monkey->test = $line->after('Test: ')
                    ->replace('divisible by ', '')
                    ->toString();
            }

            if ($line->startsWith('Starting items: ')) {
                $monkey->items = $line->after('Starting items: ')
                    ->explode(', ')
                    ->map(fn ($item) => (int) $item);
            }

            if ($line->startsWith('If false: ')) {
                $monkey->results[0] = (int) $line->after('monkey ')->toString();
            }

            if ($line->startsWith('If true: ')) {
                $monkey->results[1] = (int) $line->after('monkey ')->toString();
            }

            if ($line->toString() === '') {
                $this->monkeys->push($monkey);
            }
        });

        // Save the stray monkey
        $this->monkeys->push($monkey);

        // Do whatever this is
        $this->monkeys->each(fn ($monkey) => $this->modulo *= $monkey->test);
    }
}
