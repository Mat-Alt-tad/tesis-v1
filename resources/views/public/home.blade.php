@extends('layouts.app')

@section('title', 'Etnobotánica Fusagasugá')


@section('content')

<div class="hero">
  <h1>Saberes botánicos de<br><em>Sumapaz</em></h1>
  <p>Catálogo colaborativo del conocimiento tradicional sobre plantas medicinales y su uso local.</p>
</div>

<div class="container">
  <div class="sec-inner">
    <p class="sec-titulo">Explorar Categorías</p>
    <p class="sec-sub">Selecciona una categoría para explorar los saberes botánicos</p>

    <div class="catalogo-actions">
      <a href="{{ route('catalogo') }}" class="btn-catalogo">
        <i class="fas fa-book-open"></i>
        Ver Catálogo Completo
      </a>
    </div>

    <div class="grid-cats">
      @forelse($categorias as $cat)
        <a href="{{ route('categorias.show', $cat->id) }}" class="card-cat">
          <div class="cat-ico">
            <i class="{{ $cat->icono }}"></i>
          </div>
          <h3>{{ $cat->nombre }}</h3>
          <p>{{ $cat->descripcion }}</p>
        </a>
      @empty
        <div class="empty">
          <i class="fas fa-seedling"></i>
          <p>No hay categorías registradas aún.</p>
        </div>
      @endforelse
    </div>
  </div>
</div>

{{-- ══ SECCIÓN: Aportes de la comunidad ══ --}}
@if($aportesAprobados->count())
<div style="background:var(--pale);padding:48px 0 56px;margin-top:16px">
  <div class="container">
    <div class="sec-inner" style="padding-top:0">
      <div style="display:flex;align-items:flex-end;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:24px">
        <div>
          <p class="sec-titulo" style="margin-bottom:4px">Aportes de la comunidad</p>
          <p class="sec-sub" style="margin-bottom:0">Conocimiento compartido y verificado por nuestros moderadores</p>
        </div>
        <a href="{{ route('aportar') }}" class="btn-catalogo" style="font-size:.85rem;padding:.6rem 1.2rem">
          <i class="fas fa-seedling"></i> Ver todos los aportes
        </a>
      </div>

      <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:18px">
        @foreach($aportesAprobados as $ap)
          <a href="{{ route('aportes.show', $ap->id) }}" class="card-aporte-home" style="text-decoration:none;color:inherit">
            <div class="cah-img">
              @if($ap->img_path)
                <img src="{{ asset('storage/' . $ap->img_path) }}" alt="{{ $ap->nombre_planta }}">
              @else
                <div class="cah-noimg">
                  <i class="fas fa-seedling"></i>
                </div>
              @endif
              <span class="cah-cat">{{ $ap->categoria }}</span>
            </div>
            <div class="cah-body">
              <strong class="cah-nombre">{{ $ap->nombre_planta }}</strong>
              @if($ap->cientifico)
                <em class="cah-cientifico">{{ $ap->cientifico }}</em>
              @endif
              <p class="cah-uso"><i class="fas fa-circle-info"></i> {{ $ap->uso }}</p>
              <p class="cah-prep">{{ Str::limit($ap->preparacion, 90) }}</p>
              <div class="cah-footer">
                <span><i class="fas fa-calendar-alt"></i> {{ $ap->creado_en->format('d M Y') }}</span>
                <span class="cah-link">Leer más <i class="fas fa-arrow-right" style="font-size:.7rem"></i></span>
              </div>
            </div>
          </a>
        @endforeach
      </div>
    </div>
  </div>
</div>
@endif

@endsection

<style>
.catalogo-actions { text-align: center; margin-bottom: 2rem; }
.btn-catalogo {
  display: inline-flex; align-items: center; gap: .5rem;
  padding: .75rem 1.5rem; background: var(--verde); color: white;
  text-decoration: none; border-radius: 8px; font-weight: 500;
  transition: background .3s ease;
}
.btn-catalogo:hover { background: var(--verde-mid); color: white; text-decoration: none; }
.btn-catalogo i { font-size: 1.1rem; }

/* ── Tarjeta aporte home ── */
.card-aporte-home {
  background: var(--bg-card); border-radius: 16px;
  overflow: hidden; display: flex; flex-direction: column;
  box-shadow: var(--sombra); border: 1px solid transparent;
  transition: var(--trans);
}
.card-aporte-home:hover {
  transform: translateY(-6px); box-shadow: var(--sombra-lg);
  border-color: var(--verde-mid);
}
.cah-img { position: relative; height: 150px; overflow: hidden; background: #c8e6c9; }
.cah-img img { width: 100%; height: 100%; object-fit: cover; transition: transform .35s ease; }
.card-aporte-home:hover .cah-img img { transform: scale(1.05); }
.cah-noimg {
  height: 100%; display: flex; align-items: center; justify-content: center;
  background: linear-gradient(135deg, var(--pale) 0%, #c8e6c9 100%);
  font-size: 2.2rem; color: var(--verde-mid); opacity: .45;
}
.cah-cat {
  position: absolute; top: 10px; right: 10px;
  background: rgba(0,0,0,.55); color: #fff; backdrop-filter: blur(4px);
  padding: 3px 10px; border-radius: 20px; font-size: .72rem; font-weight: 700;
}
.cah-body { padding: 16px 18px; flex: 1; display: flex; flex-direction: column; gap: 6px; }
.cah-nombre { font-size: .97rem; color: var(--texto); font-weight: 700; line-height: 1.3; }
.cah-cientifico { font-size: .78rem; color: var(--texto-suave); display: block; }
.cah-uso { font-size: .8rem; color: var(--texto-suave); margin: 0; }
.cah-uso i { color: var(--verde-mid); margin-right: 3px; }
.cah-prep { font-size: .82rem; color: var(--texto); line-height: 1.5; margin: 0; flex: 1; }
.cah-footer {
  display: flex; align-items: center; justify-content: space-between;
  padding-top: 10px; border-top: 1px solid var(--border-lt);
  font-size: .74rem; color: var(--texto-suave); margin-top: 4px;
}
.cah-link { color: var(--verde); font-weight: 600; font-size: .78rem; }
</style>
