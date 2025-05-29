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
            'funds'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Guardar el logo
        $logoPath = $request->file('logo')->store('logos', 'public');

        // Guardar el fondo si se subió
        $fundsPath = $request->hasFile('funds')
            ? $request->file('funds')->store('backgrounds', 'public')
            : null;

        // Crear empresa
        $empresa = Business::create([
            'name_business' => $data['name_business'],
            'logo'          => $logoPath,
            'funds'         => $fundsPath, 
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
            'funds' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3000',
        ], [
            'name_business.required' => 'El nombre de la empresa es obligatorio.',
            'name_business.string' => 'El nombre de la empresa debe ser una cadena de texto.',
            'name_business.max' => 'El nombre de la empresa no debe superar los 255 caracteres.',

            'logo.image' => 'El archivo del logo debe ser una imagen.',
            'logo.mimes' => 'El logo debe ser un archivo de tipo: jpg, jpeg, png o webp.',
            'logo.max' => 'El logo no debe ser mayor a 2MB.',

            'funds.image' => 'El fondo debe ser una imagen.',
            'funds.mimes' => 'El fondo debe ser un archivo de tipo: jpg, jpeg, png o webp.',
            'funds.max' => 'El fondo no debe ser mayor a 3MB.',
        ]);

        $business = Business::findOrFail($id);

        $data = [
            'name_business' => $request->name_business,
        ];

        // Si subieron nuevo logo
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            $data['logo'] = $path;
        }

        // Si subieron nuevo fondo (imagen de login)
        if ($request->hasFile('funds')) {
            $fundsPath = $request->file('funds')->store('backgrounds', 'public');
            $data['funds'] = $fundsPath;
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