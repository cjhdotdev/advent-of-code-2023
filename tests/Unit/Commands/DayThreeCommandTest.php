<?php

declare(strict_types=1);

use App\Commands\DayThreeCommand;

it('solves part one of the puzzle', function () {
    $mock = mock(DayThreeCommand::class)
        ->makePartial()
        ->shouldReceive('getFileByLines')
        ->andReturn(collect([
            '467..114..',
            '...*......',
            '..35..633.',
            '......#...',
            '617*......',
            '.....+.58.',
            '..592.....',
            '......755.',
            '...$.*....',
            '.664.598..',
        ]))
        ->getMock();

    expect($mock->solvePuzzlePartOne())
        ->toBeString()
        ->toEqual('4361');
});

it('solves part two of the puzzle', function () {
    $mock = mock(DayThreeCommand::class)
        ->makePartial()
        ->shouldReceive('getFileByLines')
        ->andReturn(collect([
            '467..114..',
            '...*......',
            '..35..633.',
            '......#...',
            '617*......',
            '.....+.58.',
            '..592.....',
            '......755.',
            '...$.*....',
            '.664.598..',
        ]))
        ->getMock();

    expect($mock->solvePuzzlePartTwo())
        ->toBeString()
        ->toEqual('');
});
