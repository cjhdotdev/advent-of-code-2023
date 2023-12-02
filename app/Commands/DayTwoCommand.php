<?php

declare(strict_types=1);

namespace App\Commands;

use Illuminate\Support\Str;

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
        return strval(
            $this
                ->getFileByLines()
                ->filter()
                ->mapWithKeys(fn ($line) => [
                    Str::of($line)->matchAll('/Game ([0-9]+):/')->first() => Str::of($line)->matchAll('/: (.+)/')->first(),
                ])
                ->reduce(fn ($total, $gameStr, $gameId) => $total + ($this->allLooksValid($gameStr) ? intval($gameId) : 0), 0)
        );
    }

    public function solvePuzzlePartTwo(): string
    {
        return '';
    }

    private function allLooksValid(string $gameString): bool
    {
        return Str::of($gameString)
            ->explode('; ')
            ->reject(fn ($game) => $this->isLookInvalid($game))
            ->isEmpty();
    }

    private function isLookInvalid(string $look): bool
    {
        return Str::of($look)
            ->explode(', ')
            ->filter(fn ($cubes) => (
                collect(array_combine(
                    Str::of($cubes)->matchAll('/(red|green|blue)/')->toArray(),
                    Str::of($cubes)->matchAll('/([0-9]+)/')->toArray(),
                ))
                    ->reject(fn ($count, $colour) => $this->hasTooManyForColour($colour, $count))
                    ->isEmpty()
            ))
            ->isEmpty();
    }

    private function hasTooManyForColour(string $colour, string $count): bool
    {
        return match ($colour) {
            'red' => intval($count) > 12,
            'green' => intval($count) > 13,
            'blue' => intval($count) > 14,
            default => true,
        };
    }
}
