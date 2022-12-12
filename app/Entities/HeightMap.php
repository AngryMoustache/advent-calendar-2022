<?php

namespace App\Entities;

use Illuminate\Support\Collection;

class HeightMap
{
    public array $start;
    public Collection $steps;
    public Collection $results;

    public function __construct(
        public Collection $grid,
        public int $end = 27,
    ) {
        $this->results = collect();
        $this->steps = collect(['visited' => collect(), 'steps' => collect()]);
        $this->findStart();
    }

    public function calculate()
    {
        $queue = collect([[...$this->start, 0]]);

        while ($queue->isNotEmpty()) {
            [$x, $y, $steps] = $queue->shift();

            if ($this->grid[$y][$x] === $this->end) {
                $this->results->push($steps);
            }

            $this->surroundings($x, $y)->each(function ($neighbor) use ($x, $y, $steps, &$queue) {
                $current = $this->grid[$y][$x];
                $target = $this->grid[$neighbor[1]][$neighbor[0]] ?? null;

                if (
                    is_null($target)
                    || $target - $current > 1
                    || $this->steps['visited']->contains("{$neighbor[0]}-{$neighbor[1]}")
                ) {
                    return;
                }

                $this->steps['visited']->push("{$neighbor[0]}-{$neighbor[1]}");
                $queue->push([...$neighbor, $steps + 1]);
            });
        }

        return $this->results;
    }

    public function surroundings($x, $y)
    {
        return collect([
            [$x + 1, $y],
            [$x, $y - 1],
            [$x - 1, $y],
            [$x, $y + 1],
        ]);
    }

    public function flip()
    {
        $this->end = 26;

        $this->grid->transform(function ($row) {
            return $row->transform(function ($cell) {
                $cell = 27 - $cell;
                if ($cell === 27) return 26;
                return $cell;
            });
        });

        $this->findStart();

        return $this;
    }

    private function findStart()
    {
        $this->grid->each(function ($row, $y) {
            $row->each(function ($cell, $x) use ($y) {
                if ($cell === 0) {
                    $this->start = [$x, $y];
                }
            });
        });
    }
}
