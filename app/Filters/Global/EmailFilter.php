<?php

namespace App\Filters\Global;

use Closure;

class EmailFilter
{
    public function handle($request, Closure $next)
    {
        $query = $next($request);

        $query->when(request()->has('search') && !empty(request('search')),
                fn($q) => $q->Where('email','like', '%' . request('search') . '%')
            );

        return $query;
    }
}