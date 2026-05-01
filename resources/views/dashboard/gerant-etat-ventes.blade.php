@extends('layouts.app')

@section('title', 'État des Ventes - Gérant FreshMarket')

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

    .stat-card { background:#fff; border-radius:16px; padding:20px; box-shadow:0 2px 12px rgba(0,0,0,.06); display:flex; align-items:center; gap:14px; margin-bottom:20px; border-left:4px solid transparent; }
    .stat-card.blue   { border-left-color:var(--primary); }
    .stat-card.green  { border-left-color:var(--green); }
    .stat-card.orange { border-left-color:var(--secondary); }
    .stat-icon { width:48px; height:48px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:20px; flex-shrink:0; }
    .stat-icon.blue   { background:rgba(10,79,110,.1); }
    .stat-icon.green  { background:rgba(39,174,96,.1); }
    .stat-icon.orange { background:rgba(232,131,10,.1); }
    .stat-info h3 { font-size:22px; font-weight:800; color:var(--dark); margin:0; }
    .stat-info p  { font-size:13px; color:var(--muted); margin:0; }

    .section-card { background:#fff; border-radius:16px; box-shadow:0 2px 12px rgba(0,0,0,.06); margin-bottom:24px; overflow:hidden; }
    .section-card-header { padding:16px 22px; border-bottom:2px solid var(--light-bg); display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px; }
    .section-card-header h5 { font-size:15px; font-weight:800; color:var(--dark); margin:0; }

    .admin-table { width:100%; border-collapse:collapse; }
    .admin-table th { background:var(--light-bg); padding:11px 16px; font-size:11px; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:.8px; text-align:left; }
    .admin-table td { padding:12px 16px; border-bottom:1px solid #f5f5f5; font-size:14px; color:var(--dark); vertical-align:middle; }
    .admin-table tr:last-child td { border-bottom:none; }
    .admin-table tr:hover td { background:#fafafa; }
    .admin-table tfoot td { background:var(--light-bg); font-weight:800; padding:14px 16px; border-top:2px solid #e0e0e0; }

    .status-livree { background:rgba(39,174,96,.15); color:#1a7a3a; padding:4px 10px; border-radius:20px; font-size:11px; font-weight:700; }

    .btn-sm-fm { padding:6px 14px; border-radius:8px; font-size:12px; font-weight:700; border:none; cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; gap:5px; transition:all .2s; }
    .btn-primary-fm { background:var(--primary); color:#fff; }

    /* Graphique simple par mois */
    .chart-bar-wrap { display:flex; align-items:flex-end; gap:8px; height:120px; padding:10px 0; }
    .chart-bar-item { flex:1; display:flex; flex-direction:column; align-items:center; gap:4px; }
    .chart-bar { width:100%; border-radius:6px 6px 0 0; background:linear-gradient(to top, var(--primary), #1a7a9a); min-height:4px; transition:height .3s; }
    .chart-label { font-size:10px; color:var(--muted); font-weight:600; }
    .chart-val   { font-size:10px; color:var(--primary); font-weight:700; }

    @media print {
        .dash-menu, nav { display:none !important; }
        .col-lg-3 { display:none !important; }
        .col-lg-9 { width:100% !important; }
        .btn-sm-fm { display:none !important; }
    }
</style>
@endsection

@section('content')

<div class="dash-header">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <div style="width:50px;height:50px;background:linear-gradient(135deg,#27ae60,#2ecc71);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:20px;font-weight:800;color:#fff;">
                    {{ strtoupper(substr(auth()->user()->prenom, 0, 1)) }}
                </div>
                <div>
                    <h1>État des Ventes 📈</h1>
                    <p>Rapport au {{ now()->format('d/m/Y à H:i') }}</p>
                </div>
            </div>
            <button onclick="window.print()" class="btn-sm-fm" style="background:rgba(255,255,255,.2);color:#fff;padding:10px 18px;">
                <i class="fas fa-print"></i> Imprimer
            </button>
        </div>
    </div>
</div>

<div class="dashboard-wrapper">
    <div class="container">
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
                    <a href="{{ route('gerant.stocks') }}" class="dash-menu-item">
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
                    <a href="{{ route('gerant.etat-ventes') }}" class="dash-menu-item active">
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

                {{-- Stats résumé --}}
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="stat-card green">
                            <div class="stat-icon green">💰</div>
                            <div class="stat-info">
                                <h3>{{ number_format($total_ventes, 0, ',', ' ') }}</h3>
                                <p>FCFA total ventes</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card blue">
                            <div class="stat-icon blue">📦</div>
                            <div class="stat-info">
                                <h3>{{ $nb_commandes }}</h3>
                                <p>Commandes payées</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card orange">
                            <div class="stat-icon orange">📊</div>
                            <div class="stat-info">
                                <h3>{{ $nb_commandes > 0 ? number_format($total_ventes / $nb_commandes, 0, ',', ' ') : 0 }}</h3>
                                <p>FCFA panier moyen</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Revenus totaux --}}
                <div style="background:linear-gradient(135deg,var(--green),#2ecc71);border-radius:16px;padding:20px 24px;margin-bottom:24px;display:flex;align-items:center;justify-content:space-between;">
                    <div>
                        <p style="color:rgba(255,255,255,.8);font-size:13px;margin:0 0 4px;">Chiffre d'affaires total</p>
                        <h2 style="color:#fff;font-size:30px;font-weight:800;margin:0;">
                            {{ number_format($total_ventes, 0, ',', ' ') }} FCFA
                        </h2>
                        <p style="color:rgba(255,255,255,.7);font-size:13px;margin:4px 0 0;">
                            Sur {{ $nb_commandes }} commande(s) payée(s)
                        </p>
                    </div>
                    <div style="font-size:48px;">📈</div>
                </div>

                {{-- Graphique ventes par mois --}}
                @php
                    $ventesMois = $commandes->groupBy(fn($c) => $c->created_at->format('M Y'))
                                           ->map(fn($g) => $g->sum('montant_total'))
                                           ->take(6);
                    $maxVente = $ventesMois->max() ?: 1;
                @endphp

                @if($ventesMois->count() > 0)
                <div class="section-card">
                    <div class="section-card-header">
                        <h5>📊 Ventes par mois</h5>
                    </div>
                    <div style="padding:20px 22px;">
                        <div class="chart-bar-wrap">
                            @foreach($ventesMois as $mois => $montant)
                            <div class="chart-bar-item">
                                <span class="chart-val">{{ number_format($montant/1000, 0) }}k</span>
                                <div class="chart-bar" style="height:{{ max(4, ($montant / $maxVente) * 90) }}px;"></div>
                                <span class="chart-label">{{ $mois }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                {{-- Table ventes --}}
                <div class="section-card">
                    <div class="section-card-header">
                        <h5>📋 Détail des ventes</h5>
                        <span style="font-size:13px;color:var(--muted);">{{ now()->format('d/m/Y') }}</span>
                    </div>
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Référence</th>
                                <th>Client</th>
                                <th>Date</th>
                                <th>Opérateur</th>
                                <th>Montant</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($commandes as $i => $commande)
                            <tr>
                                <td style="color:var(--muted);">{{ $i + 1 }}</td>
                                <td><strong>{{ $commande->reference }}</strong></td>
                                <td>
                                    {{ $commande->client->prenom_client ?? '—' }}
                                    {{ $commande->client->nom_client ?? '' }}
                                </td>
                                <td>{{ $commande->created_at->format('d/m/Y') }}</td>
                                <td>
                                    @if($commande->momo_operateur === 'mtn')
                                        🟡 MTN MoMo
                                    @elseif($commande->momo_operateur === 'moov')
                                        🔵 Moov Money
                                    @else
                                        💵 Espèces
                                    @endif
                                </td>
                                <td><strong>{{ number_format($commande->montant_total, 0, ',', ' ') }} FCFA</strong></td>
                                <td><span class="status-livree">✅ Payé</span></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" style="text-align:center;color:var(--muted);padding:40px;">
                                    Aucune vente enregistrée pour le moment
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                        @if($commandes->count() > 0)
                        <tfoot>
                            <tr>
                                <td colspan="5" style="text-align:right;">Total :</td>
                                <td colspan="2"><strong>{{ number_format($total_ventes, 0, ',', ' ') }} FCFA</strong></td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection