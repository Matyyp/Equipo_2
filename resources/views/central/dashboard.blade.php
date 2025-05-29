@extends('central.layouts.app')

@section('title', 'Dashboard Central')
@section('page_title')
<div class="position-relative mb-4" style="max-width: max-content;">
  <h1 class="h3 fw-bold mb-0 text-dark">Resumen de Clientes</h1>
  <span class="position-absolute bottom-0 start-0 w-100" style="
    height: 4px;
    background: linear-gradient(90deg, #1e3c72 0%, #2a5298 100%);
    border-radius: 2px;
    opacity: 0.7;
  "></span>
</div>
@endsection

@section('content')
<div class="container-fluid">
  <!-- Resumen General -->
  <div class="row mb-4">
    <div class="col-md-4">
      <div class="info-box bg-gradient-primary shadow-lg" style="border: 1px solid rgba(255,255,255,0.2);">
        <span class="info-box-icon"><i class="fas fa-building"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Clientes Totales</span>
          <span class="info-box-number">{{ count($tenantData) }}</span>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="info-box bg-gradient-success shadow-lg" style="border: 1px solid rgba(255,255,255,0.2);">
        <span class="info-box-icon"><i class="fas fa-users"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Usuarios Totales</span>
          <span class="info-box-number">{{ array_sum(array_column($tenantData, 'user_count')) }}</span>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="info-box bg-gradient-info shadow-lg" style="border: 1px solid rgba(255,255,255,0.2);">
        <span class="info-box-icon"><i class="fas fa-globe"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Dominios Activos</span>
          <span class="info-box-number">{{ count(array_filter($tenantData, fn($t) => $t['domain'] !== 'Sin dominio')) }}</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Tarjetas de Clientes -->
  <div class="row">
    @foreach($tenantData as $tenant)
    <div class="col-lg-4 col-md-6 mb-4">
      <div class="card tenant-card h-100" style="border: 1px solid rgba(0,0,0,0.08);">
        <div class="card-body">
          <div class="d-flex align-items-start">
            <!-- Avatar del Tenant -->
            <div class="tenant-avatar" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); box-shadow: 0 4px 8px rgba(30, 60, 114, 0.3);">
              {{ strtoupper(substr($tenant['id'], 0, 1)) }}
            </div>
            
            <!-- Información del Tenant -->
            <div class="tenant-info ml-3 flex-grow-1">
              <h5 class="tenant-name mb-1">{{ $tenant['id'] }}</h5>
              <p class="tenant-domain mb-2">
                @if($tenant['domain'] !== 'Sin dominio')
                <a href="http://{{ $tenant['domain'] }}" target="_blank" class="text-primary" style="text-decoration: none;">
                  <i class="fas fa-external-link-alt mr-1"></i>
                  {{ $tenant['domain'] }}
                </a>
                @else
                <span class="text-muted">Sin dominio configurado</span>
                @endif
              </p>
              <div class="tenant-meta d-flex">
                <span class="badge badge-light mr-2" style="background: rgba(0,0,0,0.05); color: #495057;">
                  <i class="fas fa-id-badge mr-1"></i> {{ $tenant['id'] }}
                </span>
              </div>
            </div>
            
            <!-- Contador de Usuarios -->
            <div class="user-counter">
              <div class="counter-circle" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); box-shadow: 0 4px 8px rgba(30, 60, 114, 0.2);">
                <span>{{ $tenant['user_count'] }}</span>
              </div>
              <small class="text-muted">Usuarios</small>
            </div>
          </div>
        </div>
        <div class="card-footer bg-white border-top-0 pt-0">
          <div class="d-flex justify-content-between">
            <span class="text-muted small">
              <i class="far fa-clock mr-1"></i> 
              {{-- Aquí podrías mostrar la fecha de creación si la tienes --}}
            </span>
          </div>
        </div>
      </div>
    </div>
    @endforeach
  </div>
</div>
@endsection

@push('styles')
<style>
  /* Estilos personalizados para las tarjetas */
  .tenant-card {
    border-radius: 12px;
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.08);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    overflow: hidden;
    background: #fff;
  }
  
  .tenant-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.12);
  }
  
  .tenant-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    font-weight: bold;
    color: #fff;
  }
  
  .tenant-name {
    font-weight: 600;
    color: #2d3748;
    letter-spacing: 0.2px;
  }
  
  .tenant-domain {
    font-size: 0.9rem;
    color: #6c757d;
  }
  
  .user-counter {
    text-align: center;
    margin-left: auto;
    padding-left: 15px;
  }
  
  .counter-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    margin: 0 auto;
  }
  
  .tenant-actions .btn {
    border-radius: 20px;
    padding: 0.25rem 0.75rem;
    transition: all 0.3s;
  }
  
  .tenant-actions .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
  }
  
  .info-box {
    border-radius: 12px;
    color: white;
    overflow: hidden;
    transition: transform 0.3s;
  }
  
  .info-box:hover {
    transform: translateY(-5px);
  }
  
  .info-box-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
    text-shadow: 0 2px 4px rgba(0,0,0,0.1);
  }
  
  .info-box-content {
    padding: 15px;
  }
  
  .info-box-text {
    font-size: 0.9rem;
    opacity: 0.9;
  }
  
  .info-box-number {
    font-size: 1.8rem;
    font-weight: bold;
    margin-top: 5px;
  }
  
</style>
@endpush