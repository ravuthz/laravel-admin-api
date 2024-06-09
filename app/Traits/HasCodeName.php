<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasCodeName
{
    public static function generateCode(string $name): array
    {
        return [
            'code' => Str::of($name)->slug(),
            'name' => strtolower($name)
        ];
    }

    public static function saveByName($name): static
    {
        return static::updateOrCreate(self::generateCode($name));
    }
}
