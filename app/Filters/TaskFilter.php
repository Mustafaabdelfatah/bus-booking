<?php

namespace App\Filters;

use Closure;

class TaskFilter
{
    public function handle($query, Closure $next)
    {
        $request = request();

        // Apply project filter
        if ($request->has('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        // Apply employee filter if no project is specified
        if (!$request->has('project_id')) {
            $query->where('employee_id', auth()->guard('employee')->id());
        }

        // Apply project_phase filter
        if ($request->has('project_phase_id')) {
            $query->where('project_phase_id', $request->project_phase_id);
        }

        // Apply status filter
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        return $next($query);
    }
}