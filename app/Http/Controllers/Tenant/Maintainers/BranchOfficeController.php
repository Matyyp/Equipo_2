<?php

namespace App\Http\Controllers\Tenant\Maintainers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BranchOffice;
use App\Models\Location;
use App\Models\Business;
class BranchOfficeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $office = BranchOffice::with(['branch_office_location', 'branch_office_business'])->get();

        $office = $office->map(function ($branch) {
            return [
                'id'          => $branch->id_branch,
                'schedule'    => $branch->schedule,
                'street'      => $branch->street,
                'region'      => $branch->branch_office_location->region ?? 'N/D',
                'commune'     => $branch->branch_office_location->commune ?? 'N/D',
                'business'    => $branch->branch_office_business->name_business ?? 'N/D',
            ];
        });

        return view('tenant.branch_office.index', [
            'data' => $office
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $locacion = Location::all();
        $business = Business::all();

        return view('tenant.admin.maintainer.branch_office.create', compact('locacion', 'business'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'schedule'    => 'required|string|max:100',
            'street'      => 'required|string|max:150',
            'id_location' => 'required|exists:locations,id_location',
            'id_business' => 'required|exists:businesses,id_business',
        ]);

        BranchOffice::create([
            'schedule'    => $request->schedule,
            'street'      => $request->street,
            'id_location' => $request->id_location,
            'id_business' => $request->id_business,
        ]);

        return redirect()->route('sucursales.index');
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
    public function edit($id)
    {
        $branch = BranchOffice::where('id_branch', $id)
            ->with(['branch_office_location', 'branch_office_business'])
            ->firstOrFail();

        $locacion = Location::all();
        $business = Business::all();

        return view('tenant.admin.maintainer.branch_office.edit', compact('branch', 'locacion', 'business'));
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        BranchOffice::where('id_branch', $id)->delete();
        return redirect()->route('sucursales.index');

    }
}
