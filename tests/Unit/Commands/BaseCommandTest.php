<?php

declare(strict_types=1);

namespace Tests\Unit\Commands;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

it('reads a file into a collection of lines', function () {
    Storage::fake('local');
    Storage::put('filepath', 'string1'.PHP_EOL.'string2');
    $testClass = testClass();

    expect($testClass->getFileByLines())
        ->toBeInstanceOf(Collection::class)
        ->toHaveCount(2)
        ->toEqual(collect(['string1', 'string2']));
});
