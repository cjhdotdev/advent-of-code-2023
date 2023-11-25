<?php

declare(strict_types=1);

namespace Tests\Unit\Commands;

use App\Commands\BaseCommand;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

it('reads a file into a collection of lines', function () {
    Storage::fake('local');
    Storage::put('filepath', 'string1'.PHP_EOL.'string2');
    $testClass = new class extends BaseCommand
    {
        public function inputFiles(): array
        {
            return ['filename' => 'filepath'];
        }
    };

    expect($testClass->getFileByLines('filename'))
        ->toBeInstanceOf(Collection::class)
        ->toHaveCount(2)
        ->toEqual(collect(['string1', 'string2']));
});
