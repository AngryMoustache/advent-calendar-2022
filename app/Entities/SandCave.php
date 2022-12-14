<?php

namespace App\Entities;

use Illuminate\Support\Collection;

class SandCave
{
    const ROCK = '#';
    const SAND = 'o';

    public int $abyss = 0;
    public array $cave = [];

    public int $count = 0;
    public int $countToAbyss = -1;

    public function __construct(Collection $scans, public array $origin) {
        $scans->each(function ($scan) {
            $scan = collect(explode(' -> ', $scan))->map(fn ($scan) => explode(',', $scan));
            for ($i = 0; $i < $scan->count() - 1; $i++) {
                $this->drawLine($scan[$i], $scan[$i + 1]);
            }
        });

        // Get the highest Y value
        $this->abyss = collect($this->cave)->max(fn ($x) => collect($x)->keys()->max());

        // Set the floor
        for ($i = 0; $i < 1000; $i++) {
            $this->cave[$i][$this->abyss + 2] = self::ROCK;
        }
    }

    public function dropSand($x, $y)
    {
        if (isset($this->cave[$x][$y])) {
            return;
        }

        if ($y === $this->abyss && $this->countToAbyss === -1) {
            $this->countToAbyss = $this->count;
        }

        $this->dropSand($x , $y + 1);
        $this->dropSand($x - 1, $y + 1);
        $this->dropSand($x + 1, $y + 1);

        $this->cave[$x][$y] = self::SAND;
        $this->count++;
    }

    public function drawLine($from, $to)
    {
        if ($from[0] === $to[0]) { // Vertical
            $fill = range(min($from[1], $to[1]), max($from[1], $to[1]));
            for ($i = 0; $i < count($fill); $i++) {
                $this->cave[(int) $from[0]] ??= [];
                $this->cave[(int) $from[0]][(int) $fill[$i]] = self::ROCK;
            }
        } else { // Horizontal
            $fill = range(min($from[0], $to[0]), max($from[0], $to[0]));
            for ($i = 0; $i < count($fill); $i++) {
                $this->cave[(int) $fill[$i]] ??= [];
                $this->cave[(int) $fill[$i]][(int) $from[1]] = self::ROCK;
            }
        }
    }
}
