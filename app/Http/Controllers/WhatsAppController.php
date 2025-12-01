<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WhatsAppController extends Controller
{
     public function dashboard()
    {
        $status = Http::get('http://www.rentacarencoyhaique2:3000/status')->json();

        $qr = null;
        if(!$status['conectado']) {
            $qr = Http::get('http://www.rentacarencoyhaique2/qr')->json();
        }

        return view('admin.whatsapp-dashboard', [
            'status' => $status,
            'qr' => $qr
        ]);
    }

    public function logout()
    {
        Http::post('http://www.rentacarencoyhaique2/logout');

        return redirect()->route('whatsapp.dashboard')
                         ->with('msg', 'Sesión cerrada. Escanee el nuevo QR.');
    }

    public function send(Request $request)
    {
        $request->validate([
            'numero' => 'required',
            'archivo' => 'required',
        ]);

        $response = Http::post('http://www.rentacarencoyhaique2/send-pdf', [
            'numero' => $request->numero,
            'archivo' => $request->archivo,
        ])->json();

        return back()->with('msg', $response);
    }
}
