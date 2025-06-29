<?php

namespace App\Http\Controllers\Tenant\Maintainers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Owner;
use App\Models\Belong;

class OwnerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $owner = Owner::all();
        return view('tenant.admin.maintainer.owner.index', compact('owner'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tenant.admin.maintainer.owner.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:100',
            'number_phone'  => 'required|string|max:20',
        ]);
    
        Owner::create([
            'name'          => $request->name,
            'number_phone'  => $request->number_phone,
        ]);

        return redirect()->route('dueños.index');
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
        $owner = Owner::where('id_owner', $id)->first();
        return view('tenant.admin.maintainer.owner.edit', compact('owner'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name'          => 'required|string|max:100',
            'number_phone'  => 'required|string|max:20',
        ]);

        Owner::where('id_owner', $id)
        ->update([
            'name'          => $request->name,
            'number_phone'  => $request->number_phone,
        ]);

        return redirect()->route('dueños.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Owner::where('id_owner', $id)->delete();
        return redirect()->route('dueños.index');
    }
}
