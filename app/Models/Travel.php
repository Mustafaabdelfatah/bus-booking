<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Travel extends Model
{
    use HasFactory;
    public bool $inPermission = true;

    protected $casts = [
        'departure_time' => 'datetime',
    ];

    protected $table = 'travel';

    protected $fillable = [
        'bus_id', 'departure_station', 'arrival_station',
        'departure_time', 'available_seats', 'ticket_price'
    ];

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }
}
