<?php

namespace App\Filters\Global;

use Closure;

class NameFilter
{
    public function handle($request, Closure $next)
    {
        $query = $next($request);

        $query->when(request()->has('search') && !empty(request('search')),
                fn($q) => $q->Where('name','like', '%' . request('search') . '%')
            );

        return $query;
    }
}
