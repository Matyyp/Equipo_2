<?php

namespace App\Http\Controllers\Tenant\Maintainers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BranchOffice;
use App\Models\Location;
use App\Models\Business;
use App\Models\Region;
class BranchOfficeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $office = BranchOffice::with([
            'branch_office_location.location_region',
            'branch_office_business'
        ])->get();
        
        $office = $office->map(function ($branch) {
            $location = optional($branch->branch_office_location);
            $region   = optional($location->location_region);
            $business = optional($branch->branch_office_business);
        
            return [
                'id'                   => $branch->id_branch,
                'schedule'             => $branch->schedule,
                'street'               => $branch->street,
                'name_branch_offices' => $branch->name_branch_offices,
                'region'               => $region->name_region ?? 'N/D',
                'commune'              => $location->commune ?? 'N/D',
                'business'             => $business->name_business ?? 'N/D',
            ];
        });
        
        return view('tenant.admin.maintainer.branch_office.index', [
            'data' => $office
        ]);
        
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $locacion = Location::all();
        $region = Region::all();
        $business = Business::all();

        return view('tenant.admin.maintainer.branch_office.create', compact('locacion', 'business', 'region'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'schedule'    => 'required|string|max:100',
            'street'      => 'required|string|max:150',
            'name_branch_offices'      => 'required|string|max:250',
            'id_location' => 'required|exists:locations,id_location',
            'id_business' => 'required|exists:businesses,id_business',
        ]);

        BranchOffice::create([
            'schedule'    => $request->schedule,
            'street'      => $request->street,
            'name_branch_offices' => $request->name_branch_offices,
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
        $request->validate([
            'schedule'    => 'required|string|max:100',
            'street'      => 'required|string|max:150',
            'name_branch_offices'      => 'required|string|max:150',
            'id_location' => 'required|exists:locations,id_location',
            'id_business' => 'required|exists:businesses,id_business',
        ]);

        BranchOffice::where('id_branch', $id)
        ->update([
            'schedule'    => $request->schedule,
            'street'      => $request->street,
            'name_branch_offices' => $request->name_branch_offices,
            'id_location' => $request->id_location,
            'id_business' => $request->id_business,
        ]);

        return redirect()->route('sucursales.index');
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
