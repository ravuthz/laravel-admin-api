<?php

namespace App\Models;

use App\Traits\HasCodeName;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Permission extends Model
{
    use HasFactory;
    use HasCodeName;
    protected $guarded = ['id'];
    public function roles()
    {
        return $this->belongsToMany(
            Role::class,
            'role_permissions',
            'role_id',
            'permission_id'
        );
//        return $this->belongsTo(Role::class, 'role_id');
    }

    public static function getResourceKeys($key = null)
    {
        return $key ?? ['index', 'show', 'store', 'update', 'destroy'];
    }

    public static function saveResource($group, $permissions = null)
    {
        $result = [];
        $keys = self::getResourceKeys($permissions);

        foreach ($keys as $key) {
            $result[] = static::updateOrCreate([
                'code' => strtolower("$group.$key"),
                'name' => Str::of("$group $key")->title()
            ]);
        }

        return collect($result);
    }
}
