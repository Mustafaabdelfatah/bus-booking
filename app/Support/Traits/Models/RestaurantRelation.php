<?php

namespace App\Support\Traits\Models;


use Domain\Restaurant\Models\Restaurant;

trait RestaurantRelation
{
    public function restaurant(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Restaurant::class, 'restaurant_id', 'id');
    }
}
