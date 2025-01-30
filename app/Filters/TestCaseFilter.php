<?php

namespace App\Filters;

use Closure;

class TestCaseFilter
{
    public function handle($query, Closure $next)
    {
        $request = request();

        // Apply project filter
        if ($request->has('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        // Apply employee filter if no project is specified
//        if (!$request->has('project_id')) {
//            $query->where('employee_id', auth()->guard('employee')->id());
//        }

        // Apply task_id filter
        if ($request->has('task_id')) {
            $query->where('task_id', $request->task_id);
        }

        // Apply status filter
        if ($request->has('test_case_status_filter')) {
            $query->where('status', $request->test_case_status_filter);
        }

        return $next($query);
    }
}
