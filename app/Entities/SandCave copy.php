<?php

namespace App\Entities;

use Illuminate\Support\Collection;

class SandCave
{
    const ROCK = '#';
    const SAND = 'o';
    const EMPTY = '.';

    public Collection $cave;
    public int $abyss = 0;

    public function __construct(Collection $scans, public array $origin) {
        $this->cave = collect();

        // Scan the cave
        $scans->each(function ($scan) {
            $scan = collect(explode(' -> ', $scan))->map(fn ($scan) => explode(',', $scan));
            for ($i = 0; $i < $scan->count() - 1; $i++) {
                $this->drawLine($scan[$i], $scan[$i + 1]);
            }
        });

        $this->cave = $this->cave->unique();
    }

    public function countSand()
    {
        return $this->cave->filter(fn ($square) => $square[2] === self::SAND)->count();
    }

    public function drawLine($from, $to)
    {
        if ($to[1] > $this->abyss) {
            $this->abyss = $to[1];
        }

        if ($from[0] === $to[0]) {
            // Vertical
            $fill = range(min($from[1], $to[1]), max($from[1], $to[1]));
            for ($i = 0; $i < count($fill); $i++) {
                $this->cave->push([(int) $from[0], (int) $fill[$i], self::ROCK]);
            }
        } else {
            // Horizontal
            $fill = range(min($from[0], $to[0]), max($from[0], $to[0]));
            for ($i = 0; $i < count($fill); $i++) {
                $this->cave->push([(int) $fill[$i], (int) $from[1], self::ROCK]);
            }
        }
    }

    public function dropSandFrom($x, $y)
    {
        // Spawn a new sand particle and drop it
        info("Dropping from {$x}, {$y}");
        $sand = [$x, $y];
        while ($this->nextFreeSpace(...$sand) && $sand[1] < $this->abyss) {
            $sand = $this->nextFreeSpace(...$sand);
        }

        // If we have not reached the abyss, drop more sand
        if ($sand[1] < $this->abyss) {
            $this->cave->push([$sand[0], $sand[1], self::SAND]);
            $this->dropSandFrom(...$this->origin);
        }
    }

    private function nextFreeSpace($x, $y)
    {
        $surroundings = collect([
            $this->checkCoord($x, $y + 1),
            $this->checkCoord($x - 1, $y + 1),
            $this->checkCoord($x + 1, $y + 1),
        ]);

        return $surroundings->first(fn ($i) => $i[2] === self::EMPTY);
    }

    private function checkCoord($x, $y)
    {
        $space = $this->cave->first(fn ($square) => $square[0] === $x && $square[1] === $y);

        return [$x, $y, $space[2] ?? self::EMPTY];
    }

    public function render()
    {
        $render = '';
        for ($y = 0; $y <= $this->abyss; $y++) {
            for ($x = 450; $x <= 600; $x++) {
                $space = $this->cave->first(function ($square) use ($x, $y) {
                    return $square[0] === $x && $square[1] === $y;
                })[2] ?? self::EMPTY;

                $render .= $space;
            }
            $render .= PHP_EOL;
        }

        return $render;
    }
}
