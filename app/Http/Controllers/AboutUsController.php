<?php

namespace App\Http\Controllers;

use App\Models\AboutUs;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class AboutUsController extends Controller
{
    public function index()
    {
        $aboutUs = AboutUs::first();
        if (!$aboutUs) {
            $aboutUs = AboutUs::create([]);
        }
        return view('tenant.admin.landing.quienes-somos.index', compact('aboutUs'));
    }

    public function data()
    {
        $aboutUs = AboutUs::select([
            'id',
            'main_title',
            'main_title_active',
            'button_text',
            'button_active',
            'card_color',
            'button_color',
            'updated_at'
        ]);

        return DataTables::of($aboutUs)
            ->addColumn('colors', function ($aboutUs) {
                return '<span style="background-color:'.$aboutUs->card_color.'; padding:5px 10px; color:#fff;">Card</span> '.
                       '<span style="background-color:'.$aboutUs->button_color.'; padding:5px 10px; color:#fff;">Button</span>';
            })
            ->addColumn('actions', function ($aboutUs) {
                return '<a href="'.route('landing.quienes-somos.edit', $aboutUs->id).'" class="btn btn-primary btn-sm">Edit</a>';
            })
            ->rawColumns(['colors', 'actions'])
            ->make(true);
    }

    public function edit(Request $request, $id = null)
    {
        $id = $id ?? $request->input('id');
        $aboutUs = AboutUs::findOrFail($id);
        return view('tenant.admin.landing.quienes-somos.edit', compact('aboutUs'));
    }

    public function update(Request $request, $id)
    {
        $aboutUs = AboutUs::findOrFail($id);
        $booleanFields = [
            'top_text_active',
            'main_title_active',
            'secondary_text_active',
            'tertiary_text_active',
            'button_active',
        ];

        $data = $request->all();

        foreach ($booleanFields as $field) {
            $data[$field] = isset($data[$field]) && $data[$field] == 1 ? 1 : 0;
        }

        $aboutUs->update($data);

        return redirect()->route('landing.quienes-somos.index')->with('success', 'Se actualizó Correctamente.');
    }
}