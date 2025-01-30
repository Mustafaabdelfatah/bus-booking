<?php

namespace App\Support\Traits\Models\Payment;


use App\Support\Enum\PaymentStatusEnum;
use Domain\Payment\Models\Payment;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasPayment
{
    public function payment(): MorphOne
    {
        return $this->morphOne(Payment::class, 'payable');
    }


    public function payments(): MorphMany
    {
        return $this->morphMany(Payment::class, 'payable')->latest();
    }

    public function successPayment(): MorphOne
    {
        return $this->morphOne(Payment::class, 'payable')->where('status', PaymentStatusEnum::PAID)->latest();
    }
}
