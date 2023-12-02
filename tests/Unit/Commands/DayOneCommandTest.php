<?php

namespace Tests\Unit\Commands;

use App\Commands\DayOneCommand;

it('solves part one of the puzzle', function () {
    $mock = mock(DayOneCommand::class)
        ->makePartial()
        ->shouldReceive('getFileByLines')
        ->andReturn(collect([
            '1abc2',
            'pqr3stu8vwx',
            'a1b2c3d4e5f',
            'treb7uchet',
        ]))
        ->getMock();

    expect($mock->solvePuzzlePartOne())
        ->toBeString()
        ->toEqual('142');
});

it('solves part two of the puzzle', function () {
    $mock = mock(DayOneCommand::class)
        ->makePartial()
        ->shouldReceive('getFileByLines')
        ->andReturn(collect([
            'two1nine',
            'eightwothree',
            'abcone2threexyz',
            'xtwone3four',
            '4nineeightseven2',
            'zoneight234',
            '7pqrstsixteen',
        ]))
        ->getMock();

    expect($mock->solvePuzzlePartTwo())
        ->toBeString()
        ->toEqual('281');
});
