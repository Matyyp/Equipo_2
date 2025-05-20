<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    /**
     * Muestra el formulario de edición de ajustes de correo y nombre de empresa.
     */
    public function edit()
    {
        // Obtener valores actuales de la tabla settings
        $companyEmail = Setting::get('company_email');
        $companyName  = Setting::get('company_name');

        return view('central.settings.edit', compact('companyEmail', 'companyName'));
    }

    /**
     * Guarda los cambios realizados en los ajustes.
     */
    public function update(Request $request)
    {
        // Validación de los campos
        $data = $request->validate([
            'company_email' => 'required|email',
            'company_name'  => 'required|string|max:255',
        ]);

        // Actualizar o crear los ajustes en la tabla settings
        Setting::set('company_email', $data['company_email']);
        Setting::set('company_name',  $data['company_name']);

        return back()->with('success', 'Ajustes guardados correctamente.');
    }
}
