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

        // Si el usuario es admin, no aplicar ningún filtro
        if ($user->hasRole('SuperAdmin')) {
            return;
        }

        // Obtener el nombre de la columna que debe usarse para filtrar
        $column = property_exists($model, 'branchOfficeColumn')
            ? $model->branchOfficeColumn
            : 'id_branch_office'; // default

        $builder->where($model->getTable() . '.' . $column, $user->id_branch_office);
    }
}
