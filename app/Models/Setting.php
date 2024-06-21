<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Setting extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'options' => 'json',
    ];

    public function type()
    {
        return $this->belongsTo(SettingType::class);
    }

    public static function data(string $key = null, $value = null, $other = [])
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
                        'value' => $value,
                        ...$other
                    ]
                );
            }
            return static::where('code', $slug)->first();
        }
        return static::get();
    }

}
