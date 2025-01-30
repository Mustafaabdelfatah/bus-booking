<?php

namespace App\Models;

use App\Scopes\User\RoleScopes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Permission\Models\Role as SpatieRole;
use Spatie\Translatable\HasTranslations;

class Role extends SpatieRole
{
    use RoleScopes, HasTranslations;


    public array $translatable = ['display_name'];

    public bool $inPermission = true;

    protected $fillable = [
        'name', 'guard_name', 'display_name'
    ];

    /*
    |--------------------------------------------------------------------------
    | Relations methods
    |--------------------------------------------------------------------------
    */

}
