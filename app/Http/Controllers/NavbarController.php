<?php

namespace App\Http\Controllers;

use App\Models\Navbar;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class NavbarController extends Controller
{
    public function index()
{
    $navbar = Navbar::first(); // Asegúrate de que esta fila exista
    return view('tenant.admin.landing.navbar.index', compact('navbar'));
}

    public function data()
    {
        $navbars = Navbar::select([
            'id',
            'reservations',
            'reservations_active',
            'schedule',
            'schedule_active',
            'email',
            'email_active',
            'address',
            'address_active',
            'services',
            'services_active',
            'about_us',
            'about_us_active',
            'contact_us',
            'contact_us_active',
            'background_color_1',
            'background_color_2',
            'button_1',
            'button_color_1',
            'button_1_active',
            'button_2',
            'button_color_2',
            'button_2_active',
            'text_color_1',
            'text_color_2',
        ]);

        return DataTables::of($navbars)
            ->addColumn('background_colors', function ($navbar) {
                return '<span style="background-color:' . $navbar->background_color_1 . '; padding:5px 10px; color:#fff;">1</span> ' .
                       '<span style="background-color:' . $navbar->background_color_2 . '; padding:5px 10px; color:#fff;">2</span>';
            })
            ->addColumn('buttons', function ($navbar) {
                return $navbar->button_1 . ' <span style="background-color:' . $navbar->button_color_1 . '; padding:5px 10px; color:#fff;">1</span><br>' .
                       $navbar->button_2 . ' <span style="background-color:' . $navbar->button_color_2 . '; padding:5px 10px; color:#fff;">2</span>';
            })
            ->addColumn('actions', function ($navbar) {
                return '<a href="' . route('navbar.edit', $navbar->id) . '" class="btn btn-primary btn-sm">Editar</a>';
            })
            ->rawColumns(['background_colors', 'buttons', 'actions'])
            ->make(true);
    }

    public function edit(Navbar $navbar)
    {
        return view('tenant.admin.landing.navbar.edit', compact('navbar'));
    }

    public function update(Request $request, Navbar $navbar)
    {
        // Lista de campos booleanos
        $booleanFields = [
            'reservations_active',
            'schedule_active',
            'email_active',
            'address_active',
            'services_active',
            'about_us_active',
            'contact_us_active',
            'button_1_active',
            'button_2_active',
        ];

        $data = $request->all();

        // Forzar los booleanos a valores 0 o 1
        foreach ($booleanFields as $field) {
            $data[$field] = isset($data[$field]) && $data[$field] == 1 ? 1 : 0;
        }

        $navbar->update($data);

        return redirect()->route('landing.navbar.index')->with('success', 'Navbar actualizada correctamente.');
    }


}
