<?php

namespace App\Filters\Global;

use Closure;

class TitleFilter
{
    public function handle($request, Closure $next)
    {
        $query = $next($request);

        $query->when(request()->has('search') && !empty(request('search')),
                fn($q) => $q->Where('title','like', '%' . request('search') . '%')
            );

        return $query;
    }
}