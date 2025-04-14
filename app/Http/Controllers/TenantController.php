<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    public function index()
    {
        return view('central.index', ['tenants' => Tenant::all()]);
    }

    public function create()
    {
        return view('central.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'id' => 'required|unique:tenants',
            'domain' => 'required|unique:domains,domain',
        ]);

        $tenant = Tenant::create(['id' => $request->id]);
        $tenant->domains()->create(['domain' => $request->domain]);

        return redirect()->route('tenants.index')->with('success', 'Tenant creado correctamente.');
    }

    public function edit(Tenant $tenant)
    {
        return view('central.edit', compact('tenant'));
    }

    public function update(Request $request, Tenant $tenant)
    {
        $request->validate([
            'domain' => 'required|unique:domains,domain,' . $tenant->id . ',tenant_id',
        ]);

        $tenant->domains()->update(['domain' => $request->domain]);

        return redirect()->route('tenants.index')->with('success', 'Tenant actualizado.');
    }

    public function destroy(Tenant $tenant)
    {
        $tenant->delete();

        return redirect()->route('tenants.index')->with('success', 'Tenant eliminado.');
    }
}
