<?php

namespace App\Http\Controllers\Tenant\Maintainers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ModelCar;

class ModelcarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Modelcar = ModelCar::all();
        return view('tenant.admin.maintainer.modelcar.index', compact('Modelcar'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tenant.admin.maintainer.modelcar.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name_model' => 'required|string|max:255',

        ]);

        ModelCar::create([
            'name_model' => $request->name_model,

        ]);

        return redirect()->route('modelo.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $Modelcar = ModelCar::where('id_model', $id)->first();
        return view('tenant.admin.maintainer.Modelcar.edit', compact('Modelcar'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name_model' => 'required|string|max:255'

        ]);

        ModelCar::where('id_model', $id)
        ->update([
            'name_model' => $request->name_model
        ]);

        return redirect()->route('modelo.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        ModelCar::where('id_model', $id)->delete();
        return redirect()->route('modelo.index');
    }
}
