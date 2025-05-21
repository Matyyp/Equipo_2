<?php

namespace App\Http\Controllers;

use App\Models\Footer;
use Illuminate\Http\Request;

class FooterController extends Controller
{
    public function index()
    {
        $footers = Footer::all();
        return view('tenant.admin.landing.footer.index', compact('footers'));
    }

    public function edit(Footer $footer)
    {
        return view('tenant.admin.landing.footer.edit', compact('footer'));
    }

    public function update(Request $request, Footer $footer)
    {
        $request->validate([
            'copyright' => 'required|string|max:255',
            'contact_text' => 'nullable|string',
            'contact_active' => 'required|boolean',
            'social_text' => 'nullable|string',
            'social_active' => 'required|boolean',
            'background_color' => 'required|string',
            'text_color_1' => 'required|string',
            'text_color_2' => 'required|string',
        ]);

        $footer->update($request->all());

        return redirect()->route('landing.footer.index')->with('success', 'Footer actualizado correctamente.');
    }
}
