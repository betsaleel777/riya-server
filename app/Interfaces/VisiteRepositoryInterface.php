<?php

namespace App\Interfaces;

use App\Models\Visite;

interface VisiteRepositoryInterface
{
    public function emitBailProcess(int | Visite $rental): Visite;
    public static function amout(): int;
    public static function amoutDateFilter(string $date): int;
    public static function dashboard(): array;
}
