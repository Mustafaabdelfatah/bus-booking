<?php

namespace App\Filters\Global;

use Closure;

class PhoneFilter
{
    public function handle($request, Closure $next)
    {
        $query = $next($request);

        $query->when(request()->has('search') && !empty(request('search')),
                fn($q) => $q->Where('phone','like', '%' . request('search') . '%')
            );

        return $query;
    }
}
