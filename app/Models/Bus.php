<?php

namespace App\Models;

use App\Models\Travel;
use Illuminate\Database\Eloquent\Model;

class Bus extends Model
{
    public bool $inPermission = true;
    protected $fillable = ['number', 'capacity', 'type'];

    public function travels()
    {
        return $this->hasMany(Travel::class);
    }
}