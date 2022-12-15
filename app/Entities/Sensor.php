<?php

namespace App\Entities;

use Illuminate\Support\Collection;

class Sensor
{
    public int $size;

    public function __construct(public array $position)
    {
        $this->size = abs($position[0][0] - $position[1][0])
            + abs($position[0][1] - $position[1][1]);
    }

    public function countAlong(int $y)
    {
        // Does the Y fall into our diamond?
        $middleY = $this->position[0][1];
        if (! in_array($y, range($middleY - $this->size, $middleY + $this->size))) {
            return null;
        }

        // Don't double count if you've already visited a spot
        $sizeX = $this->size - abs($y - $this->position[0][1]);
        $from = $this->position[0][0] - $sizeX;
        $to = $this->position[0][0] + $sizeX;

        return [
            'beacon' => ($this->position[1][1] === $y) ? $this->position[1] : false,
            'from' => $from,
            'to' => $to,
        ];
    }
}
