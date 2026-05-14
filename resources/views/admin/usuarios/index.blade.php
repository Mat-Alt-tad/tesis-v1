@extends('layouts.admin')

@section('title', 'Usuarios')
@section('admin-title', 'Usuarios')

@section('content')

@if(session('success'))
  <div id="flash-success" data-msg="{{ session('success') }}" style="display:none"></div>
@endif

<div class="tbl-card">
  <div class="tbl-hdr">
    <h3>Usuarios</h3>
    <button class="ab-add" onclick="abrirModal('modal-usuario')">
      <i class="fas fa-user-plus"></i> Nuevo
    </button>
  </div>

  <table>
    <thead>
      <tr>
        <th>Nombre</th>
        <th>Rol</th>
        <th>Email</th>
        <th>Estado</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      @forelse($usuarios as $usuario)
        <tr>
          <td><strong>{{ $usuario->name }}</strong></td>
          <td>
            @foreach($usuario->getRoleNames() as $rol)
              <span class="pill {{ $rol === 'admin' ? 'p-admin' : ($rol === 'moderador' ? 'p-mod' : 'p-ok') }}">
                {{ ucfirst($rol) }}
              </span>
            @endforeach
          </td>
          <td>
            <a href="mailto:{{ $usuario->email }}" style="color:var(--verde-mid)">
              {{ $usuario->email }}
            </a>
          </td>
          <td>
            <span class="pill {{ $usuario->activo ? 'p-ok' : 'p-pend' }}">
              {{ $usuario->activo ? 'Activo' : 'Inactivo' }}
            </span>
          </td>
          <td>
            @if($usuario->id !== auth()->id())
              @if(!$usuario->hasRole('admin') || auth()->user()->hasRole('admin'))
                @if($usuario->activo)
                  <form method="POST"
                        action="{{ route('admin.usuarios.destroy', $usuario) }}"
                        style="display:inline"
                        id="form-del-user-{{ $usuario->id }}">
                    @csrf @method('DELETE')
                    <button type="button" class="abtn ab-rej"
                            onclick="alpineConfirm(
                              '¿Desactivar usuario?',
                              '¿Desactivar a &laquo;{{ addslashes($usuario->name) }}&raquo;?',
                              'form-del-user-{{ $usuario->id }}'
                            )">Desactivar</button>
                  </form>
                @else
                  <form method="POST"
                        action="{{ route('admin.usuarios.activar', $usuario) }}"
                        style="display:inline"
                        id="form-act-user-{{ $usuario->id }}">
                    @csrf @method('PATCH')
                    <button type="button" class="abtn ab-ok"
                            onclick="alpineConfirm(
                              '¿Activar usuario?',
                              '¿Activar a &laquo;{{ addslashes($usuario->name) }}&raquo;?',
                              'form-act-user-{{ $usuario->id }}'
                            )">Activar</button>
                  </form>
                @endif
              @endif
            @endif
            <button type="button" class="abtn ab-edit"
                    onclick="abrirModal('modal-password-{{ $usuario->id }}')">
              Cambiar Contraseña
            </button>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="5" style="text-align:center;color:var(--texto-suave);padding:30px">
            No hay usuarios registrados.
          </td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>

{{-- Modal nuevo usuario --}}
<div id="modal-usuario" class="modal-ov">
  <div class="modal">
    <div class="modal-hdr">
      <h3><i class="fas fa-user-plus"></i> Nuevo Usuario</h3>
      <button class="modal-close" onclick="cerrarModal('modal-usuario')"><i class="fas fa-times"></i></button>
    </div>
    <form method="POST" action="{{ route('admin.usuarios.store') }}">
      @csrf
      <div class="modal-body">
        <div class="f-row">
          <div class="f-group">
            <label>Nombre *</label>
            <input type="text" name="name" placeholder="Nombre completo" required>
          </div>
          <div class="f-group">
            <label>Email *</label>
            <input type="email" name="email" placeholder="usuario@email.com" required>
          </div>
        </div>
        <div class="f-row">
          <div class="f-group">
            <label>Rol *</label>
            <select name="rol" required>
              <option value="lector">Lector</option>
              <option value="moderador">Moderador</option>
              <option value="admin">Administrador</option>
            </select>
          </div>
          <div class="f-group">
            <label>Contraseña *</label>
            <input type="password" name="password" placeholder="••••••••" required>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn-cancel" onclick="cerrarModal('modal-usuario')">Cancelar</button>
        <button type="submit" class="btn-submit">
          <i class="fas fa-user-plus"></i> Crear
        </button>
      </div>
    </form>
  </div>
</div>

@foreach($usuarios as $usuario)
{{-- Modal cambiar contraseña --}}
<div id="modal-password-{{ $usuario->id }}" class="modal-ov">
  <div class="modal">
    <div class="modal-hdr">
      <h3><i class="fas fa-key"></i> Cambiar Contraseña</h3>
      <button class="modal-close" onclick="cerrarModal('modal-password-{{ $usuario->id }}')"><i class="fas fa-times"></i></button>
    </div>
    <form method="POST" action="{{ route('admin.usuarios.change-password', $usuario) }}">
      @csrf @method('PATCH')
      <div class="modal-body">
        <p style="margin-bottom:16px;color:var(--texto-suave)">
          Cambiando contraseña para: <strong>{{ $usuario->name }}</strong>
        </p>
        <div class="f-group">
          <label>Nueva Contraseña *</label>
          <input type="password" name="password" placeholder="••••••••" required>
        </div>
        <div class="f-group">
          <label>Confirmar Contraseña *</label>
          <input type="password" name="password_confirmation" placeholder="••••••••" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn-cancel" onclick="cerrarModal('modal-password-{{ $usuario->id }}')">Cancelar</button>
        <button type="submit" class="btn-submit">
          <i class="fas fa-save"></i> Cambiar
        </button>
      </div>
    </form>
  </div>
</div>
@endforeach

@endsection
