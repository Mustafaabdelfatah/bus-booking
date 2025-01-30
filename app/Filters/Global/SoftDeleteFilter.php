<?php

namespace App\Filters\Global;

use Carbon\Carbon;
use Closure;

class SoftDeleteFilter
{
    public function handle($request, Closure $next)
    {
        $query = $next($request);
        $isTrashed = filter_var(request('isTrashed'), FILTER_VALIDATE_BOOLEAN);

        if ($isTrashed) {
            $query->withTrashed();
        } else {
            $query->onlyTrashed();
        }
        return $query;


    }
}
