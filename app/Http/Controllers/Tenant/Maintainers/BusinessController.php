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
        $data = Business::all();
        return view('tenant.admin.maintainer.business.index', compact('data'));
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
        $request->validate([
            'name_business' => 'required|string|max:255',
            'electronic_transfer' => 'required|string|max:255',
            'logo' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $tenantId = tenant()->id;
        $domain = request()->getHost();
        $filename = $request->file('logo')->hashName();

        Storage::disk('tenants_public_shared')->putFileAs(
            "{$domain}/imagenes",
            $request->file('logo'),
            $filename
        );


        business::create([
            'name_business' => $request->name_business,
            'logo' => $filename, 
            'electronic_transfer' => $request->electronic_transfer, 
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
            'name_business' => 'required|string',
            'logo' => 'image', 
            'electronic_transfer' => 'required|string',  
        ]);
        if($request->logo){

            $path = $request->file('logo')->store('logos', 'tenant_public');
            $foto_url = $path;

            business::where('id_business', $id)
            ->update([
                'name_business' => $request->name_business,
                'logo' => $foto_url, 
                'electronic_transfer' => $request->electronic_transfer, 
            ]);

        }else{

            business::where('id_business', $id)
            ->update([
                'name_business' => $request->name_business,
                'electronic_transfer' => $request->electronic_transfer, 
            ]);
        }

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
