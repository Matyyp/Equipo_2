<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; // Importante para hacer peticiones

class WhatsappController extends Controller
{
    private $nodeUrl = 'http://localhost:3000';

    /**
     * Muestra la vista principal con el escáner QR.
     */
    public function index()
    {
        return view('tenant.whatsapp.scan'); // Asegúrate de crear esta vista
    }

    /**
     * Obtiene el estado actual (Conectado, Esperando QR, o Apagado).
     * Se llama via AJAX cada 3 segundos desde la vista.
     */
    public function checkStatus()
    {
        try {
            // Petición al endpoint /status de Node.js con timeout corto
            $response = Http::timeout(3)->get($this->nodeUrl . '/status');

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json([
                'connected' => false,
                'qr' => null,
                'error' => 'Node.js respondió con error'
            ]);

        } catch (\Exception $e) {
            // Si Node.js está apagado, entra aquí
            return response()->json([
                'connected' => false,
                'qr' => null,
                'error' => 'No se puede conectar con el servidor Node.js (¿Está corriendo?)'
            ]);
        }
    }

    /**
     * Cierra la sesión de WhatsApp.
     */
    public function disconnect()
    {
        try {
            Http::post($this->nodeUrl . '/logout');
            return response()->json(['success' => true, 'message' => 'Sesión cerrada']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al desconectar']);
        }
    }

    
    // Método para probar el envío
    public function enviarMensajePrueba()
    {
        // 1. Configurar datos
        $numeroDestino = '56979192878'; // Tu número para probar
        $mensaje = 'Hola desde el Controlador de Laravel! 🛠️';

        // 2. Enviar petición al servicio local de Node.js
        try {
            $response = Http::post('http://localhost:3000/send-message', [
                'number' => $numeroDestino,
                'message' => $mensaje,
            ]);

            // 3. Verificar respuesta
            if ($response->successful()) {
                return response()->json([
                    'status' => 'ok',
                    'data' => $response->json()
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Node respondió con error',
                    'detalle' => $response->body()
                ], 500);
            }

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'fail', 
                'message' => 'No se pudo conectar con Node.js',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}