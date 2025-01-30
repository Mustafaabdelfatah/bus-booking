<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Client extends Authenticatable
{
    use HasFactory, HasApiTokens , HasTranslations;

    public bool $inPermission = true;
    public $translatable = ['name'];

    protected $fillable = [
        'name',
        'mother_name',
        'passport_number',
        'birth_date',
        'issue_date',
        'expiry_date',
        'phone',
        'passport_image',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'issue_date' => 'date',
        'expiry_date' => 'date',
    ];

}