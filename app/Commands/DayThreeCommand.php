<?php

declare(strict_types=1);

namespace App\Commands;

class DayThreeCommand extends BaseCommand
{
    protected $signature = 'day:03';

    protected $description = 'Day 3';

    public function inputFile(): string
    {
        return 'three.txt';
    }

    public function solvePuzzlePartOne(): string
    {
        return strval(
            ''
        );
    }

    public function solvePuzzlePartTwo(): string
    {
        return strval(
            ''
        );
    }
}
