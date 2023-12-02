<?php

declare(strict_types=1);

namespace App\Commands;

class DayTwoCommand extends BaseCommand
{
    protected $signature = 'day:02';

    protected $description = 'Day 2';

    public function inputFile(): string
    {
        return 'two.txt';
    }

    public function solvePuzzlePartOne(): string
    {
        return '';
    }

    public function solvePuzzlePartTwo(): string
    {
        return '';
    }
}
