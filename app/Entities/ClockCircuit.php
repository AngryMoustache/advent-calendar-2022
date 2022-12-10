<?php

namespace App\Entities;

use Illuminate\Support\Str;

class ClockCircuit
{
    public int $cycle = 0;
    public int $register = 1;
    public array $footprints = [];
    public array $pixels = [];

    public function instruct($instruction)
    {
        yield $this->register;

        $this->tick();

        // Do nothing if the instruction is a noop
        if ($instruction !== 'noop') {
            $this->tick();
            $this->register += Str::after($instruction, 'addx ');
        }
    }

    public function tick()
    {
        $this->cycle++;

        // Draw a pixel for each cycle
        $positions = range($this->register, $this->register + 2);
        $this->pixels[$this->cycle] = in_array($this->cycle % 40, $positions) ? '■' : '□';

        // Set a footprint for the desired cycles
        if ($this->cycle === 20 || ($this->cycle - 20) % 40 === 0) {
            $this->footprints[$this->cycle] = $this->register;
        }
    }

    public function strength()
    {
        return collect($this->footprints)
            ->map(fn ($footprint, $cycle) => $footprint * $cycle)
            ->sum();
    }
}
