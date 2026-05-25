@extends('layouts.app')

@section('title', 'Boutique - FreshMarket')

@section('styles')
<style>
    :root {
        --primary:#0a4f6e; --primary-dark:#073a52; --secondary:#e8830a;
        --accent:#c0392b; --green:#27ae60; --light-bg:#f4f9fc;
        --dark:#1a1a2e; --muted:#6c757d;
    }

    /* HERO */
    .page-hero { background:linear-gradient(135deg,var(--primary-dark) 0%,var(--primary) 60%,#1a7a9a 100%); padding:50px 0; position:relative; overflow:hidden; }
    .page-hero::before { content:'🐟'; position:absolute; font-size:200px; opacity:.05; right:-20px; top:-30px; transform:rotate(-20deg); }
    .page-hero::after  { content:'🥩'; position:absolute; font-size:150px; opacity:.05; left:30px; bottom:-20px; transform:rotate(15deg); }
    .page-hero h1 { font-size:38px; color:#fff; font-weight:800; margin-bottom:10px; }
    .breadcrumb { background:transparent; padding:0; margin:0; }
    .breadcrumb-item a { color:rgba(255,255,255,.85); text-decoration:none; }
    .breadcrumb-item.active { color:var(--secondary); }
    .breadcrumb-item + .breadcrumb-item::before { color:rgba(255,255,255,.4); }

    /* WRAPPER */
    .shop-wrapper { padding:40px 0 60px; background:var(--light-bg); min-height:70vh; }

    /* TOOLBAR */
    .shop-toolbar { background:#fff; border-radius:12px; padding:16px 22px; display:flex; align-items:center; justify-content:space-between; margin-bottom:24px; box-shadow:0 2px 10px rgba(0,0,0,.05); flex-wrap:wrap; gap:12px; }
    .toolbar-info { font-size:14px; color:var(--muted); }
    .toolbar-info strong { color:var(--primary); font-size:15px; }
    .toolbar-right { display:flex; align-items:center; gap:12px; flex-wrap:wrap; }

    /* Recherche inline */
    .search-inline { display:flex; gap:8px; }
    .search-inline input { padding:9px 14px; border:1.5px solid #e0eaf0; border-radius:8px; font-size:13px; color:var(--dark); outline:none; width:220px; transition:border .2s; }
    .search-inline input:focus { border-color:var(--primary); }
    .search-inline button { padding:9px 16px; background:var(--primary); color:#fff; border:none; border-radius:8px; font-size:13px; font-weight:700; cursor:pointer; }

    /* Filtre catégorie */
    .cat-filter { display:flex; gap:8px; flex-wrap:wrap; margin-bottom:20px; }
    .cat-filter-btn { padding:7px 16px; border:2px solid #e0eaf0; border-radius:30px; background:#fff; font-size:13px; font-weight:600; color:var(--muted); cursor:pointer; text-decoration:none; transition:all .2s; display:inline-block; }
    .cat-filter-btn:hover, .cat-filter-btn.active { background:var(--primary); border-color:var(--primary); color:#fff; }

    /* Sort */
    .sort-select { border:1.5px solid #e0e0e0; border-radius:8px; padding:9px 14px; font-size:13px; color:#444; background:#fff; cursor:pointer; }
    .sort-select:focus { outline:none; border-color:var(--primary); }

    /* GRILLE PRODUITS */
    .products-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:20px; }
    .product-card { background:#fff; border-radius:14px; overflow:hidden; box-shadow:0 3px 12px rgba(0,0,0,.07); transition:all .3s ease; }
    .product-card:hover { transform:translateY(-6px); box-shadow:0 10px 35px rgba(0,0,0,.13); }
    .product-img-wrap { position:relative; overflow:hidden; height:400px; }
    .product-img-wrap img { width:100%; height:100%; object-fit:cover; transition:transform .4s ease; }
    .product-card:hover .product-img-wrap img { transform:scale(1.08); }
    .product-badge { position:absolute; top:10px; left:10px; padding:3px 10px; border-radius:20px; font-size:10px; font-weight:700; text-transform:uppercase; z-index:2; }
    .badge-fresh   { background:var(--green); color:#fff; }
    .badge-promo   { background:var(--accent); color:#fff; }
    .badge-new     { background:var(--secondary); color:#fff; }
    .badge-poisson { background:rgba(10,79,110,.9); color:#fff; }
    .product-body { padding:14px 16px; }
    .product-category { font-size:10px; color:var(--secondary); font-weight:700; text-transform:uppercase; letter-spacing:.8px; margin-bottom:5px; }
    .product-body h5 { font-size:14px; font-weight:700; color:var(--dark); margin-bottom:6px; line-height:1.4; }
    .product-desc { font-size:12px; color:var(--muted); margin-bottom:10px; line-height:1.5; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; }
    .product-footer { display:flex; align-items:center; justify-content:space-between; }
    .product-price { font-size:15px; font-weight:800; color:var(--primary); }
    .product-price small { font-size:11px; color:var(--muted); font-weight:400; }
    .btn-cart { background:var(--primary); color:#fff; border:none; padding:7px 14px; border-radius:8px; font-size:12px; font-weight:700; cursor:pointer; transition:all .3s; display:inline-flex; align-items:center; gap:5px; text-decoration:none; }
    .btn-cart:hover { background:var(--secondary); color:#fff; }
    .btn-cart-poisson { background:linear-gradient(135deg,#0a4f6e,#1a7fb5); }
    .btn-cart-poisson:hover { background:linear-gradient(135deg,#e8830a,#f0a83a); }

    /* PAGINATION */
    .pagination-wrap { display:flex; justify-content:center; margin-top:35px; }
    .page-link { border:1.5px solid #e0e0e0; color:var(--dark); border-radius:8px!important; width:38px; height:38px; display:flex; align-items:center; justify-content:center; font-size:14px; font-weight:600; transition:all .3s; }
    .page-link:hover { border-color:var(--primary); color:var(--primary); background:#fff; }
    .page-item.active .page-link { background:var(--primary); border-color:var(--primary); color:#fff; }

    /* EMPTY */
    .empty-state { text-align:center; padding:60px 20px; color:var(--muted); grid-column:1/-1; background:#fff; border-radius:16px; }
    .empty-state .empty-icon { font-size:60px; margin-bottom:20px; display:block; }

    /* MODAL CALIBRE */
    .modal-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,.55); z-index:9999; align-items:center; justify-content:center; backdrop-filter:blur(4px); }
    .modal-overlay.active { display:flex; }
    .modal-box { background:#fff; border-radius:20px; width:90%; max-width:480px; overflow:hidden; box-shadow:0 20px 60px rgba(0,0,0,.25); animation:modalIn .25s ease; }
    @keyframes modalIn { from{transform:scale(.9);opacity:0} to{transform:scale(1);opacity:1} }
    .modal-header { background:linear-gradient(135deg,#073a52,#0a4f6e); padding:20px 24px; display:flex; align-items:center; justify-content:space-between; }
    .modal-header h4 { color:#fff; font-size:16px; font-weight:800; margin:0; }
    .modal-close { background:rgba(255,255,255,.15); border:none; color:#fff; width:32px; height:32px; border-radius:50%; cursor:pointer; font-size:16px; display:flex; align-items:center; justify-content:center; }
    .modal-close:hover { background:rgba(255,255,255,.3); }
    .modal-body { padding:24px; }
    .modal-produit-info { display:flex; align-items:center; gap:14px; margin-bottom:22px; padding-bottom:18px; border-bottom:2px solid var(--light-bg); }
    .modal-produit-img { width:70px; height:70px; border-radius:12px; object-fit:cover; flex-shrink:0; }
    .modal-produit-name { font-size:16px; font-weight:800; color:var(--dark); margin-bottom:4px; }
    .modal-produit-price { font-size:18px; font-weight:800; color:var(--primary); }
    .modal-produit-price small { font-size:12px; color:var(--muted); font-weight:400; }
    .calibre-label { font-size:12px; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:.8px; margin-bottom:12px; display:block; }
    .calibre-options { display:grid; grid-template-columns:repeat(3,1fr); gap:10px; margin-bottom:20px; }
    .calibre-btn { border:2px solid #e0eaf0; background:#fff; border-radius:12px; padding:12px 8px; cursor:pointer; text-align:center; transition:all .2s; }
    .calibre-btn:hover { border-color:var(--primary); background:rgba(10,79,110,.04); }
    .calibre-btn.selected { border-color:var(--primary); background:rgba(10,79,110,.08); }
    .calibre-btn .c-icon { font-size:22px; display:block; margin-bottom:5px; }
    .calibre-btn .c-name { font-size:13px; font-weight:800; color:var(--dark); display:block; }
    .calibre-btn .c-poids { font-size:10px; color:var(--muted); display:block; margin-top:2px; }
    .calibre-btn input[type="radio"] { display:none; }
    .quantite-label { font-size:12px; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:.8px; margin-bottom:12px; display:block; }
    .quantite-ctrl { display:flex; align-items:center; gap:10px; margin-bottom:20px; }
    .qty-btn { width:36px; height:36px; border:2px solid #e0eaf0; background:#fff; border-radius:8px; font-size:18px; cursor:pointer; display:flex; align-items:center; justify-content:center; color:var(--primary); font-weight:700; transition:all .2s; }
    .qty-btn:hover { background:var(--primary); color:#fff; border-color:var(--primary); }
    .qty-input { width:60px; text-align:center; border:2px solid #e0eaf0; border-radius:8px; padding:7px; font-size:15px; font-weight:700; color:var(--dark); outline:none; }
    .qty-input:focus { border-color:var(--primary); }
    .qty-unite { font-size:13px; color:var(--muted); font-weight:600; }
    .modal-footer { padding:0 24px 24px; }
    .btn-modal-add { width:100%; padding:14px; background:linear-gradient(135deg,#0a4f6e,#1a7fb5); color:#fff; border:none; border-radius:12px; font-size:15px; font-weight:800; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:8px; transition:all .3s; }
    .btn-modal-add:hover { background:linear-gradient(135deg,#e8830a,#f0a83a); transform:translateY(-1px); }
    .btn-modal-add:disabled { opacity:.5; cursor:not-allowed; transform:none; }

    /* ===== INSTAGRAM ===== */
        .instagram-section{background:var(--light);padding-bottom:0;}
        .insta-grid{display:grid;grid-template-columns:repeat(5,1fr);}
        .insta-item{position:relative;overflow:hidden;height:380px;display:flex;align-items:center;justify-content:center;font-size:60px;cursor:pointer;}
        .insta-item:nth-child(1){background:linear-gradient(135deg,var(--primary),#1a8aac);}
        .insta-item:nth-child(2){background:linear-gradient(135deg,#8B4513,#c0392b);}
        .insta-item:nth-child(3){background:linear-gradient(135deg,#2d6a2d,#4caf50);}
        .insta-item:nth-child(4){background:linear-gradient(135deg,#6a1f6a,#9b59b6);}
        .insta-item:nth-child(5){background:linear-gradient(135deg,#c47c2f,#e8a048);}
        .insta-overlay{position:absolute;inset:0;background:rgba(10,79,110,.7);display:flex;align-items:center;justify-content:center;opacity:0;transition:all .3s;}
        .insta-item:hover .insta-overlay{opacity:1;}
        .insta-overlay i{color:#fff;font-size:28px;}

 /* ===== PAGINATION MODERNE ===== */

.pagination-wrap{
    display:flex;
    justify-content:center;
    margin-top:40px;
}

.pagination{
    display:flex;
    align-items:center;
    gap:8px;
    flex-wrap:wrap;
}

/* Boutons pagination */
.page-item .page-link{
    width:36px;
    height:36px;

    display:flex;
    align-items:center;
    justify-content:center;

    border-radius:10px !important;
    border:1px solid #e3e8ee;

    background:#fff;
    color:#1a1a2e;

    font-size:13px;
    font-weight:600;

    transition:all .25s ease;
    text-decoration:none;

    padding:0 !important;
    box-shadow:0 2px 6px rgba(0,0,0,.04);
}

/* Hover élégant */
.page-item .page-link:hover{
    background:#0a4f6e;
    border-color:#0a4f6e;
    color:#fff;
    transform:translateY(-2px);
}

/* Page active */
.page-item.active .page-link{
    background:#0a4f6e;
    border-color:#0a4f6e;
    color:#fff;
    box-shadow:0 4px 12px rgba(10,79,110,.25);
}

/* Désactivé */
.page-item.disabled .page-link{
    opacity:.4;
    pointer-events:none;
}

/* Supprime les gros SVG Laravel */
.page-link svg{
    display:none !important;
}

/* Flèches propres */
.page-item:first-child .page-link::before{
    content:'←';
    font-size:14px;
    font-weight:700;
}

.page-item:last-child .page-link::before{
    content:'→';
    font-size:14px;
    font-weight:700;
}

/* Cache le texte Laravel */
.page-item:first-child .page-link,
.page-item:last-child .page-link{
    font-size:0;
}




    @media(max-width:1200px) { .products-grid { grid-template-columns:repeat(3,1fr); } }
    @media(max-width:768px)  { .products-grid { grid-template-columns:repeat(2,1fr); } .page-hero h1 { font-size:28px; } .search-inline input { width:160px; } }
    @media(max-width:480px)  { .products-grid { grid-template-columns:1fr; } }
    @media(max-width:991px){.insta-grid{grid-template-columns:repeat(3,1fr);}}
</style>
@endsection

@section('content')

{{-- HERO --}}
<div class="page-hero">
    <div class="container" style="position:relative;z-index:2;">
        <h1>Notre Boutique</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                <li class="breadcrumb-item active">Boutique</li>
            </ol>
        </nav>
    </div>
</div>

{{-- MODAL CALIBRE --}}
<div class="modal-overlay" id="calibreModal">
    <div class="modal-box">
        <div class="modal-header">
            <h4>🐟 Choisir le calibre</h4>
            <button class="modal-close" onclick="closeModal()">✕</button>
        </div>
        <div class="modal-body">
            <div class="modal-produit-info">
                <div id="modalImg"></div>
                <div>
                    <div class="modal-produit-name" id="modalNom">—</div>
                    <div class="modal-produit-price"><span id="modalPrix">—</span> FCFA <small>/ kg</small></div>
                </div>
            </div>
            <span class="calibre-label">Sélectionnez un calibre</span>
            <div class="calibre-options">
                <label class="calibre-btn">
                    <input type="radio" name="calibre_choix" value="1">
                    <span class="c-icon">🐟</span>
                    <span class="c-name">Grande</span>
                    <span class="c-poids">≥ 1,5 kg</span>
                </label>
                <label class="calibre-btn">
                    <input type="radio" name="calibre_choix" value="2">
                    <span class="c-icon">🐟</span>
                    <span class="c-name">Moyen</span>
                    <span class="c-poids">0,5 — 1,5 kg</span>
                </label>
                <label class="calibre-btn">
                    <input type="radio" name="calibre_choix" value="3">
                    <span class="c-icon">🐟</span>
                    <span class="c-name">Petit</span>
                    <span class="c-poids">Silivie ≤ 0,5 kg</span>
                </label>
            </div>
            <span class="quantite-label">Quantité</span>
            <div class="quantite-ctrl">
                <button type="button" class="qty-btn" onclick="changeQty(-1)">−</button>
                <input type="number" class="qty-input" id="modalQty" value="1" min="1" max="100">
                <button type="button" class="qty-btn" onclick="changeQty(1)">+</button>
                <span class="qty-unite">kg</span>
            </div>
        </div>
        <div class="modal-footer">
            <form action="{{ route('cart.add') }}" method="POST" id="modalForm">
                @csrf
                <input type="hidden" name="produit_id" id="modalProduitId">
                <input type="hidden" name="calibre_id" id="modalCalibreId">
                <input type="hidden" name="quantite"   id="modalQuantite" value="1">
                <button type="submit" class="btn-modal-add" id="btnModalAdd" disabled>
                    <i class="fas fa-cart-plus"></i> Ajouter au panier
                </button>
            </form>
        </div>
    </div>
</div>

{{-- BOUTIQUE --}}
<section class="shop-wrapper">
    <div class="container">

        {{-- Alertes --}}
        @if(session('success'))
        <div style="background:rgba(39,174,96,.1);border:1.5px solid rgba(39,174,96,.25);color:#1e7e44;border-radius:10px;padding:12px 16px;font-size:14px;font-weight:600;margin-bottom:18px;display:flex;align-items:center;gap:8px;">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div style="background:rgba(192,57,43,.08);border:1.5px solid rgba(192,57,43,.2);color:#c0392b;border-radius:10px;padding:12px 16px;font-size:14px;font-weight:600;margin-bottom:18px;display:flex;align-items:center;gap:8px;">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
        @endif

        {{-- Filtres catégories --}}
        @php
            $catIcons = ['Poissons frais'=>'🐟','Poissons fumés'=>'🔥','Viandes de bœuf'=>'🥩','Volailles'=>'🍗','Escargots'=>'🐌','Abats'=>'🫀'];
        @endphp
        <div class="cat-filter">
            <a href="{{ route('shop') }}" class="cat-filter-btn {{ !request('categorie') ? 'active' : '' }}">📦 Tous</a>
            @foreach($categories as $cat)
            <a href="{{ route('shop', ['categorie' => $cat->slug]) }}"
               class="cat-filter-btn {{ request('categorie') === $cat->slug ? 'active' : '' }}">
                {{ $catIcons[$cat->libelle] ?? '📦' }} {{ $cat->libelle }}
                <span style="font-size:11px;opacity:.7;">({{ $cat->produits_count }})</span>
            </a>
            @endforeach
        </div>

        {{-- Toolbar recherche + tri --}}
        <div class="shop-toolbar">
            <div class="toolbar-info">
                <strong>{{ $produits->total() }}</strong> produit(s)
                @if(request('categorie') && isset($catActive)) dans <strong>{{ $catActive->libelle }}</strong> @endif
                @if(request('q')) — recherche : "<strong>{{ request('q') }}</strong>" @endif
            </div>
            <div class="toolbar-right">
                <form action="{{ route('shop') }}" method="GET" class="search-inline">
                    @if(request('categorie'))
                    <input type="hidden" name="categorie" value="{{ request('categorie') }}">
                    @endif
                    <input type="text" name="q" placeholder="Rechercher..." value="{{ request('q') }}">
                    <button type="submit"><i class="fas fa-search"></i></button>
                </form>
                <form action="{{ route('shop') }}" method="GET" style="display:inline;">
                    @foreach(request()->except('tri') as $key => $val)
                        <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                    @endforeach
                    <select name="tri" class="sort-select" onchange="this.form.submit()">
                        <option value="popularite" {{ request('tri') == 'popularite' ? 'selected' : '' }}>Popularité</option>
                        <option value="prix_asc"   {{ request('tri') == 'prix_asc'   ? 'selected' : '' }}>Prix ↑</option>
                        <option value="prix_desc"  {{ request('tri') == 'prix_desc'  ? 'selected' : '' }}>Prix ↓</option>
                        <option value="nouveautes" {{ request('tri') == 'nouveautes' ? 'selected' : '' }}>Nouveautés</option>
                    </select>
                </form>
            </div>
        </div>

        {{-- Grille produits --}}
        <div class="products-grid">
            @forelse($produits as $produit)
            @php
                $estPoisson = $produit->calibre && $produit->calibre->type_produit === 'poisson';
                $imgUrl     = $produit->image
                    ? asset('images/produits/' . $produit->image)
                    : asset('images/img-pro-01.jpg');
            @endphp
            <div class="product-card">
                <div class="product-img-wrap">
                    <img src="{{ $imgUrl }}" alt="{{ $produit->libelle_prod }}"
                         onerror="this.src='{{ asset('images/img-pro-01.jpg') }}'">
                    @if($produit->stock && $produit->stock->quantite_stock <= 0)
                        <span class="product-badge badge-promo">Épuisé</span>
                    @elseif($estPoisson)
                        <span class="product-badge badge-poisson">🐟 Au calibre</span>
                    @elseif($produit->created_at->diffInDays(now()) <= 7)
                        <span class="product-badge badge-new">Nouveau</span>
                    @else
                        <span class="product-badge badge-fresh">Frais</span>
                    @endif
                </div>
                <div class="product-body">
                    <p class="product-category">{{ $produit->categorie->libelle ?? 'Produit' }}</p>
                    <h5>{{ $produit->libelle_prod }}</h5>
                    @if($produit->description)
                    <p class="product-desc">{{ $produit->description }}</p>
                    @endif
                    <div class="product-footer">
                        <div class="product-price">
                            {{ number_format($produit->prix, 0, ',', ' ') }} FCFA
                            <small>/ kg</small>
                        </div>
                        @if($produit->stock && $produit->stock->quantite_stock <= 0)
                            <button class="btn-cart" disabled style="opacity:.5;cursor:not-allowed;">
                                <i class="fas fa-times"></i> Épuisé
                            </button>
                        @elseif($estPoisson)
                            <button type="button" class="btn-cart btn-cart-poisson"
                                onclick="openModal({{ $produit->id_prod }}, '{{ addslashes($produit->libelle_prod) }}', '{{ number_format($produit->prix, 0) }}', '{{ $imgUrl }}')">
                                <i class="fas fa-fish"></i> Choisir
                            </button>
                        @else
                            <form action="{{ route('cart.add') }}" method="POST" style="display:inline">
                                @csrf
                                <input type="hidden" name="produit_id" value="{{ $produit->id_prod }}">
                                <input type="hidden" name="quantite" value="1">
                                <button type="submit" class="btn-cart">
                                    <i class="fas fa-cart-plus"></i> Ajouter
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="empty-state">
                <span class="empty-icon">🐟</span>
                <h4>Aucun produit trouvé</h4>
                <p>Essayez une autre catégorie ou revenez plus tard.</p>
                <a href="{{ route('shop') }}" class="btn-cart mt-3" style="display:inline-flex;">Voir tous les produits</a>
            </div>
            @endforelse
        </div>

        {{-- PAGINATION PERSONNALISÉE --}}
@if ($produits->hasPages())
<div class="pagination-wrap">

    <ul class="pagination">

        {{-- Bouton précédent --}}
        @if ($produits->onFirstPage())
            <li class="page-item disabled">
                <span class="page-link">‹</span>
            </li>
        @else
            <li class="page-item">
                <a class="page-link"
                   href="{{ $produits->previousPageUrl() }}">
                    ‹
                </a>
            </li>
        @endif

        {{-- Numéros --}}
        @foreach ($produits->getUrlRange(1, $produits->lastPage()) as $page => $url)

            @if ($page == $produits->currentPage())
                <li class="page-item active">
                    <span class="page-link">{{ $page }}</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $url }}">
                        {{ $page }}
                    </a>
                </li>
            @endif

        @endforeach

        {{-- Bouton suivant --}}
        @if ($produits->hasMorePages())
            <li class="page-item">
                <a class="page-link"
                   href="{{ $produits->nextPageUrl() }}">
                    ›
                </a>
            </li>
        @else
            <li class="page-item disabled">
                <span class="page-link">›</span>
            </li>
        @endif

    </ul>

</div>
@endif

    </div>
</section>

{{-- ===== INSTAGRAM ===== --}}
<section class="instagram-section">
    <div class="container text-center pb-4">
        <h3 style="font-size:28px;font-weight:800;color:var(--dark);">Suivez-nous sur Instagram</h3>
        <p class="text-muted mt-2">@freshmarket.bj — Arrivages du jour, promotions et coulisses</p>
    </div>
    <div class="insta-grid">
        <div class="insta-item"><img src="{{ asset('images/bare/poule.jpg') }}" style="width:100%;height:100%;object-fit:cover;" alt="Poule"><div class="insta-overlay"><i class="fab fa-instagram"></i></div></div>
        <div class="insta-item"><img src="{{ asset('images/bare/poisson.jpg') }}" style="width:100%;height:100%;object-fit:cover;" alt="Poisson"><div class="insta-overlay"><i class="fab fa-instagram"></i></div></div>
        <div class="insta-item"><img src="{{ asset('images/bare/lapin.jpg') }}" style="width:100%;height:100%;object-fit:cover;" alt="Lapin"><div class="insta-overlay"><i class="fab fa-instagram"></i></div></div>
        <div class="insta-item"><img src="{{ asset('images/bare/mouton.jpg') }}" style="width:100%;height:100%;object-fit:cover;" alt="Mouton"><div class="insta-overlay"><i class="fab fa-instagram"></i></div></div>
        <div class="insta-item"><img src="{{ asset('images/bare/boeuf.jpg') }}" style="width:100%;height:100%;object-fit:cover;" alt="Boeuf"><div class="insta-overlay"><i class="fab fa-instagram"></i></div></div>
    </div>
</section>

@endsection

@section('scripts')
<script>
            // ===== MODAL CALIBRE =====
            function openModal(produitId, nom, prix, imgUrl) {
                document.getElementById('modalNom').textContent  = nom;
                document.getElementById('modalPrix').textContent = parseInt(prix.replace(/\s/g,'')).toLocaleString('fr-FR');
                document.getElementById('modalProduitId').value  = produitId;
                document.getElementById('modalQty').value        = 1;
                document.getElementById('modalQuantite').value   = 1;
                document.getElementById('modalImg').innerHTML    = `<img src="${imgUrl}" class="modal-produit-img" alt="${nom}" onerror="this.src='{{ asset('images/img-pro-01.jpg') }}'">`;
                document.querySelectorAll('.calibre-btn').forEach(b => b.classList.remove('selected'));
                document.querySelectorAll('input[name="calibre_choix"]').forEach(r => r.checked = false);
                document.getElementById('modalCalibreId').value = '';
                document.getElementById('btnModalAdd').disabled = true;
                document.getElementById('calibreModal').classList.add('active');
                document.body.style.overflow = 'hidden';
            }

            function closeModal() {
                document.getElementById('calibreModal').classList.remove('active');
                document.body.style.overflow = '';
            }

            document.getElementById('calibreModal').addEventListener('click', function(e) { if (e.target === this) closeModal(); });
            document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });

            document.querySelectorAll('.calibre-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.querySelectorAll('.calibre-btn').forEach(b => b.classList.remove('selected'));
                    this.classList.add('selected');
                    const radio = this.querySelector('input[type="radio"]');
                    radio.checked = true;
                    document.getElementById('modalCalibreId').value = radio.value;
                    document.getElementById('btnModalAdd').disabled = false;
                });
            });

                function changeQty(delta) {
                    const input = document.getElementById('modalQty');
                    let val = Math.min(100, Math.max(1, parseInt(input.value) + delta));
                    input.value = val;
                    document.getElementById('modalQuantite').value = val;
                }
                document.getElementById('modalQty').addEventListener('input', function() {
                    document.getElementById('modalQuantite').value = this.value;
                });
</script>
@endsection