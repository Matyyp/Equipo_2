<?php

namespace App\Http\Controllers\Tenant\Maintainers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Location;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Location::all();
        return view('tenant.admin.maintainer.location.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tenant.admin.maintainer.location.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'region' => 'required|string|max:255',
            'commune' => 'required|string|max:255',

        ]);

        location::create([
            'region' => $request->region,
            'commune' => $request->commune, 
        ]);

        return redirect()->route('locacion.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $location = location::where('id_location', $id)->first();
        return view('tenant.admin.maintainer.location.edit', compact('location'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'region' => 'required|string|max:255',
            'commune' => 'required|string|max:255',

        ]);

        location::where('id_location', $id)
        ->update([
            'region' => $request->commune,
            'commune' => $request->region, 
        ]);

        return redirect()->route('locacion.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        location::where('id_location', $id)->delete();
        return redirect()->route('locacion.index');
    }
}
