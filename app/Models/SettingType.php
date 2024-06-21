<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SettingType extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function settings()
    {
        return $this->hasMany(Setting::class);
    }

    public static function data($key = null, $value = null, $other = [])
    {
        if ($key) {
            $slug = Str::slug($key);

            if (isset($value)) {
                return static::updateOrCreate(
                    [
                        'code' => Str::slug($slug),
                    ],
                    [
                        'name' => $key,
                        'description' => $value,
                        ...$other
                    ]
                );
            }
            return static::where('code', $slug)->first();
        }
        return static::get();
    }
}
