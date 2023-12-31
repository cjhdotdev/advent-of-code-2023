<?php

declare(strict_types=1);

namespace App\Commands;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class DayTwoCommand extends BaseCommand
{
    protected $signature = 'day:02';

    protected $description = 'Day 2';

    private const int MAX_RED = 12;

    private const int MAX_GREEN = 13;

    private const int MAX_BLUE = 14;

    public function inputFile(): string
    {
        return 'two.txt';
    }

    public function solvePuzzlePartOne(): string
    {
        return strval(
            $this
                ->getGameCollection()
                ->reduce(fn ($total, $games, $gameId) => $total + ($this->allLooksValid($games) ? intval($gameId) : 0))
        );
    }

    public function solvePuzzlePartTwo(): string
    {
        return strval(
            $this
                ->getGameCollection()
                ->reduce(fn ($total, $games) => $total + ($this->findPowerOfFewestCubes($games)))
        );
    }

    private function getGameCollection(): Collection
    {
        return $this
            ->getFileByLines()
            ->filter()
            ->mapWithKeys(fn ($line) => [
                Str::of($line)->matchAll('/Game ([0-9]+):/')->first() => Str::of($line)->matchAll('/: (.+)/')->first(),
            ]);
    }

    private function allLooksValid(string $games): bool
    {
        return Str::of($games)
            ->explode('; ')
            ->reject(fn ($game) => (
                Str::of($game)
                    ->explode(', ')
                    ->filter(fn ($cubes) => (
                        $this
                            ->getCubeCollection($cubes)
                            ->reject(fn ($count, $colour) => $this->hasTooManyForColour($colour, $count))
                            ->isEmpty()
                    ))
                    ->isEmpty()
            ))
            ->isEmpty();
    }

    private function getCubeCollection(string $cubes): Collection
    {
        return collect(array_combine(
            Str::of($cubes)->matchAll('/(red|green|blue)/')->toArray(),
            Str::of($cubes)->matchAll('/([0-9]+)/')->toArray(),
        ));
    }

    private function findPowerOfFewestCubes(string $games): int
    {
        return intval(
            Str::of($games)
                ->explode('; ')
                ->reduce(fn ($cubeCount, $game) => (
                    Str::of($game)
                        ->explode(', ')
                        ->reduce(fn ($cubeCount, $cubes) => (
                            $this
                                ->getCubeCollection($cubes)
                                ->reduce(fn ($cubeCount, $count, $colour) => (
                                    $cubeCount->put($colour, max($cubeCount->get($colour), $count))
                                ), $cubeCount)
                        ), $cubeCount)
                ), collect())
                ->reduce(fn ($total, $count) => $total * $count, 1)
        );
    }

    private function hasTooManyForColour(string $colour, string $count): bool
    {
        return match ($colour) {
            'red' => intval($count) > self::MAX_RED,
            'green' => intval($count) > self::MAX_GREEN,
            'blue' => intval($count) > self::MAX_BLUE,
            default => true,
        };
    }
}
