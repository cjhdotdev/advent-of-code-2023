<?php

declare(strict_types=1);

namespace App\Commands;

use App\Collections\GridCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

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
            $this
                ->getFileByLines()
                ->map(fn ($line, $y) => $this->findAdjacentNumbers($line, $y))
                ->flatten()
                ->sum()
        );
    }

    public function solvePuzzlePartTwo(): string
    {
        return strval(
            $this
                ->getFileByLines()
                ->map(fn ($line, $y) => $this->findGearPairs($line, $y))
                ->flatten()
                ->sum()
        );
    }

    private function findGearPairs(string $line, int $y): Collection
    {
        static $seenKeys = collect();
        $grid = $this->getFileAsGrid();
        $gearPairs = collect();

        Str::of($line)
            ->matchAll('/./')
            ->filter(fn ($char) => $this->isGear($char))
            ->each(fn ($char, $x) => (
                $gearPairs->push(
                    $grid
                        ->getAllSurroundingCoordinates($x, $y)
                        ->reject(fn ($char, $key) => ! $this->isDigit($char) || $seenKeys->search($key))
                        ->each(fn ($char, $key) => $seenKeys->push($key))
                        ->reduce(
                            function (Collection $foundNumbers, string $char, string $key) use ($grid) {
                                [$foundKeys, $foundNumber] = $this->findNumber($grid, $key);

                                return $foundNumbers->put(
                                    $foundKeys,
                                    $foundNumber,
                                );
                            },
                            collect(),
                        )
                        ->pipe(
                            fn (Collection $collect) => (
                                $collect->count() === 2
                                    ? $collect->reduce(fn ($total, $number) => $total * intval($number), 1)
                                    : 0
                            )
                        )
                )
            ));

        return $gearPairs;
    }

    /**
     * @return array<int, string>
     */
    private function findNumber(GridCollection $grid, string $key): array
    {
        [$x, $y] = $grid->getXYFromKey($key);
        $number = $grid->getByCoordinate($x, $y);
        $foundKey = $key;
        $left = $right = $x;
        while (($char = $grid->getByCoordinate(--$left, $y)) !== '' && $this->isDigit($char)) {
            $number = sprintf('%s%s', $char, $number);
            $foundKey = sprintf('%s-%s/%s', $left, $y, $foundKey);
        }
        while (($char = $grid->getByCoordinate(++$right, $y)) !== '' && $this->isDigit($char)) {
            $number = sprintf('%s%s', $number, $char);
            $foundKey = sprintf('%s/%s-%s', $foundKey, $right, $y);
        }

        return [$foundKey, $number];
    }

    private function findAdjacentNumbers(string $line, int $y): Collection
    {
        $grid = $this->getFileAsGrid();
        $adjacentNumbers = collect();
        $surrounding = collect();
        $finalNumber = Str::of($line)
            ->matchAll('/./')
            ->reduce(function ($currentNumber, $char, $x) use (&$adjacentNumbers, &$surrounding, $grid, $y) {
                if (! empty($currentNumber) && ! $this->isDigit($char) && ! $surrounding->isEmpty()) {
                    $adjacentNumbers->push($currentNumber);
                }
                if (! $this->isDigit($char)) {
                    $surrounding = collect();

                    return '';
                } else {
                    $surrounding = $surrounding->merge(
                        $grid->getAllSurroundingCoordinates($x, $y)->reject(
                            fn ($char) => $this->isEmpty($char) || $this->isDigit($char)
                        )
                    );
                }

                return $currentNumber.$char;
            });

        if (! empty($finalNumber) && ! $surrounding->isEmpty()) {
            $adjacentNumbers->push($finalNumber);
        }

        return $adjacentNumbers;
    }

    private function isDigit(string $character): bool
    {
        return Str::of($character)->isMatch('/[0-9]/');
    }

    private function isEmpty(string $character): bool
    {
        return empty($character) || $character === '.';
    }

    private function isGear(string $character): bool
    {
        return $character === '*';
    }
}
