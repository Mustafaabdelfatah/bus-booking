<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reservation extends Model
{
    use HasFactory;
    public bool $inPermission = true;

    protected $fillable = [
        'client_id', 'travel_id', 'passenger_type', 'price', 'is_paid'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function travel()
    {
        return $this->belongsTo(Travel::class);
    }

    public function seat()
{
    return $this->hasOne(Seat::class, 'seat_number', 'seat_number');
}

}
