<?php

namespace App\Models\Scopes;

use App\Models\Application;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ApplicationGlobalScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        if (! \Auth::hasUser()) {
            return;
        }

        if (auth()->user()->hasPermissionTo(config('atc.access.permission.restrict_view'))) {
            $builder->where('user_id', auth()->user()->id);
        } else {
            $builder
                ->where('status', '<>', Application::DRAFT)
                ->orWhere(function ($query) {
                    $query->where('user_id', auth()->user()->id)
                        ->where('status', Application::DRAFT);
                });
        }

        if (auth()->user()->hasPermissionTo(config('atc.access.permission.view_discussion'))) {
            $builder->with('comments');
        }

        if (auth()->user()->hasPermissionTo(config('atc.access.permission.view_vote'))) {
            $builder->with('votes');
        }
    }
}
