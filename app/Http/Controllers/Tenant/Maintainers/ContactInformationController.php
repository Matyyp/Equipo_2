<?php

namespace App\Http\Controllers\Tenant\Maintainers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContactInformation;
use App\Models\BranchOffice;

class ContactInformationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($branchId)
    {
        return view('tenant.admin.maintainer.contactInformation.create', compact('branchId'));
    }
    
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'type_contact' => 'required|string|max:100',
            'data_contact' => 'required|string|max:255',
            'id_branch_office' => 'required|exists:branch_offices,id_branch',
        ]);
    
        ContactInformation::create([
            'type_contact'      => $request->type_contact,
            'data_contact'      => $request->data_contact,
            'id_branch_office'  => $request->id_branch_office,
        ]);
    
        return redirect()->route('informacion_contacto.show', $request->id_branch_office);
    }
    

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = ContactInformation::where('id_branch_office', $id)->get();
        $branch = BranchOffice::findOrFail($id); // <- Obtener datos de la sucursal
        return view('tenant.admin.maintainer.contactInformation.index', compact('data', 'branch'));
    }
    
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = ContactInformation::where('id_contact_information', $id)->first();
        return view('tenant.admin.maintainer.contactInformation.edit', compact('data'));
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
