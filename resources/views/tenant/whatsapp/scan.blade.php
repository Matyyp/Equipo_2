@extends('layouts.admin')

@section('content')

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-success text-white text-center">
                    <h4 class="mb-0"><i class="fab fa-whatsapp"></i> Configuración WhatsApp</h4>
                </div>
                <div class="card-body text-center p-5">
                    
                    <div id="loading-state">
                        <div class="spinner-border text-primary" role="status"></div>
                        <p class="mt-2 text-muted">Verificando estado del servidor...</p>
                    </div>

                    <div id="error-state" style="display: none;">
                        <div class="text-danger mb-3"><i class="fas fa-server fa-4x"></i></div>
                        <h5>Servidor Desconectado</h5>
                        <p class="text-muted">No se detecta el proceso de Node.js.</p>
                        <small>Ejecuta <code>node index.js</code> o <code>pm2 start</code> en el servidor.</small>
                    </div>

                    <div id="qr-state" style="display: none;">
                        <h5>Escanea el código QR</h5>
                        <p class="text-muted small">Abre WhatsApp en tu teléfono -> Dispositivos vinculados -> Vincular</p>
                        
                        <div class="d-flex justify-content-center my-4">
                            <div id="canvas-qr" style="border: 10px solid white; box-shadow: 0 0 10px #ccc;"></div>
                        </div>
                        
                        <div class="spinner-grow spinner-grow-sm text-success" role="status"></div>
                        <small class="text-muted">Esperando escaneo...</small>
                    </div>

                    <div id="connected-state" style="display: none;">
                        <div class="text-success mb-4"><i class="fas fa-check-circle fa-5x"></i></div>
                        <h3>¡Sistema Operativo!</h3>
                        <p class="text-muted">WhatsApp está vinculado y listo para enviar mensajes.</p>
                        <hr>
                        <button onclick="logout()" class="btn btn-outline-danger">
                            <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

<script>
    let qrGenerator = null;

    function checkStatus() {
        $.ajax({
            url: "{{ route('whatsapp.status') }}",
            type: "GET",
            success: function(response) {
                $('#loading-state').hide();

                // 1. Error en Node.js
                if (response.error) {
                    $('#error-state').show();
                    $('#qr-state').hide();
                    $('#connected-state').hide();
                    return;
                }
                
                $('#error-state').hide();

                // 2. Ya está conectado
                if (response.connected) {
                    $('#connected-state').show();
                    $('#qr-state').hide();
                } 
                // 3. Necesita QR (Node envía el string del QR)
                else if (response.qr) {
                    $('#connected-state').hide();
                    $('#qr-state').show();

                    // Dibujar QR solo si no está dibujado o si cambió
                    var container = document.getElementById("canvas-qr");
                    
                    if (container.innerHTML === "") {
                        // Limpiar y crear nuevo
                        container.innerHTML = "";
                        new QRCode(container, {
                            text: response.qr,
                            width: 200,
                            height: 200
                        });
                    }
                } 
                // 4. Node arrancó pero aún no genera el QR (Cargando...)
                else {
                    $('#loading-state').show();
                }
            },
            error: function() {
                $('#loading-state').hide();
                $('#error-state').show();
            }
        });
    }

    function logout() {
        if(!confirm('¿Estás seguro de desconectar el número?')) return;

        // Efecto visual
        var btn = $('button[onclick="logout()"]');
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Cerrando...');

        $.ajax({
            url: "{{ route('whatsapp.logout') }}",
            type: "GET",  // <--- ¡AQUÍ ESTÁ EL CAMBIO CLAVE! (Antes decía POST)
            success: function(response) {
                alert('Sesión cerrada correctamente.');
                window.location.reload();
            },
            error: function(xhr) {
                console.error("Error:", xhr);
                alert('Error al desconectar.');
                btn.prop('disabled', false).text('Cerrar Sesión');
            }
        });
    }

    // Ejecutar al cargar y luego cada 3 segundos
    $(document).ready(function() {
        checkStatus();
        setInterval(checkStatus, 3000);
    });
</script>
@endpush
