<?php

declare(strict_types=1);

namespace App\Commands;

use Illuminate\Support\Str;

class DayFourCommand extends BaseCommand
{
    protected $signature = 'day:04';

    protected $description = 'Day 4';

    public function inputFile(): string
    {
        return 'four.txt';
    }

    public function solvePuzzlePartOne(): string
    {
        return strval(
            $this
                ->getFileByLines()
                ->mapWithKeys(fn ($line) => [
                    Str::of($line)->match('/Card[ ]+([0-9]+):/')->toString() => collect(
                        array_intersect(
                            Str::of($line)
                                ->match('/: (.+) \|/')
                                ->matchAll('/([0-9]+)/')
                                ->map(fn ($number) => intval($number))
                                ->toArray(),
                            Str::of($line)
                                ->match('/\| (.+)$/')
                                ->matchAll('/([0-9]+)/')
                                ->map(fn ($number) => intval($number))
                                ->toArray(),
                        )
                    )
                    ->reduce(fn ($total, $count) => ($total === 0 ? 1 : $total + $total), 0),
                ])
                ->sum()
        );
    }

    public function solvePuzzlePartTwo(): string
    {
        return '';
    }
}
