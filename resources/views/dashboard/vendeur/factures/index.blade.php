@extends('layouts.dashboard')

@section('title', 'Factures - FreshMarket')

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

    .filter-bar { padding:16px 22px; background:var(--light-bg); border-bottom:1px solid #e8f0f5; display:flex; gap:10px; flex-wrap:wrap; align-items:center; }
    .filter-bar input, .filter-bar select { padding:9px 14px; border:2px solid #e0eaf0; border-radius:10px; font-size:13px; color:var(--dark); outline:none; background:#fff; transition:border .2s; }
    .filter-bar input:focus, .filter-bar select:focus { border-color:var(--primary); }
    .filter-bar button { padding:9px 18px; background:var(--primary); color:#fff; border:none; border-radius:10px; font-size:13px; font-weight:700; cursor:pointer; }
    .filter-bar a.reset { padding:9px 14px; background:#fff; color:var(--muted); border:2px solid #e0eaf0; border-radius:10px; font-size:13px; font-weight:600; text-decoration:none; }

    .admin-table { width:100%; border-collapse:collapse; }
    .admin-table th { background:var(--light-bg); padding:11px 16px; font-size:11px; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:.8px; text-align:left; }
    .admin-table td { padding:13px 16px; border-bottom:1px solid #f5f5f5; font-size:14px; color:var(--dark); vertical-align:middle; }
    .admin-table tr:last-child td { border-bottom:none; }
    .admin-table tr:hover td { background:#fafafa; }

    .status-badge { display:inline-flex; align-items:center; gap:5px; padding:4px 10px; border-radius:20px; font-size:11px; font-weight:700; }
    .status-paye     { background:rgba(39,174,96,.15);  color:#1a7a3a; }
    .status-impayee  { background:rgba(232,131,10,.12); color:#a05c00; }

    .btn-sm-fm { padding:6px 14px; border-radius:8px; font-size:12px; font-weight:700; border:none; cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; gap:5px; transition:all .2s; }
    .btn-primary-fm { background:var(--primary); color:#fff; }
    .btn-primary-fm:hover { background:#083d56; color:#fff; }
    .btn-success-fm { background:var(--green); color:#fff; }
    .btn-success-fm:hover { background:#1e8449; color:#fff; }
    .btn-purple-fm  { background:#8e44ad; color:#fff; }
    .btn-purple-fm:hover { background:#7d3c98; color:#fff; }

    .alert-fm { border-radius:10px; padding:12px 16px; font-size:14px; font-weight:600; margin-bottom:20px; display:flex; align-items:center; gap:8px; }
    .alert-success-fm { background:rgba(39,174,96,.1); border:1.5px solid rgba(39,174,96,.25); color:#1e7e44; }
    .alert-error-fm   { background:rgba(192,57,43,.08); border:1.5px solid rgba(192,57,43,.2); color:var(--accent); }

    .facture-tag { display:inline-flex; align-items:center; gap:5px; padding:4px 10px; border-radius:8px; font-size:12px; font-weight:700; background:rgba(142,68,173,.1); color:#8e44ad; }

    .stat-mini { background:#fff; border-radius:12px; padding:16px; box-shadow:0 2px 8px rgba(0,0,0,.05); display:flex; align-items:center; gap:12px; border-left:4px solid transparent; margin-bottom:12px; }
    .stat-mini.red    { border-left-color:var(--accent); }
    .stat-mini.green  { border-left-color:var(--green); }
    .stat-mini.purple { border-left-color:#8e44ad; }
    .stat-mini-icon { width:40px; height:40px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:18px; }
    .stat-mini-icon.red    { background:rgba(192,57,43,.1); }
    .stat-mini-icon.green  { background:rgba(39,174,96,.1); }
    .stat-mini-icon.purple { background:rgba(142,68,173,.1); }
    .stat-mini h4 { font-size:18px; font-weight:800; color:var(--dark); margin:0; }
    .stat-mini p  { font-size:12px; color:var(--muted); margin:0; }

    .mode-badge { display:inline-flex; align-items:center; gap:4px; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:700; background:var(--light-bg); color:var(--dark); }
</style>
@endsection

@section('content')

<div class="dash-header">
    <div class="container">
        <div class="d-flex align-items-center gap-3">
            <div class="dash-avatar">{{ strtoupper(substr(auth()->user()->prenom, 0, 1)) }}</div>
            <div>
                <h1>Gestion des factures 🧾</h1>
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

        <div class="row">

            {{-- Sidebar --}}
            <div class="col-lg-3 mb-4">
                <div class="dash-menu">
                    <div class="dash-menu-title">Ventes</div>
                    <a href="{{ route('vendeur.dashboard') }}" class="dash-menu-item">
                        <i class="fas fa-tachometer-alt"></i> Tableau de bord
                    </a>
                    <a href="{{ route('vendeur.produits') }}" class="dash-menu-item">
                        <i class="fas fa-search"></i> Recherche produits
                    </a>
                    <a href="{{ route('vendeur.commandes') }}" class="dash-menu-item">
                        <i class="fas fa-shopping-bag"></i> Commandes
                    </a>
                    <a href="{{ route('vendeur.livraisons') }}" class="dash-menu-item">
                        <i class="fas fa-truck"></i> Livraisons
                    </a>
                    <div class="dash-menu-title" style="margin-top:4px;">Gestion</div>
                    <a href="{{ route('vendeur.clients') }}" class="dash-menu-item">
                        <i class="fas fa-users"></i> Clients
                    </a>
                    <a href="{{ route('vendeur.factures') }}" class="dash-menu-item active">
                        <i class="fas fa-file-invoice"></i> Factures
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

                {{-- Stats sidebar --}}
                @php
                    $nbImpayees = $factures->filter(fn($f) => $f->commande->statut_paiement !== 'paye')->count();
                    $nbPayees   = $factures->filter(fn($f) => $f->commande->statut_paiement === 'paye')->count();
                    $montantTotal = $factures->sum('montant_fact');
                @endphp
                <div class="stat-mini red">
                    <div class="stat-mini-icon red">⏳</div>
                    <div><h4>{{ $nbImpayees }}</h4><p>Impayées</p></div>
                </div>
                <div class="stat-mini green">
                    <div class="stat-mini-icon green">✅</div>
                    <div><h4>{{ $nbPayees }}</h4><p>Payées</p></div>
                </div>
                <div class="stat-mini purple">
                    <div class="stat-mini-icon purple">💰</div>
                    <div>
                        <h4 style="font-size:14px;">{{ number_format($montantTotal, 0, ',', ' ') }}</h4>
                        <p>FCFA total</p>
                    </div>
                </div>
            </div>

            {{-- Contenu --}}
            <div class="col-lg-9">
                <div class="section-card">
                    <div class="section-card-header">
                        <h5>🧾 Toutes les factures</h5>
                        <span style="font-size:13px;color:var(--muted);">{{ $factures->total() }} facture(s)</span>
                    </div>

                    {{-- Filtres --}}
                    <form action="{{ route('vendeur.factures') }}" method="GET">
                        <div class="filter-bar">
                            <select name="statut_paiement">
                                <option value="">Tous les statuts</option>
                                <option value="en_attente" {{ request('statut_paiement') === 'en_attente' ? 'selected' : '' }}>Impayées</option>
                                <option value="paye"       {{ request('statut_paiement') === 'paye'       ? 'selected' : '' }}>Payées</option>
                            </select>
                            <select name="mode_paie">
                                <option value="">Tout mode</option>
                                <option value="mtn"     {{ request('mode_paie') === 'mtn'     ? 'selected' : '' }}>MTN MoMo</option>
                                <option value="moov"    {{ request('mode_paie') === 'moov'    ? 'selected' : '' }}>Moov Money</option>
                                <option value="especes" {{ request('mode_paie') === 'especes' ? 'selected' : '' }}>Espèces</option>
                                <option value="carte"   {{ request('mode_paie') === 'carte'   ? 'selected' : '' }}>Carte</option>
                            </select>
                            <input type="date" name="date" value="{{ request('date') }}">
                            <button type="submit"><i class="fas fa-search"></i> Filtrer</button>
                            <a href="{{ route('vendeur.factures') }}" class="reset"><i class="fas fa-times"></i> Reset</a>
                        </div>
                    </form>

                    {{-- Tableau --}}
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>N° Facture</th>
                                <th>Client</th>
                                <th>Montant</th>
                                <th>Mode paiement</th>
                                <th>Date</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($factures as $facture)
                            @php $isPaye = $facture->commande->statut_paiement === 'paye'; @endphp
                            <tr>
                                <td>
                                    <span class="facture-tag">
                                        <i class="fas fa-file-invoice"></i> #{{ $facture->id_fact }}
                                    </span>
                                </td>
                                <td>
                                    <div style="font-weight:700;font-size:13px;">
                                        {{ $facture->commande->client->prenom_client ?? '—' }}
                                        {{ $facture->commande->client->nom_client ?? '' }}
                                    </div>
                                    <div style="font-size:11px;color:var(--muted);">
                                        {{ $facture->commande->reference ?? '' }}
                                    </div>
                                </td>
                                <td>
                                    <strong style="color:{{ $isPaye ? 'var(--green)' : 'var(--accent)' }};">
                                        {{ number_format($facture->montant_fact, 0, ',', ' ') }} FCFA
                                    </strong>
                                </td>
                                <td>
                                    <span class="mode-badge">
                                        @if($facture->mode_paie === 'mtn')     📱 MTN MoMo
                                        @elseif($facture->mode_paie === 'moov') 📱 Moov Money
                                        @elseif($facture->mode_paie === 'carte') 💳 Carte
                                        @else 💵 Espèces
                                        @endif
                                    </span>
                                </td>
                                <td style="font-size:12px;color:var(--muted);">
                                    {{ \Carbon\Carbon::parse($facture->date_fact)->format('d/m/Y') }}
                                </td>
                                <td>
                                    <span class="status-badge {{ $isPaye ? 'status-paye' : 'status-impayee' }}">
                                        {{ $isPaye ? '✅ Payée' : '⏳ Impayée' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-1 flex-wrap">
                                        <a href="{{ route('vendeur.factures.show', $facture->id_fact) }}" class="btn-sm-fm btn-primary-fm" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('vendeur.factures.pdf', $facture->id_fact) }}" class="btn-sm-fm btn-purple-fm" title="Imprimer" target="_blank">
                                            <i class="fas fa-print"></i>
                                        </a>
                                        @if(!$isPaye)
                                        <form action="{{ route('vendeur.factures.payer', $facture->id_fact) }}" method="POST" style="display:inline;">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="btn-sm-fm btn-success-fm" title="Marquer payée"
                                                onclick="return confirm('Confirmer le paiement de cette facture ?')">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" style="text-align:center;color:var(--muted);padding:40px;">
                                    <span style="font-size:40px;display:block;margin-bottom:10px;">🧾</span>
                                    Aucune facture trouvée
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{-- Pagination --}}
                    @if($factures->hasPages())
                    <div style="padding:16px;">
                        {{ $factures->withQueryString()->links() }}
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>

@endsection