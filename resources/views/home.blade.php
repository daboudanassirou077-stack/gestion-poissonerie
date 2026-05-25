@extends('layouts.app')

@section('title', 'FreshMarket - Poissonnerie & Boucherie en ligne')

@section('styles')
<style>
    :root {
        --primary: #0a4f6e; --secondary: #e8830a; --accent: #c0392b;
        --light-bg: #f4f9fc; --dark: #1a1a2e; --text-muted: #6c757d;
        --green: #27ae60;
    }
    body { font-family: 'Segoe UI', sans-serif; background: #fff; }

    /* HERO */
    .hero-section { position:relative; background:linear-gradient(135deg,#0a4f6e 0%,#1a7a9a 50%,#e8830a 100%); min-height:550px; display:flex; align-items:center; overflow:hidden; }
    .hero-section::before { content:''; position:absolute; inset:0; background:rgba(10,79,110,0.7); z-index:1; }
    .hero-bg { position:absolute; inset:0; background-size:cover; background-position:center; animation:zoomIn 10s ease infinite alternate; }
    @keyframes zoomIn { from{transform:scale(1)} to{transform:scale(1.08)} }
    .hero-content { position:relative; z-index:2; color:#fff; text-align:center; padding:60px 20px; }
    .hero-badge { display:inline-block; background:var(--secondary); color:#fff; padding:6px 18px; border-radius:30px; font-size:13px; font-weight:600; letter-spacing:1px; text-transform:uppercase; margin-bottom:20px; }
    .hero-content h1 { font-size:52px; font-weight:800; line-height:1.2; margin-bottom:20px; text-shadow:2px 2px 10px rgba(0,0,0,0.3); }
    .hero-content h1 span { color:var(--secondary); }
    .hero-content p { font-size:18px; opacity:0.9; margin-bottom:35px; max-width:600px; margin-left:auto; margin-right:auto; }
    .hero-btns a { display:inline-block; padding:14px 35px; border-radius:50px; font-weight:700; text-decoration:none; margin:5px; transition:all 0.3s; font-size:15px; }
    .btn-hero-primary { background:var(--secondary); color:#fff; border:2px solid var(--secondary); }
    .btn-hero-primary:hover { background:transparent; color:var(--secondary); }
    .btn-hero-outline { background:transparent; color:#fff; border:2px solid #fff; }
    .btn-hero-outline:hover { background:#fff; color:var(--primary); }

    /* STATS BAR */
    .stats-bar { background:var(--primary); padding:20px 0; }
    .stat-item { text-align:center; color:#fff; padding:10px; border-right:1px solid rgba(255,255,255,0.2); }
    .stat-item:last-child { border-right:none; }
    .stat-item h3 { font-size:28px; font-weight:800; color:var(--secondary); margin:0; }
    .stat-item p { font-size:13px; margin:0; opacity:0.85; }

    /* SECTION TITLE */
    .section-title { text-align:center; margin-bottom:50px; }
    .section-title .badge-label { display:inline-block; background:rgba(232,131,10,0.1); color:var(--secondary); padding:5px 16px; border-radius:30px; font-size:13px; font-weight:600; text-transform:uppercase; letter-spacing:1px; margin-bottom:12px; }
    .section-title h2 { font-size:36px; font-weight:800; color:var(--dark); margin-bottom:15px; }
    .section-title p { color:var(--text-muted); font-size:16px; max-width:550px; margin:0 auto; }
    .section-divider { width:60px; height:4px; background:linear-gradient(90deg,var(--primary),var(--secondary)); border-radius:2px; margin:15px auto 0; }

    /* CATÉGORIES */
    .categories-section { padding:70px 0; background:var(--light-bg); }
    .cat-card { background:#fff; border-radius:16px; overflow:hidden; box-shadow:0 4px 20px rgba(0,0,0,0.07); transition:all 0.35s ease; margin-bottom:25px; display:block; text-decoration:none; }
    .cat-card:hover { transform:translateY(-8px); box-shadow:0 12px 40px rgba(0,0,0,0.15); }
    .cat-card-img { height:440px; width:100%; overflow:hidden; position:relative; }
    .cat-card-img img { width:100%; height:100%; object-fit:cover; transition:transform 0.4s ease; }
    .cat-card:hover .cat-card-img img { transform:scale(1.1); }
    .cat-card-overlay { position:absolute; inset:0; background:linear-gradient(to top,rgba(10,79,110,0.85) 0%,transparent 60%); }
    .cat-card-body { padding:16px 20px; display:flex; align-items:center; justify-content:space-between; }
    .cat-card-body h5 { font-size:16px; font-weight:700; color:var(--dark); margin:0; }
    .cat-card-body span { font-size:12px; color:var(--text-muted); display:block; margin-top:3px; }
    .cat-icon { width:40px; height:40px; background:rgba(10,79,110,0.1); border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:18px; flex-shrink:0; }

    /* PRODUITS */
    .products-section { padding:70px 0; background:#fff; }
    .filter-btns { margin-bottom:35px; text-align:center; }
    .filter-btns button { background:transparent; border:2px solid #e0e0e0; color:#666; padding:9px 22px; border-radius:30px; margin:4px; cursor:pointer; font-weight:600; font-size:14px; transition:all 0.3s; }
    .filter-btns button.active, .filter-btns button:hover { background:var(--primary); border-color:var(--primary); color:#fff; }
    .product-card { background:#fff; border-radius:16px; overflow:hidden; box-shadow:0 3px 15px rgba(0,0,0,0.07); transition:all 0.3s ease; margin-bottom:25px; position:relative; }
    .product-card:hover { transform:translateY(-6px); box-shadow:0 10px 35px rgba(0,0,0,0.13); }
    .product-img-wrap { position:relative; overflow:hidden; height:300px; }
    .product-img-wrap img { width:100%; height:100%; object-fit:cover; transition:transform 0.4s ease; }
    .product-card:hover .product-img-wrap img { transform:scale(1.08); }
    .product-badge { position:absolute; top:12px; left:12px; padding:4px 12px; border-radius:20px; font-size:11px; font-weight:700; text-transform:uppercase; z-index:2; }
    .badge-fresh   { background:#27ae60; color:#fff; }
    .badge-promo   { background:var(--accent); color:#fff; }
    .badge-new     { background:var(--secondary); color:#fff; }
    .badge-poisson { background:rgba(10,79,110,0.9); color:#fff; }
    .product-body { padding:16px 18px; }
    .product-category { font-size:11px; color:var(--secondary); font-weight:600; text-transform:uppercase; letter-spacing:0.8px; margin-bottom:6px; }
    .product-body h5 { font-size:15px; font-weight:700; color:var(--dark); margin-bottom:6px; line-height:1.4; }
    .product-desc { font-size:12px; color:var(--text-muted); margin-bottom:10px; line-height:1.5; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; }
    .product-footer { display:flex; align-items:center; justify-content:space-between; }
    .product-price { font-size:18px; font-weight:800; color:var(--primary); }
    .product-price small { font-size:12px; color:var(--text-muted); font-weight:400; }
    .btn-cart { background:var(--primary); color:#fff; border:none; padding:8px 16px; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer; transition:all 0.3s; text-decoration:none; display:inline-flex; align-items:center; gap:5px; }
    .btn-cart:hover { background:var(--secondary); color:#fff; transform:scale(1.05); }
    .btn-cart-poisson { background:linear-gradient(135deg,#0a4f6e,#1a7fb5); }
    .btn-cart-poisson:hover { background:linear-gradient(135deg,#e8830a,#f0a83a); }

    /* PROMO */
    .promo-section { padding:50px 0; background:var(--light-bg); }
    .promo-card { border-radius:16px; overflow:hidden; position:relative; height:200px; margin-bottom:25px; }
    .promo-card img { width:100%; height:100%; object-fit:cover; transition:transform 0.4s; }
    .promo-card:hover img { transform:scale(1.05); }
    .promo-overlay { position:absolute; inset:0; background:linear-gradient(135deg,rgba(10,79,110,0.88) 0%,rgba(10,79,110,0.4) 100%); display:flex; flex-direction:column; justify-content:center; padding:25px; }
    .promo-overlay h4 { color:#fff; font-size:20px; font-weight:800; margin:0 0 6px; }
    .promo-overlay p { color:rgba(255,255,255,0.85); font-size:13px; margin:0 0 15px; }
    .promo-overlay a { display:inline-block; background:var(--secondary); color:#fff; padding:7px 18px; border-radius:20px; font-size:13px; font-weight:600; text-decoration:none; }
    .promo-overlay a:hover { background:#fff; color:var(--primary); }

    /* WHY US */
    .why-us { padding:70px 0; background:#fff; }
    .why-card { text-align:center; padding:35px 20px; border-radius:16px; transition:all 0.3s; margin-bottom:25px; }
    .why-card:hover { background:var(--light-bg); transform:translateY(-5px); }
    .why-icon { width:70px; height:70px; background:linear-gradient(135deg,var(--primary),#1a7a9a); border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:28px; color:#fff; margin:0 auto 20px; box-shadow:0 8px 20px rgba(10,79,110,0.3); }
    .why-card h5 { font-size:17px; font-weight:700; color:var(--dark); margin-bottom:10px; }
    .why-card p { font-size:14px; color:var(--text-muted); line-height:1.7; }

    /* TÉMOIGNAGES */
    .testimonials { padding:70px 0; background:var(--primary); }
    .testimonials .section-title h2 { color:#fff; }
    .testimonials .section-title p { color:rgba(255,255,255,0.75); }
    .testi-card { background:rgba(255,255,255,0.08); border:1px solid rgba(255,255,255,0.15); border-radius:16px; padding:30px; margin-bottom:25px; }
    .testi-stars { color:var(--secondary); margin-bottom:15px; font-size:15px; }
    .testi-text { color:rgba(255,255,255,0.9); font-size:15px; line-height:1.7; font-style:italic; margin-bottom:20px; }
    .testi-author { display:flex; align-items:center; gap:12px; }
    .testi-avatar { width:46px; height:46px; background:var(--secondary); border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:800; color:#fff; font-size:18px; }
    .testi-name { font-weight:700; color:#fff; font-size:15px; }
    .testi-role { font-size:12px; color:rgba(255,255,255,0.6); }

    /* NEWSLETTER */
    .newsletter-section { padding:60px 0; background:linear-gradient(135deg,var(--secondary) 0%,#d4720a 100%); }
    .newsletter-section h2 { color:#fff; font-weight:800; font-size:32px; }
    .newsletter-section p { color:rgba(255,255,255,0.9); font-size:16px; }
    .newsletter-form { display:flex; gap:10px; max-width:500px; margin:0 auto; }
    .newsletter-form input { flex:1; padding:14px 20px; border:none; border-radius:50px; font-size:15px; outline:none; }
    .newsletter-form button { background:var(--primary); color:#fff; border:none; padding:14px 28px; border-radius:50px; font-weight:700; cursor:pointer; white-space:nowrap; }
    .newsletter-form button:hover { background:var(--dark); }

    /* MODAL CALIBRE */
    .modal-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,0.55); z-index:9999; align-items:center; justify-content:center; backdrop-filter:blur(4px); }
    .modal-overlay.active { display:flex; }
    .modal-box { background:#fff; border-radius:20px; width:90%; max-width:480px; overflow:hidden; box-shadow:0 20px 60px rgba(0,0,0,0.25); animation:modalIn .25s ease; }
    @keyframes modalIn { from{transform:scale(.9);opacity:0} to{transform:scale(1);opacity:1} }
    .modal-header { background:linear-gradient(135deg,#073a52,#0a4f6e); padding:20px 24px; display:flex; align-items:center; justify-content:space-between; }
    .modal-header h4 { color:#fff; font-size:16px; font-weight:800; margin:0; }
    .modal-close { background:rgba(255,255,255,0.15); border:none; color:#fff; width:32px; height:32px; border-radius:50%; cursor:pointer; font-size:16px; display:flex; align-items:center; justify-content:center; }
    .modal-close:hover { background:rgba(255,255,255,0.3); }
    .modal-body { padding:24px; }
    .modal-produit-info { display:flex; align-items:center; gap:14px; margin-bottom:22px; padding-bottom:18px; border-bottom:2px solid var(--light-bg); }
    .modal-produit-img { width:70px; height:70px; border-radius:12px; object-fit:cover; flex-shrink:0; }
    .modal-produit-name { font-size:16px; font-weight:800; color:var(--dark); margin-bottom:4px; }
    .modal-produit-price { font-size:18px; font-weight:800; color:var(--primary); }
    .modal-produit-price small { font-size:12px; color:var(--text-muted); font-weight:400; }
    .calibre-label { font-size:12px; font-weight:700; color:var(--text-muted); text-transform:uppercase; letter-spacing:.8px; margin-bottom:12px; display:block; }
    .calibre-options { display:grid; grid-template-columns:repeat(3,1fr); gap:10px; margin-bottom:20px; }
    .calibre-btn { border:2px solid #e0eaf0; background:#fff; border-radius:12px; padding:12px 8px; cursor:pointer; text-align:center; transition:all .2s; }
    .calibre-btn:hover { border-color:var(--primary); background:rgba(10,79,110,0.04); }
    .calibre-btn.selected { border-color:var(--primary); background:rgba(10,79,110,0.08); }
    .calibre-btn .c-icon { font-size:22px; display:block; margin-bottom:5px; }
    .calibre-btn .c-name { font-size:13px; font-weight:800; color:var(--dark); display:block; }
    .calibre-btn .c-poids { font-size:10px; color:var(--text-muted); display:block; margin-top:2px; }
    .calibre-btn input[type="radio"] { display:none; }
    .quantite-label { font-size:12px; font-weight:700; color:var(--text-muted); text-transform:uppercase; letter-spacing:.8px; margin-bottom:12px; display:block; }
    .quantite-ctrl { display:flex; align-items:center; gap:10px; margin-bottom:20px; }
    .qty-btn { width:36px; height:36px; border:2px solid #e0eaf0; background:#fff; border-radius:8px; font-size:18px; cursor:pointer; display:flex; align-items:center; justify-content:center; color:var(--primary); font-weight:700; transition:all .2s; }
    .qty-btn:hover { background:var(--primary); color:#fff; border-color:var(--primary); }
    .qty-input { width:60px; text-align:center; border:2px solid #e0eaf0; border-radius:8px; padding:7px; font-size:15px; font-weight:700; color:var(--dark); outline:none; }
    .qty-input:focus { border-color:var(--primary); }
    .qty-unite { font-size:13px; color:var(--text-muted); font-weight:600; }
    .modal-footer { padding:0 24px 24px; }
    .btn-modal-add { width:100%; padding:14px; background:linear-gradient(135deg,#0a4f6e,#1a7fb5); color:#fff; border:none; border-radius:12px; font-size:15px; font-weight:800; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:8px; transition:all .3s; }
    .btn-modal-add:hover { background:linear-gradient(135deg,#e8830a,#f0a83a); transform:translateY(-1px); }
    .btn-modal-add:disabled { opacity:.5; cursor:not-allowed; transform:none; }

    @media(max-width:768px) {
        .hero-content h1 { font-size:32px; }
        .newsletter-form { flex-direction:column; }
        .stat-item { border-right:none; border-bottom:1px solid rgba(255,255,255,0.2); }
    }
</style>
@endsection

@section('content')

{{-- HERO --}}
<section class="hero-section">
    <div class="hero-bg" style="background-image:url('{{ asset('images/banner-01.jpg') }}')"></div>
    <div class="container hero-content">
        <span class="hero-badge">🐟 Frais du jour — Livraison rapide</span>
        <h1>Votre <span>Marché Protéiné</span><br>en ligne au Bénin</h1>
        <p>Poissons frais, viandes de qualité, volailles, escargots et bien plus — livrés directement chez vous à Cotonou.</p>
        <div class="hero-btns">
            <a href="{{ route('shop') }}" class="btn-hero-primary">🛒 Commander maintenant</a>
            <a href="{{ route('about') }}" class="btn-hero-outline">En savoir plus</a>
        </div>
    </div>
</section>

{{-- STATS --}}
<div class="stats-bar">
    <div class="container">
        <div class="row">
            <div class="col-6 col-md-3 stat-item"><h3>500+</h3><p>Clients satisfaits</p></div>
            <div class="col-6 col-md-3 stat-item"><h3>50+</h3><p>Produits disponibles</p></div>
            <div class="col-6 col-md-3 stat-item"><h3>7j/7</h3><p>Livraison disponible</p></div>
            <div class="col-6 col-md-3 stat-item"><h3>100%</h3><p>Produits frais garantis</p></div>
        </div>
    </div>
</div>

{{-- CATÉGORIES DYNAMIQUES --}}
<section class="categories-section">
    <div class="container">
        <div class="section-title">
            <span class="badge-label">Nos Rayons</span>
            <h2>Toutes nos catégories</h2>
            <p>Des produits frais sélectionnés chaque matin pour vous</p>
            <div class="section-divider"></div>
        </div>
       @php
            $catIcons = [
                'Poissons frais'   => ['icon'=>'🐟', 'desc'=>'Silure, Tilapia, Capitaine...'],
                'Poissons fumés'   => ['icon'=>'🔥', 'desc'=>'Fumé, séché, transformé...'],
                'Viandes de bœuf'  => ['icon'=>'🥩', 'desc'=>'Entrecôte, Côtes, Brisket...'],
                'Volailles'        => ['icon'=>'🍗', 'desc'=>'Poulet, Dinde, Pintade...'],
                'Escargots'        => ['icon'=>'🐌', 'desc'=>'Frais, séchés, au four...'],
                'Abats'            => ['icon'=>'🫀', 'desc'=>'Tripes, Gésiers, Pattes...'],
            ];
            @endphp
            <div class="row">
                @forelse($categories as $cat)
                @php
                    $cfg    = $catIcons[$cat->libelle] ?? ['icon'=>'📦', 'desc'=>''];
                    $imgUrl = $cat->image_url;
                @endphp
                <div class="col-lg-4 col-md-6">
                    <a href="{{ route('shop', ['categorie' => $cat->slug]) }}" class="cat-card">
                        <div class="cat-card-img">
                            <img src="{{ $imgUrl }}" alt="{{ $cat->libelle }}"
                                onerror="this.src='{{ asset('images/img-pro-01.jpg') }}'">
                            <div class="cat-card-overlay"></div>
                        </div>
                        <div class="cat-card-body">
                            <div>
                                <h5>{{ $cat->libelle }}</h5>
                                <span>{{ $cfg['desc'] }} — {{ $cat->produits_count }} produit(s)</span>
                            </div>
                            <div class="cat-icon">{{ $cfg['icon'] }}</div>
                        </div>
                    </a>
                </div>
                            @empty
                <div class="col-12 text-center py-4">
                    <p style="color:var(--text-muted);">Aucune catégorie disponible.</p>
                </div>
                @endforelse
            </div>
        
    </div>
</section>

{{-- BANNIÈRES PROMO --}}
<section class="promo-section">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="promo-card">
                    <img src="{{ asset('images/add-img-01.jpg') }}" alt="Promo poisson">
                    <div class="promo-overlay">
                        <h4>🐟 Arrivage du jour</h4>
                        <p>Poissons frais livrés chaque matin directement des pêcheurs</p>
                        <a href="{{ route('shop') }}">Voir les poissons →</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="promo-card">
                    <img src="{{ asset('images/add-img-02.jpg') }}" alt="Promo viande">
                    <div class="promo-overlay">
                        <h4>🥩 Viandes Premium</h4>
                        <p>Sélection de viandes de qualité supérieure à prix abordables</p>
                        <a href="{{ route('shop') }}">Voir les viandes →</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- MODAL CALIBRE POISSON --}}
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

{{-- PRODUITS VEDETTES --}}
<section class="products-section">
    <div class="container">
        <div class="section-title">
            <span class="badge-label">Sélection du moment</span>
            <h2>Nos Produits Vedettes</h2>
            <p>Les produits les plus appréciés par nos clients</p>
            <div class="section-divider"></div>
        </div>

        {{-- Filtres dynamiques --}}
        <div class="filter-btns">
            <button class="active" data-filter="*">Tous</button>
            @foreach($categories as $cat)
            <button data-filter=".{{ Str::slug($cat->libelle) }}">{{ $cat->libelle }}</button>
            @endforeach
        </div>

        <div class="row special-list">
            @forelse($produits as $produit)
            @php
                $estPoisson = $produit->calibre && $produit->calibre->type_produit === 'poisson';
                $imgUrl     = $produit->image
                    ? asset('images/produits/' . $produit->image)
                    : asset('images/img-pro-01.jpg');
            @endphp
            <div class="col-lg-4 col-md-6 special-grid {{ Str::slug($produit->categorie->libelle ?? '') }}">
                <div class="product-card">
                    <div class="product-img-wrap">
                        <img src="{{ $imgUrl }}" alt="{{ $produit->libelle_prod }}"
                             onerror="this.src='{{ asset('images/img-pro-01.jpg') }}'">

                        {{-- Badge --}}
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
                                {{-- Poisson → modal calibre --}}
                                <button type="button" class="btn-cart btn-cart-poisson"
                                    onclick="openModal(
                                        {{ $produit->id_prod }},
                                        '{{ addslashes($produit->libelle_prod) }}',
                                        '{{ number_format($produit->prix, 0) }}',
                                        '{{ $imgUrl }}'
                                    )">
                                    <i class="fas fa-fish"></i> Choisir
                                </button>
                            @else
                                {{-- Autre produit → ajout direct --}}
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
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <span style="font-size:60px;display:block;margin-bottom:20px;">🐟</span>
                <h4 style="color:var(--dark);font-weight:700;">Aucun produit disponible</h4>
                <p style="color:var(--text-muted);">Le gérant n'a pas encore ajouté de produits.</p>
            </div>
            @endforelse
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('shop') }}" class="btn" style="background:var(--primary);color:#fff;padding:14px 40px;border-radius:50px;font-weight:700;font-size:15px;text-decoration:none;">
                Voir tous les produits <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>

{{-- POURQUOI NOUS --}}
<section class="why-us">
    <div class="container">
        <div class="section-title">
            <span class="badge-label">Nos Engagements</span>
            <h2>Pourquoi nous choisir ?</h2>
            <p>Qualité, fraîcheur et service irréprochable à chaque commande</p>
            <div class="section-divider"></div>
        </div>
        <div class="row">
            <div class="col-md-3 col-sm-6"><div class="why-card"><div class="why-icon">🚚</div><h5>Livraison rapide</h5><p>Commandez avant 12h et recevez votre commande le jour même à Cotonou.</p></div></div>
            <div class="col-md-3 col-sm-6"><div class="why-card"><div class="why-icon">✅</div><h5>Fraîcheur garantie</h5><p>Tous nos produits sont sélectionnés chaque matin auprès de nos fournisseurs locaux.</p></div></div>
            <div class="col-md-3 col-sm-6"><div class="why-card"><div class="why-icon">💰</div><h5>Meilleurs prix</h5><p>Des prix compétitifs sans compromis sur la qualité. Promotions chaque semaine.</p></div></div>
            <div class="col-md-3 col-sm-6"><div class="why-card"><div class="why-icon">📞</div><h5>Support 7j/7</h5><p>Notre équipe est disponible tous les jours pour répondre à vos questions.</p></div></div>
        </div>
    </div>
</section>

{{-- TÉMOIGNAGES --}}
<section class="testimonials">
    <div class="container">
        <div class="section-title">
            <span class="badge-label" style="background:rgba(232,131,10,0.2);color:var(--secondary)">Avis Clients</span>
            <h2>Ce que disent nos clients</h2>
            <p>La satisfaction de nos clients est notre priorité</p>
            <div class="section-divider"></div>
        </div>
        <div class="row">
            <div class="col-md-4"><div class="testi-card"><div class="testi-stars">★★★★★</div><p class="testi-text">"Les poissons sont toujours très frais. La livraison est rapide et le service client très réactif. Je recommande vivement !"</p><div class="testi-author"><div class="testi-avatar">A</div><div><div class="testi-name">Adjoua Marie</div><div class="testi-role">Cliente régulière — Cotonou</div></div></div></div></div>
            <div class="col-md-4"><div class="testi-card"><div class="testi-stars">★★★★★</div><p class="testi-text">"Excellente qualité de viande. Les entrecôtes sont parfaites. Je commande chaque semaine depuis 3 mois sans jamais être déçu."</p><div class="testi-author"><div class="testi-avatar">K</div><div><div class="testi-name">Kofi Jean-Paul</div><div class="testi-role">Client fidèle — Porto-Novo</div></div></div></div></div>
            <div class="col-md-4"><div class="testi-card"><div class="testi-stars">★★★★☆</div><p class="testi-text">"Super marché en ligne ! Les escargots séchés sont de très bonne qualité. Commande facile et paiement mobile money très pratique."</p><div class="testi-author"><div class="testi-avatar">F</div><div><div class="testi-name">Fatou Diallo</div><div class="testi-role">Cliente — Abomey-Calavi</div></div></div></div></div>
        </div>
    </div>
</section>

{{-- NEWSLETTER --}}
<section class="newsletter-section">
    <div class="container text-center">
        <h2>🐟 Recevez nos offres du jour !</h2>
        <p class="mb-4">Inscrivez-vous et soyez les premiers informés des arrivages et promotions</p>
        <form action="{{ route('newsletter') }}" method="POST" class="newsletter-form">
            @csrf
            <input type="email" name="email" placeholder="Votre adresse email..." required>
            <button type="submit">S'abonner</button>
        </form>
    </div>
</section>

@endsection

@section('scripts')
<script>
// ===== FILTRE PRODUITS =====
document.querySelectorAll('.filter-btns button').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.filter-btns button').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        const filter = this.getAttribute('data-filter');
        document.querySelectorAll('.special-grid').forEach(item => {
            item.style.display = (filter === '*' || item.classList.contains(filter.replace('.', ''))) ? 'block' : 'none';
        });
    });
});

// ===== MODAL CALIBRE =====
function openModal(produitId, nom, prix, imgUrl) {
    document.getElementById('modalNom').textContent  = nom;
    document.getElementById('modalPrix').textContent = parseInt(prix.replace(/\s/g,'')).toLocaleString('fr-FR');
    document.getElementById('modalProduitId').value  = produitId;
    document.getElementById('modalQty').value        = 1;
    document.getElementById('modalQuantite').value   = 1;
    document.getElementById('modalImg').innerHTML    = `<img src="${imgUrl}" class="modal-produit-img" alt="${nom}" onerror="this.src='{{ asset('images/img-pro-01.jpg') }}'">`;

    // Reset calibre
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

document.getElementById('calibreModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});
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