@extends('layouts.app')

@section('title', 'Recherche Produits - FreshMarket')

@section('styles')
<style>
    :root { --primary:#0a4f6e; --secondary:#e8830a; --green:#27ae60; --accent:#c0392b; --light-bg:#f4f9fc; --dark:#1a1a2e; --muted:#6c757d; }
    .dashboard-wrapper { background:var(--light-bg); min-height:100vh; padding:30px 0 60px; }
    .dash-header { background:linear-gradient(135deg,#073a52,#0a4f6e); padding:30px 0; margin-bottom:30px; }
    .dash-header h1 { font-size:24px; color:#fff; font-weight:800; margin-bottom:4px; }
    .dash-header p { color:rgba(255,255,255,.7); font-size:14px; margin:0; }
    .dash-avatar { width:60px; height:60px; background:linear-gradient(135deg,#e8830a,#f0a83a); border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:24px; font-weight:800; color:#fff; border:3px solid rgba(255,255,255,.3); flex-shrink:0; }
    .dash-menu { background:#fff; border-radius:16px; box-shadow:0 2px 12px rgba(0,0,0,.06); overflow:hidden; margin-bottom:24px; }
    .dash-menu-title { padding:14px 20px; font-size:11px; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:1.2px; border-bottom:1px solid #f0f0f0; background:var(--light-bg); }
    .dash-menu-item { display:flex; align-items:center; gap:12px; padding:13px 20px; border-bottom:1px solid #f5f5f5; text-decoration:none; color:var(--dark); font-size:14px; font-weight:600; transition:all .2s; }
    .dash-menu-item:last-child { border-bottom:none; }
    .dash-menu-item:hover { background:var(--light-bg); color:var(--primary); padding-left:26px; }
    .dash-menu-item.active { background:rgba(10,79,110,.06); color:var(--primary); border-left:3px solid var(--primary); }
    .dash-menu-item i { width:18px; text-align:center; color:var(--primary); }
    .section-card { background:#fff; border-radius:16px; box-shadow:0 2px 12px rgba(0,0,0,.06); margin-bottom:24px; overflow:hidden; }
    .section-card-header { padding:16px 22px; border-bottom:2px solid var(--light-bg); display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px; }
    .section-card-header h5 { font-size:15px; font-weight:800; color:var(--dark); margin:0; }
    .search-bar { padding:20px 22px; border-bottom:1px solid var(--light-bg); display:flex; gap:10px; flex-wrap:wrap; }
    .search-bar input, .search-bar select { padding:10px 14px; border:2px solid #e8f0f5; border-radius:10px; font-size:14px; color:var(--dark); outline:none; }
    .search-bar input { flex:1; min-width:200px; }
    .search-bar input:focus, .search-bar select:focus { border-color:var(--primary); }
    .search-bar button { padding:10px 20px; background:var(--primary); color:#fff; border:none; border-radius:10px; font-size:14px; font-weight:700; cursor:pointer; }
    .produits-grid { display:grid; grid-template-columns:repeat(auto-fill, minmax(200px,1fr)); gap:16px; padding:20px 22px; }
    .produit-card { background:var(--light-bg); border-radius:12px; padding:16px; border:2px solid transparent; transition:all .2s; }
    .produit-card:hover { border-color:var(--primary); background:#fff; transform:translateY(-2px); }
    .produit-img { width:100%; height:120px; object-fit:cover; border-radius:8px; margin-bottom:10px; }
    .produit-img-placeholder { width:100%; height:120px; background:#e0eaf0; border-radius:8px; margin-bottom:10px; display:flex; align-items:center; justify-content:center; font-size:36px; }
    .produit-name { font-size:14px; font-weight:700; color:var(--dark); margin-bottom:4px; }
    .produit-price { font-size:16px; font-weight:800; color:var(--primary); }
    .produit-stock { font-size:12px; color:var(--muted); margin-top:4px; }
    .produit-cat { font-size:11px; color:var(--secondary); font-weight:700; text-transform:uppercase; margin-bottom:4px; }
    .pagination-wrap { padding:16px 22px; border-top:1px solid var(--light-bg); }
</style>
@endsection

@section('content')

<div class="dash-header">
    <div class="container">
        <div class="d-flex align-items-center gap-3">
            <div class="dash-avatar">{{ strtoupper(substr(auth()->user()->prenom, 0, 1)) }}</div>
            <div>
                <h1>Recherche de produits 🔍</h1>
                <p>{{ auth()->user()->prenom }} {{ auth()->user()->nom }} — {{ now()->format('d/m/Y') }}</p>
            </div>
        </div>
    </div>
</div>

<div class="dashboard-wrapper">
    <div class="container">
        <div class="row">

            {{-- Sidebar --}}
            <div class="col-lg-3 mb-4">
                <div class="dash-menu">
                    <div class="dash-menu-title">Ventes</div>
                    <a href="{{ route('vendeur.dashboard') }}" class="dash-menu-item"><i class="fas fa-tachometer-alt"></i> Tableau de bord</a>
                    <a href="{{ route('vendeur.produits') }}" class="dash-menu-item active"><i class="fas fa-search"></i> Recherche produits</a>
                    <a href="{{ route('vendeur.commandes') }}" class="dash-menu-item"><i class="fas fa-shopping-bag"></i> Commandes</a>
                    <a href="{{ route('vendeur.livraisons') }}" class="dash-menu-item"><i class="fas fa-truck"></i> Livraisons</a>
                    <div class="dash-menu-title" style="margin-top:4px;">Gestion</div>
                    <a href="{{ route('vendeur.clients') }}" class="dash-menu-item"><i class="fas fa-users"></i> Clients</a>
                    <a href="{{ route('vendeur.factures') }}" class="dash-menu-item"><i class="fas fa-file-invoice"></i> Factures</a>
                    <div class="dash-menu-title" style="margin-top:4px;">Système</div>
                    <a href="{{ route('home') }}" class="dash-menu-item"><i class="fas fa-home"></i> Voir le site</a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dash-menu-item w-100 text-danger" style="background:none;border:none;text-align:left;">
                            <i class="fas fa-sign-out-alt"></i> Déconnexion
                        </button>
                    </form>
                </div>
            </div>

            {{-- Contenu --}}
            <div class="col-lg-9">
                <div class="section-card">
                    <div class="section-card-header">
                        <h5>🔍 Catalogue produits</h5>
                        <span style="font-size:13px;color:var(--muted);">{{ $produits->total() }} produit(s)</span>
                    </div>

                    {{-- Recherche --}}
                    <form action="{{ route('vendeur.produits') }}" method="GET">
                        <div class="search-bar">
                            <select name="categorie">
                                <option value="">Toutes catégories</option>
                                @foreach($categories as $cat)
                                <option value="{{ $cat->id_categorie }}" {{ request('categorie') == $cat->id_categorie ? 'selected' : '' }}>
                                    {{ $cat->libelle }}
                                </option>
                                @endforeach
                            </select>
                            <input type="text" name="q" value="{{ request('q') }}" placeholder="Nom du produit...">
                            <button type="submit"><i class="fas fa-search"></i> Rechercher</button>
                        </div>
                    </form>

                    {{-- Grille produits --}}
                    <div class="produits-grid">
                        @forelse($produits as $produit)
                        @php
                            $icons = ['Poisson'=>'🐟','Viande'=>'🥩','Volaille'=>'🍗','Escargot'=>'🐌','Abat'=>'🫀'];
                            $icon  = '📦';
                            foreach($icons as $key => $val) {
                                if(str_contains(strtolower($produit->categorie->libelle ?? ''), strtolower($key))) { $icon = $val; break; }
                            }
                        @endphp
                        <div class="produit-card">
                            @if($produit->image)
                                <img src="{{ asset('images/produits/'.$produit->image) }}" class="produit-img" alt="{{ $produit->libelle_prod }}">
                            @else
                                <div class="produit-img-placeholder">{{ $icon }}</div>
                            @endif
                            <div class="produit-cat">{{ $produit->categorie->libelle ?? '' }}</div>
                            <div class="produit-name">{{ $produit->libelle_prod }}</div>
                            <div class="produit-price">{{ number_format($produit->prix, 0, ',', ' ') }} FCFA</div>
                            <div class="produit-stock">
                                Stock : <strong>{{ $produit->stock->quantite_stock ?? 0 }} kg</strong>
                            </div>
                        </div>
                        @empty
                        <div style="grid-column:1/-1;text-align:center;padding:40px;color:var(--muted);">
                            <span style="font-size:48px;display:block;margin-bottom:14px;">📦</span>
                            <p>Aucun produit disponible</p>
                        </div>
                        @endforelse
                    </div>

                    {{-- Pagination --}}
                    @if($produits->hasPages())
                    <div class="pagination-wrap">
                        {{ $produits->withQueryString()->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection