<?php

declare(strict_types=1);

namespace App\Commands;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use LaravelZero\Framework\Commands\Command;

abstract class BaseCommand extends Command
{
    protected string $fileContents;

    abstract public function inputFile(): string;

    abstract public function solvePuzzlePartOne(): string;

    abstract public function solvePuzzlePartTwo(): string;

    public function handle(): int
    {
        $this->output->note('The result for part 1 was: '.$this->solvePuzzlePartOne());
        $this->output->note('The result for part 2 was: '.$this->solvePuzzlePartTwo());

        return self::SUCCESS;
    }

    /**
     * @return Collection<string>
     */
    public function getFile(): Collection
    {
        if (empty($this->fileContents)) {
            $this->prepareFile();
        }

        return collect([$this->fileContents]);
    }

    /**
     * @return Collection<string>
     */
    public function getFileByLines(): Collection
    {
        return $this->getFileByDelimiter(PHP_EOL);
    }

    /**
     * @return Collection<string>
     */
    public function getFileByDelimiter(string $delimiter): Collection
    {
        if (empty($this->fileContents)) {
            $this->prepareFile();
        }

        return
            ! empty($this->fileContents)
                ? Str::of($this->fileContents)->explode($delimiter)
                : collect();
    }

    private function prepareFile(): void
    {
        $this->fileContents = Storage::disk('local')->get($this->inputFile()) ?? '';
    }
}
