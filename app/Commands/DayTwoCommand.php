<?php

declare(strict_types=1);

namespace App\Commands;

use Illuminate\Support\Collection;
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
                ->getGameCollection()
                ->reduce(fn ($total, $gameStr, $gameId) => $total + ($this->allLooksValid($gameStr) ? intval($gameId) : 0))
        );
    }

    public function solvePuzzlePartTwo(): string
    {
        return strval(
            $this
                ->getGameCollection()
                ->reduce(fn ($total, $gameStr, $gameId) => $total + ($this->findPowerOfFewestCubes($gameStr)))
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

    private function allLooksValid(string $gameString): bool
    {
        return Str::of($gameString)
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

    private function findPowerOfFewestCubes(string $gameString): int
    {
        return intval(
            Str::of($gameString)
                ->explode('; ')
                ->reduce(fn ($cubeCount, $cubes) => (
                    Str::of($cubes)
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
            'red' => intval($count) > 12,
            'green' => intval($count) > 13,
            'blue' => intval($count) > 14,
            default => true,
        };
    }
}
