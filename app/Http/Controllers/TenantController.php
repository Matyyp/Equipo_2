<?php

namespace App\Http\Controllers;
use App\Jobs\SetupTenantJob;
use App\Models\Tenant;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    public function index()
    {
        $tenants = Tenant::all();
        return view('central.tenants.index', compact('tenants'));
    }

    public function create()
    {
        return view('central.tenants.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id'     => 'required|alpha_dash|unique:tenants,id',
            'domain' => 'required|unique:domains,domain',
            'email'  => 'required|email',
        ]);

        $tenant = Tenant::create(['id' => $data['id']]);
        $tenant->domains()->create(['domain' => $data['domain']]);

        // Preparamos la respuesta
        $response = redirect()
            ->route('tenants.index')
            ->with('success','Cliente creado. Correo enviado al cliente.');

        SetupTenantJob::dispatchAfterResponse($tenant, $data['email']);

        return $response;
    }

    public function edit(Tenant $tenant)
    {
        return view('central.tenants.edit', compact('tenant'));
    }

    public function update(Request $request, Tenant $tenant)
    {
        $request->validate([
            'domain' => 'required|unique:domains,domain,' . $tenant->domains->first()->id . ',id',
        ]);

        $tenant->domains()->update([
            'domain' => $request->domain,
        ]);

        return redirect()
            ->route('central.tenants.index')
            ->with('success', 'Cliente actualizado correctamente.');
    }

    public function destroy(Tenant $tenant)
    {
        $tenant->delete();

        return redirect()
            ->route('tenants.index')
            ->with('success', 'Cliente eliminado correctamente.');
    }
}
