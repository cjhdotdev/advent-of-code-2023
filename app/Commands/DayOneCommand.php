<?php

declare(strict_types=1);

namespace App\Commands;

use Illuminate\Support\Str;

class DayOneCommand extends BaseCommand
{
    /** @var array<string, string> */
    private array $replacers = [
        'one' => 'o1e',
        'two' => 't2o',
        'three' => 't3e',
        'four' => 'f4r',
        'five' => 'f5e',
        'six' => 's6x',
        'seven' => 's7n',
        'eight' => 'e8t',
        'nine' => 'n9e',
    ];

    protected $signature = 'day:01';

    protected $description = 'Day 1';

    public function inputFile(): string
    {
        return 'one.txt';
    }

    public function solvePuzzlePartOne(): string
    {
        return strval(
            $this
                ->getFileByLines()
                ->filter()
                ->reduce(fn ($total, $line) => (
                    $total + $this->findNumber($line)
                ), 0)
        );
    }

    public function solvePuzzlePartTwo(): string
    {
        return strval(
            $this
                ->getFileByLines()
                ->filter()
                ->map(fn ($line) => (
                    str_replace(
                        array_keys($this->replacers),
                        array_values($this->replacers),
                        $line,
                    )
                ))
                ->reduce(fn ($total, $line) => (
                    $total + $this->findNumber($line)
                ))
        );
    }

    private function findNumber(string $line): int
    {
        $number = intval(
            sprintf(
                '%s%s',
                $this->findFirstNumber($line),
                $this->findLastNumber($line),
            )
        );

        return $number;
    }

    private function findFirstNumber(string $line): string
    {
        return strval(
            Str::of($line)->matchAll('/[0-9]/')->first()
        );
    }

    private function findLastNumber(string $line): string
    {
        return strval(
            Str::of($line)->matchAll('/[0-9]/')->last()
        );
    }
}
