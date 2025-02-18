<?php

namespace App\Filters\Global;

use Closure;

class NameFilter
{
    public function handle($request, Closure $next)
    {
        $query = $next($request);


        // // Apply name filter if 'search' parameter is provided
        // if ($request->has('search') && !empty($request->input('search'))) {
        //     $query->where('name', 'like', '%' . $request->input('search') . '%');
        // }

        // // Apply passport filter if 'passport' parameter is provided
        // if ($request->has('passport_number') && !empty($request->input('passport_number'))) {
        //     $query->where('passport_number', $request->input('passport_number'));
        //     // }

            // return $query;
        // }
    }
}