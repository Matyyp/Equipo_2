<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class BranchOfficeScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        if (!Auth::check()) {
            return;
        }

        $user = Auth::user();

        // Siempre filtrar por status si el modelo es BranchOffice
        if ($model instanceof \App\Models\BranchOffice) {
            $builder->where($model->getTable() . '.status', 'active');
        }

        // Si el usuario no es SuperAdmin, filtrar por sucursal
        if (!$user->hasRole('SuperAdmin')) {
            $column = property_exists($model, 'branchOfficeColumn')
                ? $model->branchOfficeColumn
                : 'id_branch_office';

            $builder->where($model->getTable() . '.' . $column, $user->id_branch_office);
        }
    }

}

