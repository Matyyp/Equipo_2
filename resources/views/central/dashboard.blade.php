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
<div class="container-fluid px-2 px-md-3">
  <!-- Resumen General -->
  <div class="row mb-4">
    <div class="col-12 col-sm-6 col-md-4 mb-3 mb-md-0">
      <div class="info-box bg-gradient-primary shadow-lg h-100" style="border: 1px solid rgba(255,255,255,0.2);">
        <span class="info-box-icon"><i class="fas fa-building"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Clientes Totales</span>
          <span class="info-box-number">{{ count($tenantData) }}</span>
        </div>
      </div>
    </div>
    <div class="col-12 col-sm-6 col-md-4 mb-3 mb-md-0">
      <div class="info-box bg-gradient-success shadow-lg h-100" style="border: 1px solid rgba(255,255,255,0.2);">
        <span class="info-box-icon"><i class="fas fa-users"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Usuarios Totales</span>
          <span class="info-box-number">{{ array_sum(array_column($tenantData, 'user_count')) }}</span>
        </div>
      </div>
    </div>
    <div class="col-12 col-md-4">
      <div class="info-box bg-gradient-info shadow-lg h-100" style="border: 1px solid rgba(255,255,255,0.2);">
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
    <div class="col-12 col-sm-6 col-lg-4 col-xl-3 mb-4">
      <div class="card tenant-card h-100" style="border: 1px solid rgba(0,0,0,0.08);">
        <div class="card-body p-3">
          <div class="d-flex align-items-start">
            <!-- Avatar del Tenant -->
            <div class="tenant-avatar" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); box-shadow: 0 4px 8px rgba(30, 60, 114, 0.3);">
              {{ strtoupper(substr($tenant['id'], 0, 1)) }}
            </div>
            
            <!-- Información del Tenant -->
            <div class="tenant-info ml-2 ml-sm-3 flex-grow-1" style="min-width: 0;">
              <h5 class="tenant-name mb-1 text-truncate">{{ $tenant['id'] }}</h5>
              <p class="tenant-domain mb-2 text-truncate">
                @if($tenant['domain'] !== 'Sin dominio')
                <a href="http://{{ $tenant['domain'] }}" target="_blank" class="text-primary" style="text-decoration: none;">
                  <i class="fas fa-external-link-alt mr-1"></i>
                  <span class="text-truncate d-inline-block" style="max-width: 120px;">{{ $tenant['domain'] }}</span>
                </a>
                @else
                <span class="text-muted">Sin dominio configurado</span>
                @endif
              </p>
              <div class="tenant-meta d-flex flex-wrap">
                <span class="badge badge-light mr-2 mb-1" style="background: rgba(0,0,0,0.05); color: #495057;">
                  <i class="fas fa-id-badge mr-1"></i> {{ $tenant['id'] }}
                </span>
              </div>
            </div>
            
            <!-- Contador de Usuarios -->
            <div class="user-counter ml-2">
              <div class="counter-circle" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); box-shadow: 0 4px 8px rgba(30, 60, 114, 0.2);">
                <span>{{ $tenant['user_count'] }}</span>
              </div>
              <small class="text-muted d-none d-sm-block">Usuarios</small>
              <small class="text-muted d-block d-sm-none"><i class="fas fa-users"></i></small>
            </div>
          </div>
        </div>
        <div class="card-footer bg-white border-top-0 pt-0 px-3 pb-2">
          <div class="d-flex justify-content-between">
            <span class="text-muted small">
              <i class="far fa-clock mr-1"></i> 
              {{-- Aquí podrías mostrar la fecha de creación si la tienes --}}
            </span>
            <div class="tenant-actions">
              <button class="btn btn-sm btn-outline-primary">
                <i class="fas fa-eye"></i>
                <span class="d-none d-sm-inline">Ver</span>
              </button>
            </div>
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
  /* Estilos base */
  .tenant-card {
    border-radius: 12px;
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.08);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    overflow: hidden;
    background: #fff;
  }
  
  .tenant-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.12);
  }
  
  .tenant-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    font-weight: bold;
    color: #fff;
    flex-shrink: 0;
  }
  
  .tenant-name {
    font-weight: 600;
    color: #2d3748;
    letter-spacing: 0.2px;
    font-size: 1rem;
  }
  
  .tenant-domain {
    font-size: 0.85rem;
    color: #6c757d;
  }
  
  .user-counter {
    text-align: center;
    flex-shrink: 0;
  }
  
  .counter-circle {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    margin: 0 auto;
    font-size: 0.9rem;
  }
  
  .tenant-actions .btn {
    border-radius: 20px;
    padding: 0.25rem 0.5rem;
    font-size: 0.8rem;
    transition: all 0.3s;
  }
  
  .info-box {
    border-radius: 10px;
    color: white;
    overflow: hidden;
    transition: transform 0.3s;
    min-height: 90px;
  }
  
  .info-box-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    padding: 0 15px;
  }
  
  .info-box-content {
    padding: 10px;
  }
  
  .info-box-text {
    font-size: 0.85rem;
    opacity: 0.9;
    margin-bottom: 0.25rem;
  }
  
  .info-box-number {
    font-size: 1.5rem;
    font-weight: bold;
  }

  /* Media Queries para responsividad */
  @media (max-width: 1024px) {
    .container-fluid {
      padding-left: 10px;
      padding-right: 10px;
    }
    
    .info-box {
      min-height: 80px;
    }
    
    .info-box-icon {
      font-size: 1.3rem;
      padding: 0 10px;
    }
    
    .info-box-number {
      font-size: 1.3rem;
    }
    
    .tenant-card {
      border-radius: 10px;
    }
    
    .tenant-avatar {
      width: 36px;
      height: 36px;
      font-size: 1.1rem;
    }
    
    .counter-circle {
      width: 32px;
      height: 32px;
    }
  }

  @media (max-width: 768px) {
    .info-box-icon {
      font-size: 1.2rem;
    }
    
    .info-box-number {
      font-size: 1.2rem;
    }
    
    .tenant-name {
      font-size: 0.95rem;
    }
    
    .tenant-domain {
      font-size: 0.8rem;
    }
    
    .card-body {
      padding: 1rem;
    }
  }

  @media (max-width: 576px) {
    .col-sm-6 {
      padding-left: 6px;
      padding-right: 6px;
    }
    
    .info-box {
      min-height: 70px;
    }
    
    .info-box-icon {
      font-size: 1rem;
      padding: 0 8px;
    }
    
    .info-box-text {
      font-size: 0.8rem;
    }
    
    .info-box-number {
      font-size: 1.1rem;
    }
    
    .tenant-card {
      border-radius: 8px;
    }
    
    .tenant-avatar {
      width: 32px;
      height: 32px;
      font-size: 1rem;
    }
    
    .counter-circle {
      width: 28px;
      height: 28px;
      font-size: 0.8rem;
    }
    
    .tenant-actions .btn {
      padding: 0.2rem 0.4rem;
      font-size: 0.75rem;
    }
  }

  @media (max-width: 400px) {
    .tenant-info {
      margin-left: 8px;
    }
    
    .tenant-name {
      font-size: 0.9rem;
    }
    
    .tenant-domain {
      font-size: 0.75rem;
    }
    
    .badge {
      font-size: 0.7rem;
      padding: 0.25em 0.4em;
    }
  }
</style>
@endpush