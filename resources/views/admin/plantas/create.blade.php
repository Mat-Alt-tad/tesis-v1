@extends('layouts.admin')

@section('title', 'Nueva Planta')
@section('admin-title', 'Nueva Planta')

@section('content')

<div class="tbl-card" style="max-width:780px">
  <div class="tbl-hdr">
    <h3><i class="fas fa-seedling"></i> Nueva Planta</h3>
    <a href="{{ route('admin.plantas.index') }}" class="btn-site">
      <i class="fas fa-arrow-left"></i> Volver
    </a>
  </div>

  <div style="padding:24px">
    @if($errors->any())
      <div class="alert-warn" style="background:#fce4ec;border-color:#f48fb1;color:#b71c1c;margin-bottom:20px">
        <i class="fas fa-exclamation-circle"></i>
        <ul style="list-style:none">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('admin.plantas.store') }}" enctype="multipart/form-data">
      @csrf

      <div class="f-row">
        <div class="f-group">
          <label>Nombre común *</label>
          <input type="text" name="nombre" value="{{ old('nombre') }}"
                 placeholder="Ej: Guayabo de Monte" required>
        </div>
        <div class="f-group">
          <label>Nombre científico</label>
          <input type="text" name="cientifico" value="{{ old('cientifico') }}"
                 placeholder="Ej: Psidium guajava">
        </div>
      </div>

      {{-- Categoría principal + categorías adicionales --}}
      <div class="f-row">
        <div class="f-group">
          <label>Categoría principal *</label>
          <select name="categoria_id" id="sel-categoria" required onchange="cargarSubtemas(this.value)">
            <option value="">Seleccionar…</option>
            @foreach($categorias as $cat)
              <option value="{{ $cat->id }}" {{ old('categoria_id') == $cat->id ? 'selected' : '' }}>
                {{ $cat->nombre }}
              </option>
            @endforeach
          </select>
        </div>
        <div class="f-group">
          <label>Subtema *</label>
          <select name="subtema_id" id="sel-subtema" required>
            <option value="">Selecciona primero una categoría</option>
            @if(old('categoria_id'))
              @foreach($categorias->find(old('categoria_id'))?->subtemas ?? [] as $sub)
                <option value="{{ $sub->id }}" {{ old('subtema_id') == $sub->id ? 'selected' : '' }}>
                  {{ $sub->nombre }}
                </option>
              @endforeach
            @endif
          </select>
        </div>
      </div>

      <div class="f-group">
        <label>Categorías adicionales <span style="font-weight:400;font-size:.8rem;color:var(--texto-suave)">(opcional — permite aparecer en múltiples categorías)</span></label>
        <div style="display:flex;flex-wrap:wrap;gap:10px;padding:12px;border:1px solid var(--border-lt);border-radius:9px;background:var(--fondo-card)">
          @foreach($categorias as $cat)
            <label style="display:flex;align-items:center;gap:6px;cursor:pointer;font-size:.85rem;font-weight:400">
              <input type="checkbox" name="categorias_extra[]" value="{{ $cat->id }}"
                     {{ in_array($cat->id, old('categorias_extra', [])) ? 'checked' : '' }}
                     style="width:14px;height:14px;accent-color:var(--verde-mid)">
              @if($cat->icono)
                <i class="{{ $cat->icono }}"></i>
              @endif
              {{ $cat->nombre }}
            </label>
          @endforeach
        </div>
        <small style="color:var(--texto-suave);font-size:.78rem">La categoría principal ya se incluye automáticamente.</small>
      </div>

      <div class="f-group">
        <label>Uso principal *</label>
        <input type="text" name="uso" value="{{ old('uso') }}"
               placeholder="Ej: Control de glucosa" required>
      </div>

      <div class="f-group">
        <label>Instrucciones de preparación *</label>
        <textarea name="instrucciones" rows="3"
                  placeholder="Hervir 3 hojas en 1 litro de agua…" required>{{ old('instrucciones') }}</textarea>
      </div>

      <div class="f-group">
        <label>Información general</label>
        <textarea name="contexto" rows="2"
                  placeholder="Propiedades científicas conocidas…">{{ old('contexto') }}</textarea>
      </div>

      <div class="f-group">
        <label>Relato del sabedor local</label>
        <textarea name="relato" rows="2"
                  placeholder="Mi abuela la usaba para…">{{ old('relato') }}</textarea>
      </div>

      {{-- Imagen --}}
      <div class="f-group">
        <label>Imagen de la planta <span style="font-weight:400;font-size:.8rem;color:var(--texto-suave)">(JPG, PNG, WEBP — máx. 4 MB)</span></label>
        <div class="img-upload-wrap" id="img-upload-wrap-create">
          <label for="imagen-create" class="img-upload-label" id="img-upload-label-create">
            <i class="fas fa-cloud-upload-alt"></i>
            <span>Haz clic o arrastra una imagen aquí</span>
          </label>
          <input type="file" name="imagen" id="imagen-create"
                 accept="image/jpeg,image/png,image/webp"
                 style="display:none"
                 onchange="previewImagen(this, 'preview-create', 'img-upload-label-create')">
          <div id="preview-create" class="img-preview" style="display:none">
            <img id="preview-create-img" src="" alt="Vista previa">
            <button type="button" class="img-remove-btn" onclick="quitarImagen('imagen-create','preview-create','img-upload-label-create')">
              <i class="fas fa-times"></i>
            </button>
          </div>
        </div>
      </div>

      {{-- Video --}}
      <div class="f-row">
        <div class="f-group">
          <label>URL video YouTube</label>
          <input type="text" name="video_url" value="{{ old('video_url') }}"
                 placeholder="https://youtube.com/watch?v=…">
          <small style="color:var(--texto-suave);font-size:.8rem;margin-top:4px;display:block">
            O sube un video local abajo
          </small>
        </div>
        <div class="f-group">
          <label>Tags (separados por coma)</label>
          <input type="text" name="tags" value="{{ old('tags') }}"
                 placeholder="Ej: local, hojas, ancestral">
        </div>
      </div>

      <div class="f-group">
        <label>Video local (opcional)</label>
        <input type="file" name="video_file" accept="video/*" style="margin-bottom:8px">
        <small style="color:var(--texto-suave);font-size:.8rem">
          Formatos: MP4, WebM, OGV. Máximo 50MB. <strong>Tiene prioridad sobre la URL arriba.</strong>
        </small>
      </div>

      {{-- Créditos del video --}}
      <div style="border:1px solid var(--border-lt);border-radius:10px;padding:16px;background:var(--fondo-card);margin-bottom:16px">
        <p style="font-size:.82rem;font-weight:700;color:var(--texto-suave);text-transform:uppercase;letter-spacing:.05em;margin:0 0 12px">
          <i class="fas fa-id-badge"></i> Créditos del video (overlay noticiero)
        </p>
        <div class="f-row" style="margin-bottom:0">
          <div class="f-group">
            <label>Nombre de la persona entrevistada</label>
            <input type="text" name="video_persona_nombre" value="{{ old('video_persona_nombre') }}"
                   placeholder="Ej: María Esperanza Rodríguez">
          </div>
          <div class="f-group">
            <label>Rol / ocupación</label>
            <input type="text" name="video_persona_rol" value="{{ old('video_persona_rol') }}"
                   placeholder="Ej: Sabedora local, Curandera">
          </div>
        </div>
        <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:.85rem;margin-top:8px">
          <input type="checkbox" name="video_validado" value="1"
                 {{ old('video_validado') ? 'checked' : '' }}
                 style="width:14px;height:14px;accent-color:var(--verde-mid)">
          El video ha sido validado y autorizado por la persona entrevistada
        </label>
      </div>

      <div class="f-group">
        <label style="display:flex;align-items:center;gap:8px;cursor:pointer">
          <input type="checkbox" name="verificada" value="1"
                 {{ old('verificada') ? 'checked' : '' }}
                 style="width:16px;height:16px;accent-color:var(--verde-mid)">
          Marcar como verificada por experto
        </label>
      </div>

      {{-- Consentimiento derechos de autor --}}
      <div class="consent" style="margin-bottom:16px">
        <input type="checkbox" name="consentimiento_autor" id="consent-autor" required>
        <p>Confirmo que tengo los derechos de autor sobre las imágenes y videos adjuntos, o cuento con la autorización expresa del titular, y asumo la responsabilidad legal por el contenido publicado.</p>
      </div>

      <div style="display:flex;justify-content:flex-end;gap:10px;margin-top:8px">
        <a href="{{ route('admin.plantas.index') }}" class="btn-cancel">Cancelar</a>
        <button type="submit" class="btn-submit">
          <i class="fas fa-save"></i> Guardar planta
        </button>
      </div>
    </form>
  </div>
</div>

@endsection

@push('scripts')
<script>
function cargarSubtemas(categoriaId) {
  const sel = document.getElementById('sel-subtema');
  sel.innerHTML = '<option value="">Cargando…</option>';
  if (!categoriaId) { sel.innerHTML = '<option value="">Selecciona primero una categoría</option>'; return; }
  fetch(`/admin/categorias/${categoriaId}/subtemas`)
    .then(r => r.json())
    .then(data => {
      sel.innerHTML = '<option value="">Seleccionar…</option>' +
        data.map(s => `<option value="${s.id}">${s.nombre}</option>`).join('');
    });
}
function previewImagen(input, previewId, labelId) {
  const file = input.files[0];
  if (!file) return;
  const reader = new FileReader();
  reader.onload = e => {
    document.getElementById(previewId + '-img').src = e.target.result;
    document.getElementById(previewId).style.display = 'block';
    document.getElementById(labelId).style.display = 'none';
  };
  reader.readAsDataURL(file);
}
function quitarImagen(inputId, previewId, labelId) {
  document.getElementById(inputId).value = '';
  document.getElementById(previewId).style.display = 'none';
  document.getElementById(labelId).style.display = 'flex';
}
</script>
@endpush
