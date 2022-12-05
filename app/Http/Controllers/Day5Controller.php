<?php

namespace App\Http\Controllers;

use Illuminate\Support\Collection;

class Day5Controller extends Controller
{
    public Collection $cargo;

    public function one()
    {
        $this->data()->each(function ($step) {
            $boxes = $this->cargo[$step['from']]->pop($step['amount']);
            $this->cargo[$step['to']] = $this->cargo[$step['to']]->merge($boxes);
        });

        return $this->cargo->map(fn ($c) => $c->last())->join('');
    }

    public function two()
    {
        $this->data()->each(function ($step) {
            $boxes = $this->cargo[$step['from']]->pop($step['amount']);
            $this->cargo[$step['to']] = $this->cargo[$step['to']]->merge($boxes->reverse());
        });

        return $this->cargo->map(fn ($c) => $c->last())->join('');
    }

    private function data()
    {
        $cargo = file(public_path('inputs/5-1.txt'), FILE_IGNORE_NEW_LINES);
        $steps = file(public_path('inputs/5-2.txt'), FILE_IGNORE_NEW_LINES);

        $this->cargo = collect($cargo)->mapWithKeys(function ($list, $key) {
            return [$key + 1 => collect(str_split($list))];
        });

        return collect($steps)->map(function ($step) {
            $step = explode(' ', $step);

            return [
                'amount' => $step[1],
                'from' => $step[3],
                'to' => $step[5],
            ];
        });
    }
}
