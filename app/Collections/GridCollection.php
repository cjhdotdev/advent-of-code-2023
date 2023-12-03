<?php

declare(strict_types=1);

namespace App\Collections;

use Illuminate\Support\Collection;

class GridCollection extends Collection
{
    public function getByCoordinate(int $x, int $y): string
    {
        return strval(
            $this->get($this->getKey($x, $y))
        );
    }

    public function setByCoordinate(int $x, int $y, string $value): void
    {
        $this->put(
            $this->getKey($x, $y),
            $value
        );
    }

    public function getAllSurroundingCoordinates(int $x, int $y): Collection
    {
        return new GridCollection([
            $this->getKey($x - 1, $y - 1) => $this->getByCoordinate($x - 1, $y - 1),
            $this->getKey($x, $y - 1) => $this->getByCoordinate($x, $y - 1),
            $this->getKey($x + 1, $y - 1) => $this->getByCoordinate($x + 1, $y - 1),
            $this->getKey($x - 1, $y) => $this->getByCoordinate($x - 1, $y),
            $this->getKey($x + 1, $y) => $this->getByCoordinate($x + 1, $y),
            $this->getKey($x - 1, $y + 1) => $this->getByCoordinate($x - 1, $y + 1),
            $this->getKey($x, $y + 1) => $this->getByCoordinate($x, $y + 1),
            $this->getKey($x + 1, $y + 1) => $this->getByCoordinate($x + 1, $y + 1),
        ]);
    }

    private function getKey(int $x, int $y): string
    {
        return sprintf('%d-%d', $x, $y);
    }
}
