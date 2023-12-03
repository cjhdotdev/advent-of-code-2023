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
        $grid = $this->getFileAsGrid();

        return strval(
            $this
                ->getFileByLines()
                ->map(fn ($line, $x) => $this->findAdjacentNumbers($line, $x, $grid))
                ->flatten()
                ->sum()
        );
    }

    public function solvePuzzlePartTwo(): string
    {
        return strval(
            ''
        );
    }

    private function findAdjacentNumbers(string $line, int $y, GridCollection $grid): Collection
    {
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
}
