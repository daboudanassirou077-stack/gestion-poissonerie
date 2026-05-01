@extends('layouts.app')

@section('title', 'Boutique - FreshMarket Poissonnerie & Boucherie')

@section('styles')
<style>
    :root {
        --primary: #0a4f6e;
        --primary-dark: #073a52;
        --secondary: #e8830a;
        --accent: #c0392b;
        --green: #27ae60;
        --light-bg: #f4f9fc;
        --dark: #1a1a2e;
        --muted: #6c757d;
    }

    /* ===== PAGE HERO ===== */
    .page-hero {
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 60%, #1a7a9a 100%);
        padding: 50px 0;
        position: relative;
        overflow: hidden;
    }
    .page-hero::before {
        content: '🐟';
        position: absolute;
        font-size: 200px;
        opacity: .05;
        right: -20px;
        top: -30px;
        transform: rotate(-20deg);
    }
    .page-hero::after {
        content: '🥩';
        position: absolute;
        font-size: 150px;
        opacity: .05;
        left: 30px;
        bottom: -20px;
        transform: rotate(15deg);
    }
    .page-hero h1 { font-size: 38px; color: #fff; font-weight: 800; margin-bottom: 10px; }
    .breadcrumb { background: transparent; padding: 0; margin: 0; }
    .breadcrumb-item a { color: rgba(255,255,255,.85); text-decoration: none; }
    .breadcrumb-item.active { color: var(--secondary); }
    .breadcrumb-item + .breadcrumb-item::before { color: rgba(255,255,255,.4); }

    /* ===== LAYOUT BOUTIQUE ===== */
    .shop-wrapper { padding: 40px 0 60px; background: var(--light-bg); min-height: 70vh; }

    /* ===== SIDEBAR ===== */
    .shop-sidebar {
        background: #fff;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 3px 15px rgba(0,0,0,.07);
        position: sticky;
        top: 80px;
    }
    .sidebar-search {
        position: relative;
        margin-bottom: 24px;
    }
    .sidebar-search input {
        width: 100%;
        padding: 11px 14px 11px 38px;
        border: 1.5px solid #e5e5e5;
        border-radius: 10px;
        font-size: 14px;
        transition: border-color .3s;
    }
    .sidebar-search input:focus {
        outline: none;
        border-color: var(--primary);
    }
    .sidebar-search i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #aaa;
        font-size: 14px;
    }
    .filter-section { margin-bottom: 24px; }
    .filter-section h6 {
        font-size: 11px;
        font-weight: 700;
        color: var(--primary);
        text-transform: uppercase;
        letter-spacing: 1.2px;
        margin-bottom: 14px;
        padding-bottom: 8px;
        border-bottom: 2px solid var(--light-bg);
    }
    .filter-option {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 7px 0;
        border-bottom: 1px solid #f8f8f8;
    }
    .filter-option label {
        display: flex;
        align-items: center;
        gap: 9px;
        font-size: 14px;
        color: #444;
        cursor: pointer;
        flex: 1;
    }
    .filter-option input[type="checkbox"] {
        accent-color: var(--primary);
        width: 15px;
        height: 15px;
    }
    .filter-count {
        font-size: 11px;
        background: var(--light-bg);
        color: var(--muted);
        padding: 2px 8px;
        border-radius: 20px;
        font-weight: 600;
    }
    .price-slider { padding: 8px 0; }
    .price-slider input[type="range"] {
        width: 100%;
        accent-color: var(--secondary);
    }
    .price-display {
        display: flex;
        justify-content: space-between;
        font-size: 13px;
        color: var(--muted);
        margin-top: 6px;
    }
    .price-display strong { color: var(--primary); }
    .btn-filter-reset {
        width: 100%;
        padding: 10px;
        background: transparent;
        border: 1.5px solid #e0e0e0;
        border-radius: 8px;
        color: var(--muted);
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all .3s;
        margin-top: 10px;
    }
    .btn-filter-reset:hover { border-color: var(--accent); color: var(--accent); }

    /* ===== TOOLBAR ===== */
    .shop-toolbar {
        background: #fff;
        border-radius: 12px;
        padding: 14px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,.05);
        flex-wrap: wrap;
        gap: 12px;
    }
    .toolbar-info { font-size: 14px; color: var(--muted); }
    .toolbar-info strong { color: var(--primary); font-size: 15px; }
    .toolbar-right { display: flex; align-items: center; gap: 12px; }
    .sort-select {
        border: 1.5px solid #e0e0e0;
        border-radius: 8px;
        padding: 8px 14px;
        font-size: 13px;
        color: #444;
        background: #fff;
        cursor: pointer;
        transition: border-color .3s;
    }
    .sort-select:focus { outline: none; border-color: var(--primary); }
    .view-toggle { display: flex; gap: 5px; }
    .view-btn {
        width: 36px;
        height: 36px;
        border: 1.5px solid #e0e0e0;
        border-radius: 8px;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: #aaa;
        transition: all .3s;
    }
    .view-btn.active { background: var(--primary); border-color: var(--primary); color: #fff; }

    /* ===== CATÉGORIE ACTIVE BADGE ===== */
    .active-filters {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 16px;
    }
    .active-filter-tag {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(10,79,110,.08);
        color: var(--primary);
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
    }
    .active-filter-tag button {
        background: none;
        border: none;
        color: var(--primary);
        cursor: pointer;
        font-size: 14px;
        line-height: 1;
        padding: 0;
    }

    /* ===== PRODUITS ===== */
    .products-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 18px;
    }
    .products-list .product-card {
        display: flex;
        flex-direction: row;
        height: 130px;
    }
    .products-list .product-img-wrap { width: 130px; flex-shrink: 0; height: 130px; }
    .products-list .product-body { flex: 1; display: flex; flex-direction: column; justify-content: space-between; }

    .product-card {
        background: #fff;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 3px 12px rgba(0,0,0,.07);
        transition: all .3s ease;
        position: relative;
    }
    .product-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 10px 35px rgba(0,0,0,.13);
    }
    .product-img-wrap {
        position: relative;
        overflow: hidden;
        height: 200px;
    }
    .product-img-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform .4s ease;
    }
    .product-card:hover .product-img-wrap img { transform: scale(1.08); }

    .product-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .5px;
        z-index: 2;
    }
    .badge-fresh { background: var(--green); color: #fff; }
    .badge-promo { background: var(--accent); color: #fff; }
    .badge-new { background: var(--secondary); color: #fff; }
    .badge-local { background: #8B4513; color: #fff; }

    .product-actions {
        position: absolute;
        top: 10px;
        right: 10px;
        display: flex;
        flex-direction: column;
        gap: 6px;
        opacity: 0;
        transform: translateX(10px);
        transition: all .3s ease;
        z-index: 2;
    }
    .product-card:hover .product-actions { opacity: 1; transform: translateX(0); }
    .product-actions a {
        width: 34px;
        height: 34px;
        background: #fff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
        font-size: 13px;
        box-shadow: 0 2px 8px rgba(0,0,0,.15);
        transition: all .2s;
        text-decoration: none;
    }
    .product-actions a:hover { background: var(--primary); color: #fff; }

    .product-body { padding: 14px 16px; }
    .product-category {
        font-size: 10px;
        color: var(--secondary);
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .8px;
        margin-bottom: 5px;
    }
    .product-body h5 {
        font-size: 14px;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 10px;
        line-height: 1.4;
    }
    .product-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .product-price { font-size: 16px; font-weight: 800; color: var(--primary); }
    .product-price small { font-size: 11px; color: var(--muted); font-weight: 400; }
    .btn-cart {
        background: var(--primary);
        color: #fff;
        border: none;
        padding: 7px 14px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        transition: all .3s;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .btn-cart:hover { background: var(--secondary); transform: scale(1.05); }

    /* ===== PAGINATION ===== */
    .pagination-wrap { display: flex; justify-content: center; margin-top: 35px; }
    .pagination { gap: 5px; }
    .page-link {
        border: 1.5px solid #e0e0e0;
        color: var(--dark);
        border-radius: 8px !important;
        width: 38px;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        font-weight: 600;
        transition: all .3s;
    }
    .page-link:hover { border-color: var(--primary); color: var(--primary); background: #fff; }
    .page-item.active .page-link { background: var(--primary); border-color: var(--primary); color: #fff; }

    /* ===== EMPTY STATE ===== */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: var(--muted);
    }
    .empty-state .empty-icon { font-size: 60px; margin-bottom: 20px; }
    .empty-state h4 { font-size: 20px; color: var(--dark); margin-bottom: 10px; }
    .empty-state p { font-size: 15px; }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 991px) {
        .shop-sidebar { position: static; margin-bottom: 20px; }
        .products-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 576px) {
        .products-grid { grid-template-columns: 1fr; }
        .page-hero h1 { font-size: 28px; }
        .shop-toolbar { flex-direction: column; align-items: flex-start; }
    }
</style>
@endsection

@section('content')

{{-- ===== PAGE HERO ===== --}}
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

{{-- ===== BOUTIQUE ===== --}}
<section class="shop-wrapper">
    <div class="container">
        <div class="row">

            {{-- ===== SIDEBAR ===== --}}
            <div class="col-lg-3 mb-4 mb-lg-0">
                <div class="shop-sidebar">

                    {{-- Recherche --}}
                    <form action="{{ route('shop') }}" method="GET">
                        <div class="sidebar-search">
                            <i class="fas fa-search"></i>
                            <input
                                type="text"
                                name="q"
                                placeholder="Rechercher un produit..."
                                value="{{ request('q') }}"
                            >
                        </div>

                        {{-- Catégories --}}
                        <div class="filter-section">
                            <h6>Catégories</h6>

                           @php
                                $categories = \App\Models\Categorie::all()->keyBy('slug');
                           @endphp

                            @foreach($categories as $slug => $cat)
                            <div class="filter-option">
                                <label>
                                    <input
                                        type="radio"
                                        name="categorie"
                                        value="{{ $slug }}"
                                        {{ request('categorie') === $slug ? 'checked' : '' }}
                                        style="accent-color: var(--primary);"
                                    >
                                    {{ $cat['icon'] }} {{ $cat['label'] }}
                                </label>
                            </div>
                            @endforeach
                        </div>

                        {{-- Prix --}}
                        <div class="filter-section">
                            <h6>Prix maximum (FCFA/kg)</h6>
                            <div class="price-slider">
                                <input
                                    type="range"
                                    name="prix_max"
                                    min="500"
                                    max="15000"
                                    step="500"
                                    value="{{ request('prix_max', 15000) }}"
                                    id="priceRange"
                                    oninput="document.getElementById('priceVal').textContent = parseInt(this.value).toLocaleString('fr-FR')"
                                >
                                <div class="price-display">
                                    <span>500 FCFA</span>
                                    <strong><span id="priceVal">{{ number_format(request('prix_max', 15000), 0, ',', ' ') }}</span> FCFA</strong>
                                </div>
                            </div>
                        </div>

                        {{-- Disponibilité --}}
                        <div class="filter-section">
                            <h6>Disponibilité</h6>
                            <div class="filter-option">
                                <label>
                                    <input type="checkbox" name="en_stock" value="1" {{ request('en_stock') ? 'checked' : '' }}>
                                    ✅ En stock uniquement
                                </label>
                            </div>
                            <div class="filter-option">
                                <label>
                                    <input type="checkbox" name="promo" value="1" {{ request('promo') ? 'checked' : '' }}>
                                    🏷️ Promotions du jour
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn-cart w-100" style="justify-content:center; padding:10px;">
                            <i class="fas fa-filter"></i> Appliquer les filtres
                        </button>
                        <a href="{{ route('shop') }}" class="btn-filter-reset">
                            <i class="fas fa-times"></i> Réinitialiser
                        </a>
                    </form>

                </div>
            </div>

            {{-- ===== PRODUITS ===== --}}
            <div class="col-lg-9">

                {{-- Filtre actif --}}
                @if(request('categorie') || request('q'))
                <div class="active-filters mb-3">
                    @if(request('categorie'))
                    <span class="active-filter-tag">
                        {{ $categories[request('categorie')]['icon'] ?? '' }}
                        {{ $categories[request('categorie')]['label'] ?? request('categorie') }}
                        <a href="{{ route('shop', array_merge(request()->except('categorie'))) }}" style="color:inherit; text-decoration:none;">✕</a>
                    </span>
                    @endif
                    @if(request('q'))
                    <span class="active-filter-tag">
                        🔍 "{{ request('q') }}"
                        <a href="{{ route('shop', array_merge(request()->except('q'))) }}" style="color:inherit; text-decoration:none;">✕</a>
                    </span>
                    @endif
                </div>
                @endif

                {{-- Toolbar --}}
                <div class="shop-toolbar">
                    <div class="toolbar-info">
                        <strong>{{ is_array($produits) ? count($produits) : $produits->count() }}</strong> produit(s) trouvé(s)
                        @if(request('categorie'))
                            dans <strong>{{ $categories[request('categorie')]['label'] ?? '' }}</strong>
                        @endif
                    </div>
                    <div class="toolbar-right">
                        <form action="{{ route('shop') }}" method="GET" style="display:inline;">
                            @foreach(request()->except('tri') as $key => $val)
                                <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                            @endforeach
                            <select name="tri" class="sort-select" onchange="this.form.submit()">
                                <option value="popularite" {{ request('tri') == 'popularite' ? 'selected' : '' }}>Popularité</option>
                                <option value="prix_asc"   {{ request('tri') == 'prix_asc'   ? 'selected' : '' }}>Prix croissant</option>
                                <option value="prix_desc"  {{ request('tri') == 'prix_desc'  ? 'selected' : '' }}>Prix décroissant</option>
                                <option value="nouveautes" {{ request('tri') == 'nouveautes' ? 'selected' : '' }}>Nouveautés</option>
                            </select>
                        </form>
                        <div class="view-toggle">
                            <button class="view-btn active" id="gridBtn" onclick="setView('grid')" title="Grille">
                                <i class="fas fa-th"></i>
                            </button>
                            <button class="view-btn" id="listBtn" onclick="setView('list')" title="Liste">
                                <i class="fas fa-list"></i>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Grille produits --}}
                <div class="products-grid" id="productsContainer">

                    @forelse($produits as $produit)
                    <div class="product-card">
                        <div class="product-img-wrap">
                            <img
                                src="{{ asset('images/produits/' . ($produit->image ?? 'default.jpg')) }}"
                                alt="{{ $produit->libelle_prod }}"
                                loading="lazy"
                            >
                            <span class="product-badge badge-fresh">Frais</span>
                            <div class="product-actions">
                                <a href="{{ route('shop.show', $produit->id_prod) }}" title="Voir le détail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('wishlist.add', $produit->id_prod) }}" title="Favoris">
                                    <i class="far fa-heart"></i>
                                </a>
                            </div>
                        </div>
                        <div class="product-body">
                            <p class="product-category">{{ $produit->categorie->libelle ?? 'Produit' }}</p>
                            <h5>{{ $produit->libelle_prod }}</h5>
                            <div class="product-footer">
                                <div class="product-price">
                                    {{ number_format($produit->prix, 0, ',', ' ') }} FCFA
                                    <small>/ {{ $produit->calibre->unite_vente ?? 'kg' }}</small>
                                </div>
                                <form action="{{ route('cart.add') }}" method="POST" style="display:inline">
                                    @csrf
                                    <input type="hidden" name="produit_id" value="{{ $produit->id_prod }}">
                                    <input type="hidden" name="quantite" value="1">
                                    <button type="submit" class="btn-cart">
                                        <i class="fas fa-cart-plus"></i> Ajouter
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty

                    {{-- ===== PRODUITS DE DÉMONSTRATION ===== --}}
                    @php
                        $demoProducts = [
                            ['id' => 1, 'img' => 'img-pro-01.jpg', 'cat' => 'Poissons frais',  'nom' => 'Silure frais du lac',        'prix' => '1 500', 'unite' => 'kg',  'badge' => 'fresh', 'label' => 'Frais'],
                            ['id' => 2, 'img' => 'img-pro-02.jpg', 'cat' => 'Viandes de bœuf', 'nom' => 'Entrecôte de bœuf',          'prix' => '3 500', 'unite' => 'kg',  'badge' => 'new',   'label' => 'Nouveau'],
                            ['id' => 3, 'img' => 'img-pro-03.jpg', 'cat' => 'Volailles',       'nom' => 'Cuisses de poulet frais',    'prix' => '2 000', 'unite' => 'kg',  'badge' => 'promo', 'label' => 'Promo'],
                            ['id' => 4, 'img' => 'img-pro-04.jpg', 'cat' => 'Escargots',       'nom' => 'Escargots africains séchés', 'prix' => '2 500', 'unite' => 'kg',  'badge' => 'local', 'label' => 'Local'],
                            ['id' => 5, 'img' => 'img-pro-01.jpg', 'cat' => 'Poissons fumés',  'nom' => 'Tilapia fumé entier',        'prix' => '2 200', 'unite' => 'kg',  'badge' => 'fresh', 'label' => 'Séché'],
                            ['id' => 6, 'img' => 'img-pro-03.jpg', 'cat' => 'Volailles',       'nom' => 'Poulet entier fermier',      'prix' => '2 800', 'unite' => 'pce', 'badge' => 'new',   'label' => 'Nouveau'],
                            ['id' => 7, 'img' => 'img-pro-02.jpg', 'cat' => 'Abats',           'nom' => 'Gésiers de poulet frais',   'prix' => '1 800', 'unite' => 'kg',  'badge' => 'fresh', 'label' => 'Frais'],
                            ['id' => 8, 'img' => 'img-pro-04.jpg', 'cat' => 'Viandes de bœuf', 'nom' => 'Côtes de bœuf',             'prix' => '4 000', 'unite' => 'kg',  'badge' => 'promo', 'label' => 'Promo'],
                            ['id' => 9, 'img' => 'img-pro-01.jpg', 'cat' => 'Poissons frais',  'nom' => 'Capitaine frais',            'prix' => '2 000', 'unite' => 'kg',  'badge' => 'fresh', 'label' => 'Frais'],
                        ];
                     @endphp

                    @foreach($demoProducts as $p)
                    <div class="product-card">
                        <div class="product-img-wrap">
                            <img src="{{ asset('images/' . $p['img']) }}" alt="{{ $p['nom'] }}" loading="lazy">
                            <span class="product-badge badge-{{ $p['badge'] }}">{{ $p['label'] }}</span>
                            <div class="product-actions">
                                <a href="{{ route('shop') }}" title="Voir"><i class="fas fa-eye"></i></a>
                                <a href="#" title="Favoris"><i class="far fa-heart"></i></a>
                            </div>
                        </div>
                        <div class="product-body">
                            <p class="product-category">{{ $p['cat'] }}</p>
                            <h5>{{ $p['nom'] }}</h5>
                            <div class="product-footer">
                                <div class="product-price">
                                    {{ $p['prix'] }} FCFA
                                    <small>/ {{ $p['unite'] }}</small>
                                </div>
                                <form action="{{ route('cart.add') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="produit_id" value="4">
                                    <input type="hidden" name="quantite" value="1">
                                    <div class="product-footer">
                                        <div class="product-price">
                                            {{ $p['prix'] }} FCFA
                                            <small>/ {{ $p['unite'] }}</small>
                                        </div>
                                        {{-- Remplacez le <a> par ce <form> --}}
                                        <form action="{{ route('cart.add') }}" method="POST" style="display:inline;">
                                            @csrf
                                            <input type="hidden" name="produit_id" value="{{ $p['id'] }}">
                                            <input type="hidden" name="quantite" value="1">
                                            <button type="submit" class="btn-cart">
                                                <i class="fas fa-cart-plus"></i> Ajouter
                                            </button>
                                        </form>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach

                    @endforelse

                </div>

                {{-- Pagination (si Eloquent paginate()) --}}
                @if(isset($produits) && !is_array($produits) && method_exists($produits, 'links'))
                <div class="pagination-wrap mt-4">
                    {{ $produits->appends(request()->query())->links() }}
                </div>
                @endif

            </div>
        </div>
    </div>
</section>

@endsection

@section('scripts')
<script>
    // Vue grille / liste
    function setView(mode) {
        const container = document.getElementById('productsContainer');
        const gridBtn = document.getElementById('gridBtn');
        const listBtn = document.getElementById('listBtn');
        if (mode === 'list') {
            container.classList.remove('products-grid');
            container.classList.add('products-list');
            container.style.gridTemplateColumns = '1fr';
            listBtn.classList.add('active');
            gridBtn.classList.remove('active');
        } else {
            container.classList.add('products-grid');
            container.classList.remove('products-list');
            container.style.gridTemplateColumns = '';
            gridBtn.classList.add('active');
            listBtn.classList.remove('active');
        }
    }

    // Mise à jour affichage prix
    const range = document.getElementById('priceRange');
    const priceVal = document.getElementById('priceVal');
    if (range && priceVal) {
        range.addEventListener('input', function () {
            priceVal.textContent = parseInt(this.value).toLocaleString('fr-FR');
        });
    }
</script>
@endsection
