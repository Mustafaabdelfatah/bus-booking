<?php

namespace App\Models;

use App\Enums\EmployeeStatusEnum;
use Laravel\Sanctum\HasApiTokens;
use App\Services\Global\UploadService;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Notifications\UserPasswordResetNotification;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Authenticatable
{
    use HasFactory, HasApiTokens , HasRoles;

    protected string $guard_name = 'sanctum';

    public bool $inPermission = true;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'avatar',
        'status',
        'position_id',
        'password',
    ];

    protected $hidden = ['password'];


    /*
    |--------------------------------------------------------------------------
    | Casts && Set Custom Attributes
    |--------------------------------------------------------------------------
    */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function avatar(): Attribute
    {
        return Attribute::make(get: static fn($value) => UploadService::url($value));
    }

    protected function password(): Attribute
    {
        return Attribute::make(set: static fn($value) => bcrypt($value));
    }

    /*
    |--------------------------------------------------------------------------
    | Helper methods
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | Relations methods
    |--------------------------------------------------------------------------
    */



    public function creator(): BelongsTo
    {
        return $this->belongsTo(__CLASS__, 'created_by');
    }


}