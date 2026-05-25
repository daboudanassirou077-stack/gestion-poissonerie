@extends('layouts.dashboard')

@section('title', 'Fiche client - FreshMarket')

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

    .info-grid { display:grid; grid-template-columns:1fr 1fr; gap:16px; padding:22px; }
    .info-block { background:var(--light-bg); border-radius:12px; padding:16px; }
    .info-block label { font-size:11px; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:.8px; display:block; margin-bottom:6px; }
    .info-block span { font-size:14px; font-weight:700; color:var(--dark); }

    .admin-table { width:100%; border-collapse:collapse; }
    .admin-table th { background:var(--light-bg); padding:11px 16px; font-size:11px; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:.8px; text-align:left; }
    .admin-table td { padding:13px 16px; border-bottom:1px solid #f5f5f5; font-size:14px; color:var(--dark); vertical-align:middle; }
    .admin-table tr:last-child td { border-bottom:none; }
    .admin-table tr:hover td { background:#fafafa; }

    .status-badge { display:inline-flex; align-items:center; gap:5px; padding:4px 10px; border-radius:20px; font-size:11px; font-weight:700; }
    .status-en_attente     { background:rgba(232,131,10,.12); color:#a05c00; }
    .status-confirmee      { background:rgba(10,79,110,.1);   color:var(--primary); }
    .status-en_preparation { background:rgba(155,89,182,.1);  color:#6c3483; }
    .status-en_livraison   { background:rgba(39,174,96,.1);   color:var(--green); }
    .status-livree         { background:rgba(39,174,96,.15);  color:#1a7a3a; }
    .status-annulee        { background:rgba(192,57,43,.1);   color:var(--accent); }

    .btn-sm-fm { padding:8px 18px; border-radius:8px; font-size:13px; font-weight:700; border:none; cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; gap:6px; transition:all .2s; }
    .btn-primary-fm { background:var(--primary); color:#fff; }
    .btn-primary-fm:hover { background:#083d56; color:#fff; }
    .btn-outline-fm { background:#fff; color:var(--primary); border:2px solid var(--primary); }
    .btn-outline-fm:hover { background:var(--primary); color:#fff; }

    /* Profil client hero */
    .client-hero { padding:24px; display:flex; align-items:center; gap:20px; border-bottom:2px solid var(--light-bg); }
    .client-hero-avatar { width:70px; height:70px; border-radius:50%; background:linear-gradient(135deg,var(--primary),#1a7fb5); display:flex; align-items:center; justify-content:center; font-size:28px; font-weight:800; color:#fff; flex-shrink:0; border:3px solid rgba(10,79,110,.2); }
    .client-hero-info h3 { font-size:20px; font-weight:800; color:var(--dark); margin:0 0 4px; }
    .client-hero-info p  { font-size:13px; color:var(--muted); margin:0; }

    /* Stats client */
    .client-stats { display:grid; grid-template-columns:repeat(3,1fr); gap:12px; padding:20px 22px; }
    .client-stat-box { background:var(--light-bg); border-radius:12px; padding:16px; text-align:center; }
    .client-stat-box h4 { font-size:22px; font-weight:800; color:var(--primary); margin:0; }
    .client-stat-box p  { font-size:12px; color:var(--muted); margin:4px 0 0; }

    .badge-vip { background:linear-gradient(135deg,#f39c12,#e67e22); color:#fff; padding:4px 12px; border-radius:20px; font-size:11px; font-weight:800; }

    @media(max-width:768px) {
        .info-grid { grid-template-columns:1fr; }
        .client-stats { grid-template-columns:1fr 1fr; }
    }
</style>
@endsection

@section('content')

<div class="dash-header">
    <div class="container">
        <div class="d-flex align-items-center gap-3">
            <div class="dash-avatar">{{ strtoupper(substr(auth()->user()->prenom, 0, 1)) }}</div>
            <div>
                <h1>Fiche client 👤</h1>
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

                {{-- Actions --}}
                <div class="section-card">
                    <div class="section-card-header"><h5>⚡ Actions</h5></div>
                    <div style="padding:16px;display:flex;flex-direction:column;gap:10px;">
                        <a href="{{ route('vendeur.clients') }}" class="btn-sm-fm btn-outline-fm w-100" style="justify-content:center;">
                            <i class="fas fa-arrow-left"></i> Retour clients
                        </a>
                        <a href="{{ route('vendeur.commandes') }}?client={{ $client->id_client }}" class="btn-sm-fm btn-primary-fm w-100" style="justify-content:center;">
                            <i class="fas fa-shopping-bag"></i> Commandes du client
                        </a>
                    </div>
                </div>
            </div>

            {{-- Contenu --}}
            <div class="col-lg-9">

                {{-- Profil hero --}}
                <div class="section-card">
                    <div class="client-hero">
                        <div class="client-hero-avatar">
                            {{ strtoupper(substr($client->prenom_client ?? '?', 0, 1)) }}
                        </div>
                        <div class="client-hero-info">
                            <h3>
                                {{ $client->prenom_client }} {{ $client->nom_client }}
                                @if(($client->commandes_sum_montant_total ?? 0) >= 100000)
                                    <span class="badge-vip ms-2">⭐ VIP</span>
                                @endif
                            </h3>
                            <p>📧 {{ $client->email ?? 'Email non renseigné' }}</p>
                            <p>📅 Membre depuis {{ $client->created_at->format('d/m/Y') }}</p>
                        </div>
                    </div>

                    {{-- Stats --}}
                    <div class="client-stats">
                        <div class="client-stat-box">
                            <h4>{{ $client->commandes_count ?? 0 }}</h4>
                            <p>Commandes</p>
                        </div>
                        <div class="client-stat-box">
                            <h4>{{ number_format($client->commandes_sum_montant_total ?? 0, 0, ',', ' ') }}</h4>
                            <p>FCFA dépensés</p>
                        </div>
                        <div class="client-stat-box">
                            <h4>{{ $client->commandes->where('statut_cmd','livree')->count() }}</h4>
                            <p>Livrées</p>
                        </div>
                    </div>
                </div>

                {{-- Infos personnelles --}}
                <div class="section-card">
                    <div class="section-card-header">
                        <h5>📋 Informations personnelles</h5>
                    </div>
                    <div class="info-grid">
                        <div class="info-block">
                            <label>Prénom</label>
                            <span>{{ $client->prenom_client ?? '—' }}</span>
                        </div>
                        <div class="info-block">
                            <label>Nom</label>
                            <span>{{ $client->nom_client ?? '—' }}</span>
                        </div>
                        <div class="info-block">
                            <label>Téléphone</label>
                            <span>📞 {{ $client->telephone ?? '—' }}</span>
                        </div>
                        <div class="info-block">
                            <label>Email</label>
                            <span>📧 {{ $client->email ?? '—' }}</span>
                        </div>
                        @if(isset($client->adresse))
                        <div class="info-block" style="grid-column:1/-1;">
                            <label>Adresse</label>
                            <span>📍 {{ $client->adresse }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Historique commandes --}}
                <div class="section-card">
                    <div class="section-card-header">
                        <h5>📦 Historique des commandes</h5>
                        <span style="font-size:13px;color:var(--muted);">
                            {{ $client->commandes->count() }} commande(s)
                        </span>
                    </div>
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Référence</th>
                                <th>Date</th>
                                <th>Montant</th>
                                <th>Statut</th>
                                <th>Paiement</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($client->commandes as $commande)
                            <tr>
                                <td><strong>{{ $commande->reference }}</strong></td>
                                <td style="font-size:12px;color:var(--muted);">
                                    {{ $commande->created_at->format('d/m/Y') }}
                                </td>
                                <td>
                                    <strong style="color:var(--primary);">
                                        {{ number_format($commande->montant_total, 0, ',', ' ') }} FCFA
                                    </strong>
                                </td>
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
                                    <a href="{{ route('vendeur.commandes.show', $commande->id_cmd) }}" class="btn-sm-fm btn-primary-fm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" style="text-align:center;color:var(--muted);padding:30px;">
                                    Aucune commande pour ce client
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                        @if($client->commandes->count() > 0)
                        <tfoot>
                            <tr style="background:var(--light-bg);">
                                <td colspan="2" style="padding:12px 16px;font-weight:800;">Total</td>
                                <td style="padding:12px 16px;font-weight:800;color:var(--primary);">
                                    {{ number_format($client->commandes->sum('montant_total'), 0, ',', ' ') }} FCFA
                                </td>
                                <td colspan="3"></td>
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