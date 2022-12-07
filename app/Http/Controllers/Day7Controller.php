<?php

namespace App\Http\Controllers;

use App\Entities\Filesystem;
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;

class Day7Controller extends Controller
{
    public Filesystem $filesystem;

    public function one()
    {
        $this->data();

        return $this->filesystem->getDirectorySizes()
            ->filter(fn ($size) => $size <= 100000)
            ->sum();
    }

    public function two()
    {
        $this->data();

        $free = $this->filesystem->freeSpace();
        $needed = $this->filesystem->upgradeSize - $free;

        return $this->filesystem->getDirectorySizes()
            ->sort()
            ->first(fn ($size) => $size > $needed);
    }

    private function data()
    {
        $file = file(public_path('inputs/7-1.txt'), FILE_IGNORE_NEW_LINES);
        $this->filesystem = new Filesystem;

        collect($file)->map(fn ($line) => Str::of($line))->each(function (Stringable $command) {
            // List command - ignore
            if ($command->startsWith('$ ls')) {
                return;
            }

            // Navigation command
            if ($command->startsWith('$ cd')) {
                $this->filesystem->navigate($command->after('$ cd '));
                return;
            }

            // Listed directory
            if ($command->startsWith('dir')) {
                $this->filesystem->addDirectory($command->after('dir '));
                return;
            }

            // Listed file
            $this->filesystem->addFile(...$command->explode(' '));
        });
    }
}
