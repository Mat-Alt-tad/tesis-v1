@extends('layouts.app')

@section('title', 'Cambiar Contraseña')

@section('content')

<div class="container" style="max-width:600px;margin:40px auto;padding:0 20px">

  <div class="card" style="padding:30px;border-radius:12px;background:#fff;box-shadow:0 4px 20px rgba(0,0,0,.1)">
    <h2 style="margin:0 0 20px;text-align:center;color:#2e7d32;font-family:'Lora',serif">
      <i class="fas fa-key"></i> Cambiar Contraseña
    </h2>

    @if(session('success'))
      <div style="background:#e8f5e8;border:1px solid #4caf50;color:#2e7d32;padding:12px;border-radius:8px;margin-bottom:20px;text-align:center">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
      </div>
    @endif

    @if($errors->any())
      <div style="background:#ffebee;border:1px solid #f44336;color:#c62828;padding:12px;border-radius:8px;margin-bottom:20px">
        <ul style="margin:0;padding-left:20px">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('perfil.change-password') }}">
      @csrf @method('PATCH')

      <div style="margin-bottom:20px">
        <label style="display:block;margin-bottom:8px;font-weight:600;color:#333">Contraseña Actual *</label>
        <input type="password" name="current_password" required
               style="width:100%;padding:12px;border:1px solid #ddd;border-radius:8px;font-size:16px">
      </div>

      <div style="margin-bottom:20px">
        <label style="display:block;margin-bottom:8px;font-weight:600;color:#333">Nueva Contraseña *</label>
        <input type="password" name="password" required
               style="width:100%;padding:12px;border:1px solid #ddd;border-radius:8px;font-size:16px">
      </div>

      <div style="margin-bottom:30px">
        <label style="display:block;margin-bottom:8px;font-weight:600;color:#333">Confirmar Nueva Contraseña *</label>
        <input type="password" name="password_confirmation" required
               style="width:100%;padding:12px;border:1px solid #ddd;border-radius:8px;font-size:16px">
      </div>

      <div style="text-align:center">
        <button type="submit" style="background:#2e7d32;color:#fff;border:none;padding:12px 30px;border-radius:8px;font-size:16px;cursor:pointer">
          <i class="fas fa-save"></i> Cambiar Contraseña
        </button>
      </div>
    </form>
  </div>

</div>

@endsection