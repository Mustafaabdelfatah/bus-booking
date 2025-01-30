<?php

namespace App\Support\Traits\Models;


use App\Support\Scopes\RestaurantScope;

trait BelongsToRestaurant
{
    protected static function bootBelongsToRestaurant(): void
    {

        //get only vendors by default
        static::addGlobalScope(new RestaurantScope);
        static::creating(function ($model) {
            if (auth()->guard('restaurant-api')->user() && auth()->guard('restaurant-api')->check()) {
                $model->restaurant_id = auth()->guard('restaurant-api')->id();
            }

        });

        static::updating(function ($model) {
            if (auth()->guard('restaurant-api')->user() && auth()->guard('restaurant-api')->check()) {
                $model->restaurant_id = auth()->guard('restaurant-api')->id();
            }

        });

    }

}
