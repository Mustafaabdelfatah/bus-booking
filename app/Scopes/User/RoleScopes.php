<?php

namespace App\Scopes\User;

use Illuminate\Database\Eloquent\Builder;

trait RoleScopes
{
    public function scopeApplyFilterForView(Builder $builder): void
    {
        if ($user = auth()->user()) {
            $builder->when(!$user->can('view-all-role'), function ($subQuery) use ($user) {
                $subQuery->where('created_by', $user->id);
            });
        }
    }

    public function scopeExcludeRoot(Builder $query): Builder
    {
        return $query->where('name', '!=', 'root');
    }
}
