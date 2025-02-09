<?php

namespace App\Models;

use App\Enums\SeatTypeEnum;
use App\Enums\SeatStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Seat extends Model
{
    use HasFactory;
    public bool $inPermission = true;

    protected $fillable = ['bus_id', 'seat_number', 'type', 'status'];

    protected $casts = [
        'type' => SeatTypeEnum::class,
        'status' => SeatStatusEnum::class,
    ];
    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }
}
