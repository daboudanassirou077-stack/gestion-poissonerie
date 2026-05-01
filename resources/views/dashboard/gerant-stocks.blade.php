@extends('layouts.app')

@section('title', 'Gestion Stocks - Gérant FreshMarket')

@section('styles')
<style>
    :root {
        --primary:#0a4f6e; --secondary:#e8830a;
        --green:#27ae60; --accent:#c0392b;
        --light-bg:#f4f9fc; --dark:#1a1a2e; --muted:#6c757d;
    }
    .dashboard-wrapper { background:var(--light-bg); min-height:100vh; padding:30px 0 60px; }
    .dash-header { background:linear-gradient(135deg,#073a52,#0a4f6e); padding:30px 0; margin-bottom:30px; }
    .dash-header h1 { font-size:24px; color:#fff; font-weight:800; margin-bottom:4px; }
    .dash-header p  { color:rgba(255,255,255,.7); font-size:14px; margin:0; }

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

    .admin-table { width:100%; border-collapse:collapse; }
    .admin-table th { background:var(--light-bg); padding:11px 16px; font-size:11px; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:.8px; text-align:left; }
    .admin-table td { padding:12px 16px; border-bottom:1px solid #f5f5f5; font-size:14px; color:var(--dark); vertical-align:middle; }
    .admin-table tr:last-child td { border-bottom:none; }
    .admin-table tr:hover td { background:#fafafa; }

    .stock-ok      { background:rgba(39,174,96,.1);   color:var(--green);  padding:4px 12px; border-radius:20px; font-size:12px; font-weight:700; }
    .stock-faible  { background:rgba(232,131,10,.12); color:#a05c00;       padding:4px 12px; border-radius:20px; font-size:12px; font-weight:700; }
    .stock-epuise  { background:rgba(192,57,43,.1);   color:var(--accent); padding:4px 12px; border-radius:20px; font-size:12px; font-weight:700; }

    /* Barre de progression stock */
    .stock-bar { height:6px; border-radius:3px; background:#e0e0e0; margin-top:4px; overflow:hidden; width:100px; }
    .stock-bar-fill { height:100%; border-radius:3px; transition:width .3s; }

    /* Formulaire inline mise à jour stock */
    .update-form { display:flex; align-items:center; gap:8px; }
    .update-form input { width:80px; padding:6px 10px; border:1.5px solid #e0e0e0; border-radius:8px; font-size:13px; text-align:center; }
    .update-form input:focus { outline:none; border-color:var(--primary); }

    .btn-sm-fm { padding:5px 12px; border-radius:7px; font-size:12px; font-weight:700; border:none; cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; gap:4px; transition:all .2s; }
    .btn-primary-fm { background:var(--primary); color:#fff; }
    .btn-primary-fm:hover { background:#083d56; color:#fff; }
    .btn-success-fm { background:var(--green); color:#fff; }
    .btn-success-fm:hover { background:#219a52; color:#fff; }

    .alert-fm { border-radius:10px; padding:12px 16px; font-size:14px; font-weight:600; margin-bottom:20px; display:flex; align-items:center; gap:8px; }
    .alert-success-fm { background:rgba(39,174,96,.1);  border:1.5px solid rgba(39,174,96,.25);  color:#1e7e44; }
    .alert-warning-fm { background:rgba(232,131,10,.1); border:1.5px solid rgba(232,131,10,.25); color:#a05c00; }
</style>
@endsection

@section('content')

<div class="dash-header">
    <div class="container">
        <div class="d-flex align-items-center gap-3">
            <div style="width:50px;height:50px;background:linear-gradient(135deg,#27ae60,#2ecc71);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:20px;font-weight:800;color:#fff;">
                {{ strtoupper(substr(auth()->user()->prenom, 0, 1)) }}
            </div>
            <div>
                <h1>Gestion des Stocks 📦</h1>
                <p>{{ $stocks->total() }} produit(s) en stock</p>
            </div>
        </div>
    </div>
</div>

<div class="dashboard-wrapper">
    <div class="container">

        @if(session('success'))
        <div class="alert-fm alert-success-fm"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
        @endif

        <div class="row">

            {{-- Sidebar --}}
            <div class="col-lg-3 mb-4">
                <div class="dash-menu">
                    <div class="dash-menu-title">Gestion</div>
                    <a href="{{ route('gerant.dashboard') }}" class="dash-menu-item">
                        <i class="fas fa-tachometer-alt"></i> Tableau de bord
                    </a>
                    <a href="{{ route('gerant.produits') }}" class="dash-menu-item">
                        <i class="fas fa-fish"></i> Produits
                    </a>
                    <a href="{{ route('gerant.stocks') }}" class="dash-menu-item active">
                        <i class="fas fa-boxes"></i> Stocks
                    </a>
                    <a href="{{ route('gerant.commandes') }}" class="dash-menu-item">
                        <i class="fas fa-shopping-bag"></i> Commandes
                    </a>
                    <a href="{{ route('gerant.approvisionnement') }}" class="dash-menu-item">
                        <i class="fas fa-truck-loading"></i> Approvisionnement
                    </a>
                    <div class="dash-menu-title">Rapports</div>
                    <a href="{{ route('gerant.etat-stock') }}" class="dash-menu-item">
                        <i class="fas fa-chart-bar"></i> État des stocks
                    </a>
                    <a href="{{ route('gerant.etat-ventes') }}" class="dash-menu-item">
                        <i class="fas fa-chart-line"></i> État des ventes
                    </a>
                    <div class="dash-menu-title">Système</div>
                    <a href="{{ route('home') }}" class="dash-menu-item">
                        <i class="fas fa-home"></i> Voir le site
                    </a>
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
                        <h5>📦 État des stocks en temps réel</h5>
                        <a href="{{ route('gerant.approvisionnement') }}" class="btn-sm-fm btn-primary-fm">
                            <i class="fas fa-truck-loading"></i> Approvisionner
                        </a>
                    </div>
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Produit</th>
                                <th>Catégorie</th>
                                <th>Stock actuel</th>
                                <th>Seuil alerte</th>
                                <th>Statut</th>
                                <th>Mise à jour</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($stocks as $stock)
                            <tr>
                                <td>
                                    <strong>{{ $stock->produit->libelle_prod ?? '—' }}</strong>
                                    <div style="font-size:12px;color:var(--muted);">
                                        {{ number_format($stock->produit->prix ?? 0, 0, ',', ' ') }} FCFA / {{ $stock->produit->calibre->unite_vente ?? 'kg' }}
                                    </div>
                                </td>
                                <td>{{ $stock->produit->categorie->libelle ?? '—' }}</td>
                                <td>
                                    <strong>{{ $stock->quantite_stock }}</strong>
                                    @php
                                        $pct = $stock->seuil_alerte > 0
                                            ? min(100, ($stock->quantite_stock / ($stock->seuil_alerte * 3)) * 100)
                                            : 100;
                                        $color = $stock->quantite_stock == 0 ? '#c0392b'
                                            : ($stock->quantite_stock <= $stock->seuil_alerte ? '#e8830a' : '#27ae60');
                                    @endphp
                                    <div class="stock-bar">
                                        <div class="stock-bar-fill" style="width:{{ $pct }}%;background:{{ $color }};"></div>
                                    </div>
                                </td>
                                <td>{{ $stock->seuil_alerte }}</td>
                                <td>
                                    @if($stock->quantite_stock == 0)
                                        <span class="stock-epuise">🔴 Épuisé</span>
                                    @elseif($stock->quantite_stock <= $stock->seuil_alerte)
                                        <span class="stock-faible">🟡 Faible</span>
                                    @else
                                        <span class="stock-ok">🟢 Normal</span>
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('gerant.update-stock', $stock->id_stock) }}"
                                          method="POST" class="update-form">
                                        @csrf
                                        @method('PUT')
                                        <input type="number" name="quantite_stock"
                                               value="{{ $stock->quantite_stock }}"
                                               min="0" step="0.01" title="Nouvelle quantité">
                                        <input type="hidden" name="seuil_alerte" value="{{ $stock->seuil_alerte }}">
                                        <button type="submit" class="btn-sm-fm btn-success-fm" title="Mettre à jour">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" style="text-align:center;color:var(--muted);padding:40px;">
                                    Aucun stock enregistré
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                    @if($stocks->hasPages())
                    <div style="padding:16px 18px;border-top:1px solid #f0f0f0;">
                        {{ $stocks->links() }}
                    </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>

@endsection