<header id="main-hdr">
  <div id="hdr-marca" class="marca">
    <a href="{{ route('home') }}" class="marca-link">
      <div class="marca-ico"><i class="fas fa-leaf"></i></div>
      <div class="marca-txt">
        <strong>Etnobotánica</strong>
        <span>Fusagasugá · Cundinamarca</span>
      </div>
    </a>
  </div>

  @hasSection('breadcrumbs')
  <div id="hdr-bc" class="breadcrumbs">
    <a href="{{ route('home') }}" class="crumb"><i class="fas fa-home"></i> Inicio</a>
    @yield('breadcrumbs')
  </div>
  @endif

  <form action="{{ route('plantas.buscar') }}" method="GET" class="search-wrap">
    <i class="fas fa-search"></i>
    <input type="text"
           name="q"
           placeholder="Buscar planta…"
           autocomplete="off">
  </form>

  <div class="hdr-right">
    <button class="theme-btn" id="theme-btn" onclick="toggleTheme()" title="Claro / Oscuro">
      <i class="fas fa-moon"></i>
    </button>

    {{-- Créditos --}}
    <a href="{{ route('creditos') }}" class="btn-hdr"
       style="background:transparent;color:var(--texto-nav,rgba(255,255,255,.8));border:1px solid rgba(255,255,255,.25);font-size:.8rem"
       title="Créditos del proyecto">
      <i class="fas fa-award"></i> Créditos
    </a>

    {{-- Botón Aportar: solo para rol lector --}}
    @auth
      @if(auth()->user()->hasRole('lector'))
        <a href="{{ route('aportar') }}" class="btn-hdr btn-aportar">
          <i class="fas fa-plus"></i> Aportar
        </a>
      @endif
    @endauth

    @auth
      {{-- Campana de notificaciones --}}
      <div class="notif-wrap" id="notif-wrap">
        <button class="notif-btn" id="notif-btn" onclick="toggleNotifPanel()" title="Notificaciones">
          <i class="fas fa-bell"></i>
          <span class="notif-badge" id="notif-badge" style="display:none">0</span>
        </button>
        <div class="notif-panel" id="notif-panel" style="display:none">
          <div class="notif-hdr">
            <span>Notificaciones</span>
            <button class="notif-mark-all" onclick="marcarTodasLeidas()">Marcar todas</button>
          </div>
          <div class="notif-lista" id="notif-lista">
            <div class="notif-empty">Cargando…</div>
          </div>
        </div>
      </div>

      @if(auth()->user()->hasAnyRole(['admin', 'moderador']))
        <a href="{{ route('admin.dashboard') }}" class="btn-hdr btn-admin">
          <i class="fas fa-th-large"></i> Admin
        </a>
      @elseif(auth()->user()->hasRole('lector'))
        <a href="{{ route('lector.dashboard') }}" class="btn-hdr btn-lector">
          <i class="fas fa-seedling"></i> Mis aportes
        </a>
      @endif

      <a href="{{ route('perfil.show-change-password') }}" class="btn-hdr" title="Cambiar contraseña">
        <i class="fas fa-user"></i> Perfil
      </a>

      <form method="POST" action="{{ route('logout') }}" style="display:inline">
        @csrf
        <button type="submit" class="btn-hdr" title="Cerrar sesión" style="background:var(--rojo-light);color:white">
          <i class="fas fa-sign-out-alt"></i> Salir
        </button>
      </form>
    @else
      <a href="{{ route('login') }}" class="btn-hdr btn-admin">
        <i class="fas fa-sign-in-alt"></i> Iniciar sesión
      </a>
    @endauth
  </div>
</header>

<style>
/* ── Campana ── */
.notif-wrap { position: relative; }
.notif-btn {
  width: 38px; height: 38px; border-radius: 50%;
  border: 1.5px solid rgba(255,255,255,.3);
  background: rgba(255,255,255,.12); color: var(--dorado-lt);
  cursor: pointer; display: flex; align-items: center; justify-content: center;
  font-size: 1rem; transition: background .2s; position: relative;
  flex-shrink: 0;
}
.notif-btn:hover { background: rgba(255,255,255,.24); }
.notif-badge {
  position: absolute; top: -4px; right: -4px;
  background: #e53935; color: #fff; border-radius: 50%;
  min-width: 18px; height: 18px; font-size: .68rem; font-weight: 800;
  display: flex; align-items: center; justify-content: center; padding: 0 3px;
  border: 2px solid var(--verde);
}

/* ── Panel ── */
.notif-panel {
  position: absolute; top: calc(100% + 10px); right: 0;
  width: 340px; background: var(--bg-card, #fff);
  border-radius: 16px; box-shadow: 0 8px 40px rgba(0,0,0,.18);
  z-index: 1000; overflow: hidden;
  border: 1px solid var(--border-lt, #eee);
  animation: fadeDown .18s ease;
}
@keyframes fadeDown { from{opacity:0;transform:translateY(-8px)} to{opacity:1;transform:translateY(0)} }
.notif-hdr {
  padding: 14px 18px; display: flex; justify-content: space-between; align-items: center;
  border-bottom: 1px solid var(--border-lt, #eee);
  font-size: .88rem; font-weight: 700; color: var(--texto, #333);
}
.notif-mark-all {
  background: none; border: none; font-size: .75rem; color: var(--verde, #2e7d32);
  cursor: pointer; font-family: inherit;
}
.notif-mark-all:hover { text-decoration: underline; }
.notif-lista { max-height: 380px; overflow-y: auto; }
.notif-item {
  display: flex; gap: 12px; padding: 13px 16px; cursor: pointer;
  border-bottom: 1px solid var(--border-lt, #eee);
  transition: background .15s; text-decoration: none; color: inherit;
}
.notif-item:hover { background: var(--pale, #e8f5ee); }
.notif-item.no-leida { background: var(--pale, #e8f5ee); }
.notif-item.no-leida .notif-dot { display: block; }
.notif-ico {
  width: 36px; height: 36px; border-radius: 50%; flex-shrink: 0;
  display: flex; align-items: center; justify-content: center; font-size: .9rem;
}
.notif-ico.nuevo-aporte  { background: #fff3e0; color: #e65100; }
.notif-ico.aprobado      { background: #e8f5e9; color: #2e7d32; }
.notif-ico.rechazado     { background: #fce4ec; color: #c62828; }
.notif-ico.like          { background: #fce4ec; color: #e53935; }
.notif-body { flex: 1; min-width: 0; }
.notif-titulo { font-size: .82rem; font-weight: 700; color: var(--texto, #333); margin: 0 0 2px; }
.notif-msg { font-size: .78rem; color: var(--texto-suave, #999); line-height: 1.4; margin: 0 0 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.notif-fecha { font-size: .7rem; color: var(--texto-suave, #aaa); }
.notif-dot { display: none; width: 8px; height: 8px; border-radius: 50%; background: var(--verde, #2e7d32); flex-shrink: 0; margin-top: 6px; }
.notif-empty { padding: 32px; text-align: center; font-size: .85rem; color: var(--texto-suave, #999); }
</style>

@auth
<script>
const _notifIcons = {
  nuevo_aporte:    {cls:'nuevo-aporte',  ico:'fa-seedling'},
  aporte_aprobado: {cls:'aprobado',      ico:'fa-check-circle'},
  aporte_rechazado:{cls:'rechazado',     ico:'fa-times-circle'},
  like_comentario: {cls:'like',          ico:'fa-heart'},
};

async function cargarNotificaciones() {
  try {
    const res  = await fetch('{{ route("notificaciones.index") }}', {headers:{'Accept':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').content}});
    const data = await res.json();
    const badge = document.getElementById('notif-badge');
    const lista = document.getElementById('notif-lista');

    if (data.no_leidas > 0) {
      badge.textContent = data.no_leidas > 9 ? '9+' : data.no_leidas;
      badge.style.display = 'flex';
    } else {
      badge.style.display = 'none';
    }

    if (!data.notificaciones.length) {
      lista.innerHTML = '<div class="notif-empty"><i class="fas fa-bell-slash" style="font-size:1.5rem;opacity:.3;display:block;margin-bottom:8px"></i>Sin notificaciones</div>';
      return;
    }

    lista.innerHTML = data.notificaciones.map(n => {
      const meta = _notifIcons[n.tipo] || {cls:'nuevo-aporte',ico:'fa-bell'};
      const url  = n.url || '#';
      return `<a href="${url}" class="notif-item ${n.leida ? '' : 'no-leida'}" onclick="marcarUna(${n.id},this)" data-id="${n.id}">
        <div class="notif-ico ${meta.cls}"><i class="fas ${meta.ico}"></i></div>
        <div class="notif-body">
          <p class="notif-titulo">${n.titulo}</p>
          <p class="notif-msg">${n.mensaje}</p>
          <span class="notif-fecha">${formatearFecha(n.creado_en)}</span>
        </div>
        <span class="notif-dot"></span>
      </a>`;
    }).join('');
  } catch(e) {}
}

function formatearFecha(s) {
  const d = new Date(s);
  const diff = Date.now() - d.getTime();
  const min = Math.floor(diff/60000);
  if (min < 1)  return 'Ahora mismo';
  if (min < 60) return `Hace ${min} min`;
  const h = Math.floor(min/60);
  if (h < 24)   return `Hace ${h} h`;
  return d.toLocaleDateString('es-CO',{day:'2-digit',month:'short'});
}

function toggleNotifPanel() {
  const p = document.getElementById('notif-panel');
  const open = p.style.display === 'none';
  p.style.display = open ? 'block' : 'none';
  if (open) cargarNotificaciones();
}

async function marcarTodasLeidas() {
  await fetch('{{ route("notificaciones.leidas") }}',{method:'POST',headers:{'X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').content,'Accept':'application/json'}});
  document.getElementById('notif-badge').style.display = 'none';
  document.querySelectorAll('.notif-item.no-leida').forEach(el => { el.classList.remove('no-leida'); el.querySelector('.notif-dot').style.display='none'; });
}

async function marcarUna(id, el) {
  el.classList.remove('no-leida');
  const dot = el.querySelector('.notif-dot');
  if (dot) dot.style.display = 'none';
  fetch(`/notificaciones/${id}/leida`,{method:'POST',headers:{'X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').content}});
}

// Cerrar panel al click fuera
document.addEventListener('click', e => {
  const wrap = document.getElementById('notif-wrap');
  if (wrap && !wrap.contains(e.target)) {
    document.getElementById('notif-panel').style.display = 'none';
  }
});

// Cargar badge al cargar página
document.addEventListener('DOMContentLoaded', async () => {
  try {
    const res  = await fetch('{{ route("notificaciones.index") }}',{headers:{'Accept':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').content}});
    const data = await res.json();
    const badge = document.getElementById('notif-badge');
    if (data.no_leidas > 0) { badge.textContent = data.no_leidas > 9 ? '9+' : data.no_leidas; badge.style.display = 'flex'; }
  } catch(e) {}
});
</script>
@endauth
