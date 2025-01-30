<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    public $inPermission = true;

    protected $fillable = ['key', 'value', 'group', 'is_editable', 'is_env'];

    public $appends = [
        'full_url'
    ];

    public function getValueAttribute($value)
    {
        if ($value && file_exists('storage/' . $value)) {
            return null;
        }
        return $value ?? null;
    }

    public function getFullUrlAttribute()
    {
        if ($this->attributes['value'] && file_exists('storage/' . $this->attributes['value'])) {
            return url('storage/' . $this->attributes['value']);
        }
        return null;
    }
}
