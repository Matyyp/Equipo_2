<?php

namespace App\Http\Controllers\Tenant\Maintainers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Region;
use Yajra\DataTables\Facades\DataTables;

class RegionController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $regions = Region::select(['id', 'name_region']);
    
            return DataTables::of($regions)
                ->addColumn('action', function ($region) {
                    return '
                        <a href="' . route('region.edit', $region->id) . '" class="btn btn-sm btn-warning me-1" title="Editar">
                            <i class="fas fa-edit"></i>
                        </a>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    
        return view('tenant.admin.maintainer.region.index');
    }

    public function create()
    {
        return view('tenant.admin.maintainer.region.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name_region' => 'required|string|max:255|unique:regions,name_region',
        ]);

        Region::create([
            'name_region' => $request->name_region,
        ]);

        return redirect()->route('region.index')->with('success', 'Región registrada correctamente.');
    }

    public function edit(Region $region)
    {
        return view('tenant.admin.maintainer.region.edit', compact('region'));
    }

    public function update(Request $request, Region $region)
    {
        $request->validate([
            'name_region' => 'required|string|max:255|unique:regions,name_region,' . $region->id,
        ]);

        $region->update([
            'name_region' => $request->name_region,
        ]);

        return redirect()->route('region.index')->with('success', 'Región actualizada correctamente.');
    }

    public function destroy(Region $region)
    {
        $region->delete();
        return redirect()->route('region.index')->with('success', 'Región actualizada correctamente.');
    }
}
