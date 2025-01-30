<?php

namespace App\Filters;

use Closure;

class BugFilter
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

        // Apply task filter
        if ($request->has('task_id')) {
            $query->where('task_id', $request->task_id);
        }

        // Apply test case filter
        if ($request->has('test_case_id')) {
            $query->where('test_case_id', $request->test_case_id);
        }

        // Apply bug status filter
        if ($request->has('bug_status_filter')) {
            $query->where('status', $request->bug_status_filter);
        }

        return $next($query);
    }
}