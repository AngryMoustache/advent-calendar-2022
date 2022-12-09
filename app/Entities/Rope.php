<?php

namespace App\Entities;

use Illuminate\Support\Collection;

class Rope
{
    public array $head;
    public array $tail;
    public Collection $visited;

    public function __construct(int $length)
    {
        $this->head = [0, 0];
        $this->visited = collect([$this->head]);
        $this->tail = array_pad([], $length, [0, 0]);
    }

    public function moveHead($dir, $steps)
    {
        for ($i = 0; $i < $steps; $i++) {
            match ($dir) {
                'U' => $this->head[1] -= 1,
                'R' => $this->head[0] += 1,
                'D' => $this->head[1] += 1,
                'L' => $this->head[0] -= 1,
            };

            $this->updateTail();
        }
    }

    public function updateTail()
    {
        // Have every knot move into position besides the previous one
        for ($knot = 0; $knot < count($this->tail); $knot++) {
            // Move the previous knot into position
            $target = $this->tail[$knot - 1] ?? $this->head;
            while (! $this->tailIsAdjacent($knot, $target)) {
                if ($this->tail[$knot][1] < $target[1]) {
                    $this->tail[$knot][1]++;
                }

                if ($this->tail[$knot][1] > $target[1]) {
                    $this->tail[$knot][1]--;
                }

                if ($this->tail[$knot][0] < $target[0]) {
                    $this->tail[$knot][0]++;
                }

                if ($this->tail[$knot][0] > $target[0]) {
                    $this->tail[$knot][0]--;
                }
            }

            $this->visited->push(end($this->tail));
        }
    }

    public function tailIsAdjacent($knot, $target)
    {
        return in_array($this->tail[$knot], [
            $target,
            [$target[0] + 1, $target[1]],
            [$target[0] - 1, $target[1]],
            [$target[0], $target[1] + 1],
            [$target[0], $target[1] - 1],
            [$target[0] + 1, $target[1] + 1],
            [$target[0] - 1, $target[1] + 1],
            [$target[0] + 1, $target[1] - 1],
            [$target[0] - 1, $target[1] - 1],
        ]);
    }

    public function count()
    {
        return $this->visited->unique()->count();
    }
}
