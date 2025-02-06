<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Seat extends Model
{
    use HasFactory;
    public bool $inPermission = true;

    protected $fillable = ['bus_id', 'seat_number', 'is_reserved'];

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }
}