<?php

namespace App\Http\Controllers\Tenant\Maintainers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Business;

class BusinessController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $business = Business::with('business_bank.bank_detail_bank', 'business_bank.bank_detail_type_account')->first();
        return view('tenant.admin.maintainer.business.index', compact('business'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tenant.admin.maintainer.business.create');
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        $data = $request->validate([
            'name_business' => 'required|string|max:255',
            'logo'          => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);


        $path = $request->file('logo')->store('logos', 'public');


        $empresa = Business::create([
            'name_business' => $data['name_business'],
            'logo'          => $path, 
        ]);

        return redirect()->route('empresa.index');
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
        $business = business::where('id_business', $id)->first();
        return view('tenant.admin.maintainer.business.edit', compact('business'));
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name_business' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $business = Business::findOrFail($id);

        $data = [
            'name_business' => $request->name_business,
        ];

        if ($request->hasFile('logo')) {
            // Guardar en carpeta 'logos' usando el disco 'public' (ya tenantizado por Tenancy)
            $path = $request->file('logo')->store('logos', 'public');
            $data['logo'] = $path; // Ej: logos/archivo.webp
        }

        $business->update($data);

        return redirect()->route('empresa.index');
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        business::where('id_business', $id)->delete();
        return redirect()->route('empresa.index');
    }
}