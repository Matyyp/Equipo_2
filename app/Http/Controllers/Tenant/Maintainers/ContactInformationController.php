<?php

namespace App\Http\Controllers\Tenant\Maintainers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContactInformation;

class ContactInformationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = ContactInformation::all();
        return view('tenant.contactInformation.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tenant.contactInformation.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'type_contact' => 'required|string|max:100',
            'data_contact' => 'required|string|max:255',
        ]);
    
        ContactInformation::create([
            'type_contact' => $request->type_contact,
            'data_contact' => $request->data_contact,
        ]);

        return redirect()->route('informacion_contacto.index');
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
        $data = ContactInformation::where('id_contact_information', $id)->first();
        return view('tenant.contactInformation.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'type_contact' => 'required|string|max:100',
            'data_contact' => 'required|string|max:255',
        ]);

        ContactInformation::where('id_contact_information', $id)
        ->update([
            'type_contact' => $request->type_contact,
            'data_contact' => $request->data_contact,
        ]);

        return redirect()->route('informacion_contacto.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        ContactInformation::where('id_contact_information', $id)->delete();
        return redirect()->route('informacion_contacto.index');
    }
}
