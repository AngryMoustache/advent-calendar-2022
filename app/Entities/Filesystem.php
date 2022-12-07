<?php

namespace App\Entities;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class Filesystem
{
    public Collection $cursor;
    public Collection $directories;

    public int $sizeMax = 70000000;
    public int $upgradeSize = 30000000;

    public function __construct(
        public array $list = [],
    ) {
        $this->cursor = collect();
        $this->directories = collect();
    }

    public function navigate($to)
    {
        $to = $to->toString();
        match ($to) {
            '/' => $this->cursor = collect(['/']),
            '..' => $this->cursor->pop(),
            default => $this->cursor->push($to),
        };
    }

    public function addDirectory($name)
    {
        $this->setOnCursor($name, []);
        $this->directories->push($this->cursor->join('.') . ".{$name}");
    }

    public function addFile($size, $name)
    {
        $this->setOnCursor(str_replace('.', '-', $name), $size);
    }

    public function freeSpace()
    {
        return $this->sizeMax - collect($this->list)->flatten()->sum();
    }

    public function getDirectorySizes()
    {
        return $this->directories->mapWithKeys(function ($dir) {
            return [$dir => collect(Arr::get($this->list, $dir))->flatten()->sum()];
        });
    }

    private function setOnCursor($key, $value)
    {
        $dir = $this->cursor->join('.');
        Arr::set($this->list, "{$dir}.{$key}", $value);
    }
}
