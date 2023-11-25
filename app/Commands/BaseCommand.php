<?php

declare(strict_types=1);

namespace App\Commands;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use LaravelZero\Framework\Commands\Command;

abstract class BaseCommand extends Command
{
    /** @var array<string, string> */
    protected array $fileContents;

    /**
     * @return array<string, string>
     */
    abstract public function inputFiles(): array;

    public function getFile(string $filename): Collection
    {
        if (empty($this->fileContents)) {
            $this->prepareFiles();
        }

        return collect([$this->fileContents[$filename]]);
    }

    public function getFileByLines(string $filename): Collection
    {
        return $this->getFileByDelimiter($filename, PHP_EOL);
    }

    public function getFileByDelimiter(string $filename, string $delimiter): Collection
    {
        if (empty($this->fileContents)) {
            $this->prepareFiles();
        }

        return
            ! empty($this->fileContents[$filename])
                ? Str::of($this->fileContents[$filename])->explode($delimiter)
                : collect();
    }

    private function prepareFiles(): void
    {
        foreach ($this->inputFiles() as $filename => $filepath) {
            $this->prepareFile($filename, $filepath);
        }
    }

    private function prepareFile(string $filename, string $filepath): void
    {
        $this->fileContents[$filename] = Storage::get($filepath) ?? '';
    }
}
