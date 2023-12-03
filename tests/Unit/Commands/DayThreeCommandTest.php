<?php

declare(strict_types=1);

use App\Commands\DayThreeCommand;

it('solves part one of the puzzle', function () {
    $mock = mock(DayThreeCommand::class)
        ->makePartial()
        ->shouldReceive('getFileByLines')
        ->andReturn(collect([

        ]))
        ->getMock();

    expect($mock->solvePuzzlePartOne())
        ->toBeString()
        ->toEqual('');
});

it('solves part two of the puzzle', function () {
    $mock = mock(DayThreeCommand::class)
        ->makePartial()
        ->shouldReceive('getFileByLines')
        ->andReturn(collect([

        ]))
        ->getMock();

    expect($mock->solvePuzzlePartTwo())
        ->toBeString()
        ->toEqual('');
});
