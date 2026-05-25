@extends('layouts.dashboard')

@section('title', 'Clients - FreshMarket')

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
    .filter-bar input { padding:9px 14px; border:2px solid #e0eaf0; border-radius:10px; font-size:13px; color:var(--dark); outline:none; background:#fff; transition:border .2s; flex:1; min-width:200px; }
    .filter-bar input:focus { border-color:var(--primary); }
    .filter-bar button { padding:9px 18px; background:var(--primary); color:#fff; border:none; border-radius:10px; font-size:13px; font-weight:700; cursor:pointer; }
    .filter-bar a.reset { padding:9px 14px; background:#fff; color:var(--muted); border:2px solid #e0eaf0; border-radius:10px; font-size:13px; font-weight:600; text-decoration:none; }

    .admin-table { width:100%; border-collapse:collapse; }
    .admin-table th { background:var(--light-bg); padding:11px 16px; font-size:11px; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:.8px; text-align:left; }
    .admin-table td { padding:13px 16px; border-bottom:1px solid #f5f5f5; font-size:14px; color:var(--dark); vertical-align:middle; }
    .admin-table tr:last-child td { border-bottom:none; }
    .admin-table tr:hover td { background:#fafafa; }

    .btn-sm-fm { padding:6px 14px; border-radius:8px; font-size:12px; font-weight:700; border:none; cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; gap:5px; transition:all .2s; }
    .btn-primary-fm { background:var(--primary); color:#fff; }
    .btn-primary-fm:hover { background:#083d56; color:#fff; }
    .btn-success-fm { background:var(--green); color:#fff; }
    .btn-success-fm:hover { background:#1e8449; color:#fff; }

    .alert-fm { border-radius:10px; padding:12px 16px; font-size:14px; font-weight:600; margin-bottom:20px; display:flex; align-items:center; gap:8px; }
    .alert-success-fm { background:rgba(39,174,96,.1); border:1.5px solid rgba(39,174,96,.25); color:#1e7e44; }

    .client-avatar { width:40px; height:40px; border-radius:50%; background:linear-gradient(135deg,var(--primary),#1a7fb5); display:inline-flex; align-items:center; justify-content:center; font-size:15px; font-weight:800; color:#fff; flex-shrink:0; }

    /* Badge VIP */
    .badge-vip { background:linear-gradient(135deg,#f39c12,#e67e22); color:#fff; padding:3px 10px; border-radius:20px; font-size:10px; font-weight:800; }
    .badge-new { background:rgba(10,79,110,.1); color:var(--primary); padding:3px 10px; border-radius:20px; font-size:10px; font-weight:800; }

    /* Stat mini */
    .stat-mini { background:#fff; border-radius:12px; padding:16px; box-shadow:0 2px 8px rgba(0,0,0,.05); display:flex; align-items:center; gap:12px; border-left:4px solid transparent; margin-bottom:12px; }
    .stat-mini.blue   { border-left-color:var(--primary); }
    .stat-mini.green  { border-left-color:var(--green); }
    .stat-mini.orange { border-left-color:var(--secondary); }
    .stat-mini-icon { width:40px; height:40px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:18px; }
    .stat-mini-icon.blue   { background:rgba(10,79,110,.1); }
    .stat-mini-icon.green  { background:rgba(39,174,96,.1); }
    .stat-mini-icon.orange { background:rgba(232,131,10,.1); }
    .stat-mini h4 { font-size:20px; font-weight:800; color:var(--dark); margin:0; }
    .stat-mini p  { font-size:12px; color:var(--muted); margin:0; }
</style>
@endsection

@section('content')

<div class="dash-header">
    <div class="container">
        <div class="d-flex align-items-center gap-3">
            <div class="dash-avatar">{{ strtoupper(substr(auth()->user()->prenom, 0, 1)) }}</div>
            <div>
                <h1>Gestion des clients 👥</h1>
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
                    <a href="{{ route('vendeur.clients') }}" class="dash-menu-item active">
                        <i class="fas fa-users"></i> Clients
                    </a>
                    <a href="{{ route('vendeur.factures') }}" class="dash-menu-item">
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
                    $totalClients  = $clients->total();
                    $clientsActifs = $clients->filter(fn($c) => $c->commandes_count > 0)->count();
                    $caTotal       = $clients->sum('commandes_sum_montant_total');
                @endphp
                <div class="stat-mini blue">
                    <div class="stat-mini-icon blue">👥</div>
                    <div><h4>{{ $totalClients }}</h4><p>Total clients</p></div>
                </div>
                <div class="stat-mini green">
                    <div class="stat-mini-icon green">✅</div>
                    <div><h4>{{ $clientsActifs }}</h4><p>Clients actifs</p></div>
                </div>
                <div class="stat-mini orange">
                    <div class="stat-mini-icon orange">💰</div>
                    <div>
                        <h4 style="font-size:15px;">{{ number_format($caTotal, 0, ',', ' ') }}</h4>
                        <p>FCFA total</p>
                    </div>
                </div>
            </div>

            {{-- Contenu --}}
            <div class="col-lg-9">
                <div class="section-card">
                    <div class="section-card-header">
                        <h5>👥 Tous les clients</h5>
                        <span style="font-size:13px;color:var(--muted);">{{ $clients->total() }} client(s)</span>
                    </div>

                    {{-- Filtre recherche --}}
                    <form action="{{ route('vendeur.clients') }}" method="GET">
                        <div class="filter-bar">
                            <input type="text" name="q" value="{{ request('q') }}"
                                placeholder="Nom, prénom ou téléphone...">
                            <button type="submit"><i class="fas fa-search"></i> Rechercher</button>
                            <a href="{{ route('vendeur.clients') }}" class="reset">
                                <i class="fas fa-times"></i> Reset
                            </a>
                        </div>
                    </form>

                    {{-- Tableau --}}
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Client</th>
                                <th>Téléphone</th>
                                <th>Commandes</th>
                                <th>Total dépensé</th>
                                <th>Membre depuis</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($clients as $client)
                            @php
                                $isVip = ($client->commandes_sum_montant_total ?? 0) >= 100000;
                                $isNew = $client->created_at->diffInDays(now()) <= 30;
                            @endphp
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="client-avatar">
                                            {{ strtoupper(substr($client->prenom_client ?? '?', 0, 1)) }}
                                        </div>
                                        <div>
                                            <div style="font-weight:700;font-size:13px;">
                                                {{ $client->prenom_client }} {{ $client->nom_client }}
                                                @if($isVip)
                                                    <span class="badge-vip ms-1">⭐ VIP</span>
                                                @elseif($isNew)
                                                    <span class="badge-new ms-1">🆕 Nouveau</span>
                                                @endif
                                            </div>
                                            <div style="font-size:11px;color:var(--muted);">
                                                {{ $client->email ?? '' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $client->telephone ?? '—' }}</td>
                                <td>
                                    <span class="badge bg-primary">{{ $client->commandes_count ?? 0 }}</span>
                                </td>
                                <td>
                                    <strong style="color:{{ ($client->commandes_sum_montant_total ?? 0) > 0 ? 'var(--green)' : 'var(--muted)' }};">
                                        {{ number_format($client->commandes_sum_montant_total ?? 0, 0, ',', ' ') }} FCFA
                                    </strong>
                                </td>
                                <td style="font-size:12px;color:var(--muted);">
                                    {{ $client->created_at->format('d/m/Y') }}
                                </td>
                                <td>
                                    <a href="{{ route('vendeur.clients.show', $client->id_client) }}"
                                        class="btn-sm-fm btn-primary-fm" title="Voir fiche client">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" style="text-align:center;color:var(--muted);padding:40px;">
                                    <span style="font-size:40px;display:block;margin-bottom:10px;">👥</span>
                                    Aucun client trouvé
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{-- Pagination --}}
                    @if($clients->hasPages())
                    <div style="padding:16px;">
                        {{ $clients->withQueryString()->links() }}
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>

@endsection