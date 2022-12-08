<?php

namespace App\Entities;

use Illuminate\Support\Collection;

class TreeTops
{
    private Collection $transposed;

    public function __construct(public Collection $grid)
    {
        // Transpose the grid for easier column access
        $this->transposed = collect(array_map(
            fn (...$rows) => collect($rows),
            ...$grid->toArray()
        ));
    }

    public function map($callback)
    {
        return $this->grid->map(function ($row, $y) use ($callback) {
            return $row->keys()->map(fn ($x) => $callback($this, $x, $y));
        })->flatten();
    }

    public function visible($x, $y): bool
    {
        $tree = $this->grid[$y][$x];
        $row = $this->grid[$y];
        $column = $this->transposed[$x];

        return  $column->take($y)->reject(fn ($t) => $t < $tree)->isEmpty() // Up
            || $row->skip($x + 1)->reject(fn ($t) => $t < $tree)->isEmpty() // Right
            || $column->skip($y + 1)->reject(fn ($t) => $t < $tree)->isEmpty() // Down
            || $row->take($x)->reject(fn ($t) => $t < $tree)->isEmpty(); // Left
    }

    public function count($x, $y)
    {
        $tree = $this->grid[$y][$x];
        $row = $this->grid[$y];
        $column = $this->transposed[$x];

        return $this->countUntil($column->take($y)->reverse(), $tree) // Up
            * $this->countUntil($row->skip($x + 1), $tree) // Right
            * $this->countUntil($column->skip($y + 1), $tree) // Down
            * $this->countUntil($row->take($x)->reverse(), $tree); // Left
    }

    private function countUntil($row, $until): int
    {
        $result = $row->takeUntil(fn ($t) => $t >= $until)->count();

        // Make sure we count the tree that stopped the iteration
        return $result + ($result < $row->count());
    }
}
