@extends('layouts.app')

@section('title', 'Dashboard Gérant - FreshMarket')

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
    .dash-avatar { width:60px; height:60px; background:linear-gradient(135deg,#27ae60,#2ecc71); border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:24px; font-weight:800; color:#fff; border:3px solid rgba(255,255,255,.3); flex-shrink:0; }

    /* Sidebar */
    .dash-menu { background:#fff; border-radius:16px; box-shadow:0 2px 12px rgba(0,0,0,.06); overflow:hidden; margin-bottom:24px; }
    .dash-menu-title { padding:14px 20px; font-size:11px; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:1.2px; border-bottom:1px solid #f0f0f0; background:var(--light-bg); }
    .dash-menu-item { display:flex; align-items:center; gap:12px; padding:13px 20px; border-bottom:1px solid #f5f5f5; text-decoration:none; color:var(--dark); font-size:14px; font-weight:600; transition:all .2s; }
    .dash-menu-item:last-child { border-bottom:none; }
    .dash-menu-item:hover { background:var(--light-bg); color:var(--primary); padding-left:26px; }
    .dash-menu-item.active { background:rgba(10,79,110,.06); color:var(--primary); border-left:3px solid var(--primary); }
    .dash-menu-item i { width:18px; text-align:center; color:var(--primary); }

    /* Stats */
    .stat-card { background:#fff; border-radius:16px; padding:20px; box-shadow:0 2px 12px rgba(0,0,0,.06); display:flex; align-items:center; gap:14px; margin-bottom:20px; transition:all .3s; border-left:4px solid transparent; }
    .stat-card:hover { transform:translateY(-4px); box-shadow:0 8px 25px rgba(0,0,0,.1); }
    .stat-card.blue   { border-left-color:var(--primary); }
    .stat-card.orange { border-left-color:var(--secondary); }
    .stat-card.green  { border-left-color:var(--green); }
    .stat-card.red    { border-left-color:var(--accent); }
    .stat-icon { width:50px; height:50px; border-radius:14px; display:flex; align-items:center; justify-content:center; font-size:22px; flex-shrink:0; }
    .stat-icon.blue   { background:rgba(10,79,110,.1); }
    .stat-icon.orange { background:rgba(232,131,10,.1); }
    .stat-icon.green  { background:rgba(39,174,96,.1); }
    .stat-icon.red    { background:rgba(192,57,43,.1); }
    .stat-info h3 { font-size:26px; font-weight:800; color:var(--dark); margin:0; }
    .stat-info p  { font-size:13px; color:var(--muted); margin:0; }

    /* Cards */
    .section-card { background:#fff; border-radius:16px; box-shadow:0 2px 12px rgba(0,0,0,.06); margin-bottom:24px; overflow:hidden; }
    .section-card-header { padding:16px 22px; border-bottom:2px solid var(--light-bg); display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px; }
    .section-card-header h5 { font-size:15px; font-weight:800; color:var(--dark); margin:0; }

    /* Table */
    .admin-table { width:100%; border-collapse:collapse; }
    .admin-table th { background:var(--light-bg); padding:11px 16px; font-size:11px; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:.8px; text-align:left; }
    .admin-table td { padding:13px 16px; border-bottom:1px solid #f5f5f5; font-size:14px; color:var(--dark); vertical-align:middle; }
    .admin-table tr:last-child td { border-bottom:none; }
    .admin-table tr:hover td { background:#fafafa; }

    /* Badges */
    .status-badge { display:inline-flex; align-items:center; gap:5px; padding:4px 10px; border-radius:20px; font-size:11px; font-weight:700; }
    .status-en_attente    { background:rgba(232,131,10,.12); color:#a05c00; }
    .status-confirmee     { background:rgba(10,79,110,.1);   color:var(--primary); }
    .status-en_preparation{ background:rgba(155,89,182,.1);  color:#6c3483; }
    .status-en_livraison  { background:rgba(39,174,96,.1);   color:var(--green); }
    .status-livree        { background:rgba(39,174,96,.15);  color:#1a7a3a; }
    .status-annulee       { background:rgba(192,57,43,.1);   color:var(--accent); }

    .stock-ok      { background:rgba(39,174,96,.1);   color:var(--green);   padding:3px 10px; border-radius:20px; font-size:11px; font-weight:700; }
    .stock-faible  { background:rgba(232,131,10,.12); color:#a05c00;        padding:3px 10px; border-radius:20px; font-size:11px; font-weight:700; }
    .stock-critique{ background:rgba(192,57,43,.1);   color:var(--accent);  padding:3px 10px; border-radius:20px; font-size:11px; font-weight:700; }

    /* Boutons */
    .btn-sm-fm { padding:6px 14px; border-radius:8px; font-size:12px; font-weight:700; border:none; cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; gap:5px; transition:all .2s; }
    .btn-primary-fm { background:var(--primary); color:#fff; }
    .btn-primary-fm:hover { background:#083d56; color:#fff; }
    .btn-success-fm { background:var(--green); color:#fff; }
    .btn-warning-fm { background:var(--secondary); color:#fff; }
    .btn-danger-fm  { background:var(--accent); color:#fff; }

    /* Alert */
    .alert-fm { border-radius:10px; padding:12px 16px; font-size:14px; font-weight:600; margin-bottom:20px; display:flex; align-items:center; gap:8px; }
    .alert-success-fm { background:rgba(39,174,96,.1);  border:1.5px solid rgba(39,174,96,.25);  color:#1e7e44; }
    .alert-error-fm   { background:rgba(192,57,43,.08); border:1.5px solid rgba(192,57,43,.2);   color:var(--accent); }
    .alert-warning-fm { background:rgba(232,131,10,.1); border:1.5px solid rgba(232,131,10,.25); color:#a05c00; }
</style>
@endsection

@section('content')

{{-- Header --}}
<div class="dash-header">
    <div class="container">
        <div class="d-flex align-items-center gap-3">
            <div class="dash-avatar">{{ strtoupper(substr(auth()->user()->prenom, 0, 1)) }}</div>
            <div>
                <h1>Dashboard Gérant 📊</h1>
                <p>{{ auth()->user()->prenom }} {{ auth()->user()->nom }} — {{ now()->format('d/m/Y') }}</p>
            </div>
        </div>
    </div>
</div>

<div class="dashboard-wrapper">
    <div class="container">

        @if(session('success'))
        <div class="alert-fm alert-success-fm"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
        @endif
        @if(session('error'))
        <div class="alert-fm alert-error-fm"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
        @endif

        {{-- Alerte stock faible --}}
        @if($stats['stock_faible'] > 0)
        <div class="alert-fm alert-warning-fm">
            <i class="fas fa-exclamation-triangle"></i>
            <strong>{{ $stats['stock_faible'] }} produit(s)</strong> ont un stock faible ou critique !
            <a href="{{ route('gerant.stocks') }}" style="color:inherit;font-weight:800;margin-left:8px;">Voir les stocks →</a>
        </div>
        @endif

        <div class="row">

            {{-- Sidebar --}}
            <div class="col-lg-3 mb-4">
                <div class="dash-menu">
                    <div class="dash-menu-title">Gestion</div>
                    <a href="{{ route('gerant.dashboard') }}" class="dash-menu-item active">
                        <i class="fas fa-tachometer-alt"></i> Tableau de bord
                    </a>
                    <a href="{{ route('gerant.produits') }}" class="dash-menu-item">
                        <i class="fas fa-fish"></i> Produits
                        <span class="badge bg-primary ms-auto">{{ $stats['total_produits'] }}</span>
                    </a>
                    <a href="{{ route('gerant.stocks') }}" class="dash-menu-item">
                        <i class="fas fa-boxes"></i> Stocks
                        @if($stats['stock_faible'] > 0)
                        <span class="badge bg-danger ms-auto">{{ $stats['stock_faible'] }}</span>
                        @endif
                    </a>
                    <a href="{{ route('gerant.commandes') }}" class="dash-menu-item">
                        <i class="fas fa-shopping-bag"></i> Commandes
                        @if($stats['commandes_attente'] > 0)
                        <span class="badge bg-warning ms-auto">{{ $stats['commandes_attente'] }}</span>
                        @endif
                    </a>
                    <a href="{{ route('gerant.approvisionnement') }}" class="dash-menu-item">
                        <i class="fas fa-truck-loading"></i> Approvisionnement
                    </a>
                    <div class="dash-menu-title" style="margin-top:4px;">Rapports</div>
                    <a href="{{ route('gerant.etat-stock') }}" class="dash-menu-item">
                        <i class="fas fa-chart-bar"></i> État des stocks
                    </a>
                    <a href="{{ route('gerant.etat-ventes') }}" class="dash-menu-item">
                        <i class="fas fa-chart-line"></i> État des ventes
                    </a>
                    <div class="dash-menu-title" style="margin-top:4px;">Système</div>
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

                {{-- Stats ligne 1 --}}
                <div class="row mb-2">
                    <div class="col-6 col-md-3">
                        <div class="stat-card blue">
                            <div class="stat-icon blue">🐟</div>
                            <div class="stat-info">
                                <h3>{{ $stats['total_produits'] }}</h3>
                                <p>Produits</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="stat-card red">
                            <div class="stat-icon red">⚠️</div>
                            <div class="stat-info">
                                <h3>{{ $stats['stock_faible'] }}</h3>
                                <p>Stock faible</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="stat-card orange">
                            <div class="stat-icon orange">📦</div>
                            <div class="stat-info">
                                <h3>{{ $stats['total_commandes'] }}</h3>
                                <p>Commandes</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="stat-card green">
                            <div class="stat-icon green">💰</div>
                            <div class="stat-info">
                                <h3>{{ number_format($stats['revenus_total'], 0, ',', ' ') }}</h3>
                                <p>FCFA revenus</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Stats ligne 2 --}}
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="stat-card orange">
                            <div class="stat-icon orange">⏳</div>
                            <div class="stat-info">
                                <h3>{{ $stats['commandes_attente'] }}</h3>
                                <p>En attente</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card green">
                            <div class="stat-icon green">🚚</div>
                            <div class="stat-info">
                                <h3>{{ $stats['commandes_livraison'] }}</h3>
                                <p>En livraison</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card blue">
                            <div class="stat-icon blue">🏭</div>
                            <div class="stat-info">
                                <h3>{{ $stats['total_fournisseurs'] }}</h3>
                                <p>Fournisseurs</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ===== GRAPHIQUE VENTES PAR CATÉGORIE ===== --}}
<div class="section-card">
    <div class="section-card-header">
        <h5>📊 Ventes par catégorie de produit</h5>
        <span style="font-size:13px;color:var(--muted);">{{ now()->format('d/m/Y') }}</span>
    </div>
    <div style="padding:24px;">

        @php
            // Récupérer les ventes par catégorie
            $ventesParCategorie = \App\Models\Commander::with('produit.categorie')
                ->get()
                ->groupBy(fn($c) => $c->produit->categorie->libelle ?? 'Autre')
                ->map(fn($g) => [
                    'total'    => $g->sum(fn($c) => $c->quantite_cmder * $c->prix_comd),
                    'quantite' => $g->sum('quantite_cmder'),
                    'nb'       => $g->count(),
                ]);

            $maxVente = $ventesParCategorie->max('total') ?: 1;

            $couleurs = [
                'Poissons frais'  => ['bg' => '#0a4f6e', 'light' => 'rgba(10,79,110,.1)',   'icon' => '🐟'],
                'Poissons fumés'  => ['bg' => '#5d4037', 'light' => 'rgba(93,64,55,.1)',    'icon' => '🔥'],
                'Viandes de bœuf' => ['bg' => '#c0392b', 'light' => 'rgba(192,57,43,.1)',   'icon' => '🥩'],
                'Volailles'       => ['bg' => '#e8830a', 'light' => 'rgba(232,131,10,.1)',  'icon' => '🍗'],
                'Escargots'       => ['bg' => '#27ae60', 'light' => 'rgba(39,174,96,.1)',   'icon' => '🐌'],
                'Abats'           => ['bg' => '#8e44ad', 'light' => 'rgba(142,68,173,.1)',  'icon' => '🫀'],
            ];
        @endphp

        @if(false)
            <div style="text-align:center;padding:40px;color:var(--muted);">
                <span style="font-size:48px;display:block;margin-bottom:14px;">📊</span>
                <p>Aucune vente enregistrée pour le moment</p>
            </div>
        @else

            {{-- Graphique barres horizontales --}}
            <div style="margin-bottom:30px;">
                @foreach($ventesParCategorie as $cat => $data)
                @php
                    $pct    = ($data['total'] / $maxVente) * 100;
                    $color  = $couleurs[$cat]['bg']    ?? '#0a4f6e';
                    $light  = $couleurs[$cat]['light'] ?? 'rgba(10,79,110,.1)';
                    $icon   = $couleurs[$cat]['icon']  ?? '📦';
                @endphp
                <div style="margin-bottom:18px;">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px;">
                        <div style="display:flex;align-items:center;gap:8px;">
                            <span style="font-size:18px;">{{ $icon }}</span>
                            <span style="font-size:14px;font-weight:700;color:var(--dark);">{{ $cat }}</span>
                            <span style="background:{{ $light }};color:{{ $color }};padding:2px 8px;border-radius:20px;font-size:11px;font-weight:700;">
                                {{ $data['nb'] }} vente(s)
                            </span>
                        </div>
                        <div style="text-align:right;">
                            <span style="font-size:14px;font-weight:800;color:{{ $color }};">
                                {{ number_format($data['total'], 0, ',', ' ') }} FCFA
                            </span>
                            <span style="font-size:12px;color:var(--muted);margin-left:8px;">
                                {{ number_format($data['quantite'], 1) }} kg
                            </span>
                        </div>
                    </div>
                    <div style="height:12px;background:#f0f0f0;border-radius:6px;overflow:hidden;">
                        <div style="height:100%;width:{{ $pct }}%;background:{{ $color }};border-radius:6px;transition:width .5s ease;"></div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Graphique en colonnes (canvas) --}}
            <div style="border-top:2px solid var(--light-bg);padding-top:24px;">
                <p style="font-size:13px;font-weight:700;color:var(--muted);margin-bottom:16px;text-transform:uppercase;letter-spacing:1px;">
                    Répartition en pourcentage
                </p>
                <div style="position:relative;width:100%;height:320px;">
                    <canvas id="chartCategorie"
                            role="img"
                            aria-label="Ventes par catégorie FreshMarket sur 6 mois">
                    </canvas>
                </div>
            </div>

            {{-- Tableau récap --}}
            <div style="border-top:2px solid var(--light-bg);padding-top:20px;margin-top:20px;">
                <table style="width:100%;border-collapse:collapse;">
                    <thead>
                        <tr style="background:var(--light-bg);">
                            <th style="padding:10px 14px;font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;text-align:left;">Catégorie</th>
                            <th style="padding:10px 14px;font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;text-align:right;">Quantité vendue</th>
                            <th style="padding:10px 14px;font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;text-align:right;">Nb ventes</th>
                            <th style="padding:10px 14px;font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;text-align:right;">Montant total</th>
                            <th style="padding:10px 14px;font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;text-align:right;">% du total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $totalGlobal = $ventesParCategorie->sum('total'); @endphp
                        @foreach($ventesParCategorie as $cat => $data)
                        @php
                            $pctTotal = $totalGlobal > 0 ? ($data['total'] / $totalGlobal) * 100 : 0;
                            $color    = $couleurs[$cat]['bg'] ?? '#0a4f6e';
                            $icon     = $couleurs[$cat]['icon'] ?? '📦';
                        @endphp
                        <tr style="border-bottom:1px solid #f5f5f5;">
                            <td style="padding:12px 14px;">
                                <div style="display:flex;align-items:center;gap:8px;">
                                    <div style="width:10px;height:10px;border-radius:50%;background:{{ $color }};flex-shrink:0;"></div>
                                    {{ $icon }} {{ $cat }}
                                </div>
                            </td>
                            <td style="padding:12px 14px;text-align:right;font-weight:700;">{{ number_format($data['quantite'], 1) }} kg</td>
                            <td style="padding:12px 14px;text-align:right;">{{ $data['nb'] }}</td>
                            <td style="padding:12px 14px;text-align:right;font-weight:800;color:{{ $color }};">
                                {{ number_format($data['total'], 0, ',', ' ') }} FCFA
                            </td>
                            <td style="padding:12px 14px;text-align:right;">
                                <span style="background:var(--light-bg);padding:3px 10px;border-radius:20px;font-size:12px;font-weight:700;">
                                    {{ number_format($pctTotal, 1) }}%
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr style="background:var(--light-bg);">
                            <td style="padding:12px 14px;font-weight:800;">Total</td>
                            <td style="padding:12px 14px;text-align:right;font-weight:800;">
                                {{ number_format($ventesParCategorie->sum('quantite'), 1) }} kg
                            </td>
                            <td style="padding:12px 14px;text-align:right;font-weight:800;">
                                {{ $ventesParCategorie->sum('nb') }}
                            </td>
                            <td style="padding:12px 14px;text-align:right;font-weight:800;color:var(--primary);">
                                {{ number_format($totalGlobal, 0, ',', ' ') }} FCFA
                            </td>
                            <td style="padding:12px 14px;text-align:right;font-weight:800;">100%</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

        @endif
    </div>
</div>

                {{-- Produits stock faible --}}
                @if($produits_stock_faible->count() > 0)
                <div class="section-card">
                    <div class="section-card-header">
                        <h5>⚠️ Produits stock faible</h5>
                        <a href="{{ route('gerant.stocks') }}" class="btn-sm-fm btn-warning-fm">
                            Gérer les stocks <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Produit</th>
                                <th>Stock actuel</th>
                                <th>Seuil alerte</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($produits_stock_faible as $stock)
                            <tr>
                                <td><strong>{{ $stock->produit->libelle_prod ?? '—' }}</strong></td>
                                <td>{{ $stock->quantite_stock }} {{ $stock->produit->calibre->unite_vente ?? 'kg' }}</td>
                                <td>{{ $stock->seuil_alerte }}</td>
                                <td>
                                    @if($stock->quantite_stock == 0)
                                        <span class="stock-critique">🔴 Épuisé</span>
                                    @else
                                        <span class="stock-faible">🟡 Faible</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif

                {{-- Dernières commandes --}}
                <div class="section-card">
                    <div class="section-card-header">
                        <h5>📦 Dernières commandes</h5>
                        <a href="{{ route('gerant.commandes') }}" class="btn-sm-fm btn-primary-fm">
                            Voir toutes <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Référence</th>
                                <th>Client</th>
                                <th>Montant</th>
                                <th>Statut</th>
                                <th>Paiement</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dernieres_commandes as $commande)
                            <tr>
                                <td><strong>{{ $commande->reference }}</strong></td>
                                <td>
                                    {{ $commande->client->prenom_client ?? '—' }}
                                    {{ $commande->client->nom_client ?? '' }}
                                </td>
                                <td><strong>{{ number_format($commande->montant_total, 0, ',', ' ') }} FCFA</strong></td>
                                <td>
                                    <span class="status-badge status-{{ $commande->statut_cmd }}">
                                        {{ ucfirst(str_replace('_', ' ', $commande->statut_cmd)) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="status-badge {{ $commande->statut_paiement === 'paye' ? 'status-livree' : 'status-en_attente' }}">
                                        {{ $commande->statut_paiement === 'paye' ? '✅ Payé' : '⏳ Attente' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('gerant.commandes') }}" class="btn-sm-fm btn-primary-fm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" style="text-align:center;color:var(--muted);padding:30px;">
                                    Aucune commande pour le moment
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>

@php
    use App\Models\Commander;
    use Carbon\Carbon;

    $mois = collect(range(5, 0))->map(fn($i) => now()->subMonths($i));
    $categories = \App\Models\Categorie::all();

    $datasetsData = [];
    $labels = [];

    foreach ($mois as $m) {
        $labels[] = $m->locale('fr')->isoFormat('MMM');
    }

    foreach ($categories as $cat) {
        $valeurs = [];
        foreach ($mois as $m) {
            $total = Commander::whereHas('produit', fn($q) => $q->where('id_categorie', $cat->id_categorie))
                ->whereMonth('created_at', $m->month)
                ->whereYear('created_at', $m->year)
                ->sum(\DB::raw('quantite_cmder * prix_comd'));
            $valeurs[] = (int) $total;
        }
        $datasetsData[] = [
            'label' => $cat->libelle,
            'data'  => $valeurs,
        ];
    }

    $couleurs = ['#185FA5','#A32D2D','#854F0B','#3B6D11','#3C3489','#72243E'];
@endphp

const labels   = {!! json_encode($labels) !!};
const datasets = {!! json_encode($datasetsData) !!};
const couleurs = {!! json_encode($couleurs) !!};

const ctx = document.getElementById('chartCategorie');
if (ctx) {
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: datasets.map((d, i) => ({
                label:           d.label,
                data:            d.data,
                backgroundColor: couleurs[i % couleurs.length],
                borderRadius:    3,
                borderSkipped:   false,
            }))
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => ctx.dataset.label + ' : ' + ctx.parsed.y.toLocaleString('fr-FR') + ' FCFA'
                    }
                }
            },
            scales: {
                x: { grid: { display: false }, ticks: { font: { size: 12 }, autoSkip: false } },
                y: {
                    grid: { color: 'rgba(0,0,0,0.05)' },
                    ticks: { callback: v => (v/1000) + 'k FCFA' }
                }
            },
            interaction: { mode: 'index', intersect: false },
        }
    });
}
</script>
@endsection