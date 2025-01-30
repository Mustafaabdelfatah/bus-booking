<?php

namespace App\Models;

use Spatie\Permission\Models\Permission as SpatiePermission;
use Spatie\Translatable\HasTranslations;

class Permission extends SpatiePermission
{
    use HasTranslations;

    public array $translatable = ['display_name'];

    public bool $inPermission = true;

    protected $fillable = [
        'name', 'guard_name', 'display_name', 'group'
    ];
}
