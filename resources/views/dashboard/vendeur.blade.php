@extends('layouts.dashboard')

@section('title', 'Dashboard Vendeur - FreshMarket')

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
    .stat-card.purple { border-left-color:#8e44ad; }
    .stat-icon { width:50px; height:50px; border-radius:14px; display:flex; align-items:center; justify-content:center; font-size:22px; flex-shrink:0; }
    .stat-icon.blue   { background:rgba(10,79,110,.1); }
    .stat-icon.orange { background:rgba(232,131,10,.1); }
    .stat-icon.green  { background:rgba(39,174,96,.1); }
    .stat-icon.red    { background:rgba(192,57,43,.1); }
    .stat-icon.purple { background:rgba(142,68,173,.1); }
    .stat-info h3 { font-size:26px; font-weight:800; color:var(--dark); margin:0; }
    .stat-info p  { font-size:13px; color:var(--muted); margin:0; }

    /* Cards */
    .section-card { background:#fff; border-radius:16px; box-shadow:0 2px 12px rgba(0,0,0,.06); margin-bottom:24px; overflow:hidden; }
    .section-card-header { padding:16px 22px; border-bottom:2px solid var(--light-bg); display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px; }
    .section-card-header h5 { font-size:15px; font-weight:800; color:var(--dark); margin:0; }

    /* Searchbar */
    .search-bar-wrapper { padding:20px 22px; border-bottom:1px solid var(--light-bg); }
    .search-input-group { display:flex; gap:10px; flex-wrap:wrap; }
    .search-input-group input { flex:1; min-width:200px; padding:10px 16px; border:2px solid #e8f0f5; border-radius:10px; font-size:14px; color:var(--dark); outline:none; transition:border .2s; }
    .search-input-group input:focus { border-color:var(--primary); }
    .search-input-group select { padding:10px 14px; border:2px solid #e8f0f5; border-radius:10px; font-size:14px; color:var(--dark); outline:none; background:#fff; }
    .search-input-group button { padding:10px 20px; background:var(--primary); color:#fff; border:none; border-radius:10px; font-size:14px; font-weight:700; cursor:pointer; transition:background .2s; }
    .search-input-group button:hover { background:#083d56; }

    /* Produit card mini */
    .produit-result-grid { display:grid; grid-template-columns:repeat(auto-fill, minmax(180px,1fr)); gap:16px; padding:20px 22px; }
    .produit-card-mini { background:var(--light-bg); border-radius:12px; padding:14px; border:2px solid transparent; transition:all .2s; cursor:pointer; }
    .produit-card-mini:hover { border-color:var(--primary); background:#fff; transform:translateY(-2px); }
    .produit-card-mini .pname { font-size:13px; font-weight:700; color:var(--dark); margin-bottom:4px; }
    .produit-card-mini .pprice { font-size:15px; font-weight:800; color:var(--primary); }
    .produit-card-mini .pstock { font-size:11px; color:var(--muted); margin-top:4px; }

    /* Table */
    .admin-table { width:100%; border-collapse:collapse; }
    .admin-table th { background:var(--light-bg); padding:11px 16px; font-size:11px; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:.8px; text-align:left; }
    .admin-table td { padding:13px 16px; border-bottom:1px solid #f5f5f5; font-size:14px; color:var(--dark); vertical-align:middle; }
    .admin-table tr:last-child td { border-bottom:none; }
    .admin-table tr:hover td { background:#fafafa; }

    /* Badges statut */
    .status-badge { display:inline-flex; align-items:center; gap:5px; padding:4px 10px; border-radius:20px; font-size:11px; font-weight:700; }
    .status-en_attente     { background:rgba(232,131,10,.12); color:#a05c00; }
    .status-confirmee      { background:rgba(10,79,110,.1);   color:var(--primary); }
    .status-en_preparation { background:rgba(155,89,182,.1);  color:#6c3483; }
    .status-en_livraison   { background:rgba(39,174,96,.1);   color:var(--green); }
    .status-livree         { background:rgba(39,174,96,.15);  color:#1a7a3a; }
    .status-annulee        { background:rgba(192,57,43,.1);   color:var(--accent); }

    /* Boutons */
    .btn-sm-fm { padding:6px 14px; border-radius:8px; font-size:12px; font-weight:700; border:none; cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; gap:5px; transition:all .2s; }
    .btn-primary-fm { background:var(--primary); color:#fff; }
    .btn-primary-fm:hover { background:#083d56; color:#fff; }
    .btn-success-fm { background:var(--green); color:#fff; }
    .btn-success-fm:hover { background:#1e8449; color:#fff; }
    .btn-warning-fm { background:var(--secondary); color:#fff; }
    .btn-warning-fm:hover { background:#c9720a; color:#fff; }
    .btn-danger-fm  { background:var(--accent); color:#fff; }
    .btn-purple-fm  { background:#8e44ad; color:#fff; }
    .btn-purple-fm:hover { background:#7d3c98; color:#fff; }

    /* Alertes */
    .alert-fm { border-radius:10px; padding:12px 16px; font-size:14px; font-weight:600; margin-bottom:20px; display:flex; align-items:center; gap:8px; }
    .alert-success-fm { background:rgba(39,174,96,.1);  border:1.5px solid rgba(39,174,96,.25);  color:#1e7e44; }
    .alert-error-fm   { background:rgba(192,57,43,.08); border:1.5px solid rgba(192,57,43,.2);   color:var(--accent); }
    .alert-warning-fm { background:rgba(232,131,10,.1); border:1.5px solid rgba(232,131,10,.25); color:#a05c00; }
    .alert-info-fm    { background:rgba(10,79,110,.08); border:1.5px solid rgba(10,79,110,.2);   color:var(--primary); }

    /* Client avatar */
    .client-avatar { width:34px; height:34px; border-radius:50%; background:linear-gradient(135deg,var(--primary),#1a7fb5); display:inline-flex; align-items:center; justify-content:center; font-size:13px; font-weight:800; color:#fff; flex-shrink:0; }

    /* Facture tag */
    .facture-tag { display:inline-flex; align-items:center; gap:5px; padding:4px 10px; border-radius:8px; font-size:12px; font-weight:700; background:rgba(142,68,173,.1); color:#8e44ad; }

    /* Zone livraison */
    .zone-tag { display:inline-flex; align-items:center; gap:4px; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:700; background:rgba(39,174,96,.1); color:var(--green); }
</style>
@endsection

@section('content')

{{-- Header --}}
<div class="dash-header">
    <div class="container">
        <div class="d-flex align-items-center gap-3">
            <div class="dash-avatar">{{ strtoupper(substr(auth()->user()->prenom, 0, 1)) }}</div>
            <div>
                <h1>Dashboard Vendeur 🛒</h1>
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
        @if(session('info'))
        <div class="alert-fm alert-info-fm"><i class="fas fa-info-circle"></i> {{ session('info') }}</div>
        @endif

        <div class="row">

            {{-- ===== SIDEBAR ===== --}}
            <div class="col-lg-3 mb-4">
                <div class="dash-menu">
                    <div class="dash-menu-title">Ventes</div>
                    <a href="{{ route('vendeur.dashboard') }}" class="dash-menu-item active">
                        <i class="fas fa-tachometer-alt"></i> Tableau de bord
                    </a>
                    <a href="{{ route('vendeur.produits') }}" class="dash-menu-item">
                        <i class="fas fa-search"></i> Recherche produits
                    </a>
                    <a href="{{ route('vendeur.commandes') }}" class="dash-menu-item">
                        <i class="fas fa-shopping-bag"></i> Commandes
                        @if($stats['commandes_attente'] > 0)
                        <span class="badge bg-warning ms-auto">{{ $stats['commandes_attente'] }}</span>
                        @endif
                    </a>
                    <a href="{{ route('vendeur.livraisons') }}" class="dash-menu-item">
                        <i class="fas fa-truck"></i> Livraisons
                        @if($stats['livraisons_en_cours'] > 0)
                        <span class="badge bg-success ms-auto">{{ $stats['livraisons_en_cours'] }}</span>
                        @endif
                    </a>
                    <div class="dash-menu-title" style="margin-top:4px;">Gestion</div>
                    <a href="{{ route('vendeur.clients') }}" class="dash-menu-item">
                        <i class="fas fa-users"></i> Clients
                        <span class="badge bg-primary ms-auto">{{ $stats['total_clients'] }}</span>
                    </a>
                    <a href="{{ route('vendeur.factures') }}" class="dash-menu-item">
                        <i class="fas fa-file-invoice"></i> Factures
                        @if($stats['factures_impayees'] > 0)
                        <span class="badge bg-danger ms-auto">{{ $stats['factures_impayees'] }}</span>
                        @endif
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

            {{-- ===== CONTENU PRINCIPAL ===== --}}
            <div class="col-lg-9">

                {{-- Stats ligne 1 --}}
                <div class="row mb-2">
                    <div class="col-6 col-md-3">
                        <div class="stat-card orange">
                            <div class="stat-icon orange">📦</div>
                            <div class="stat-info">
                                <h3>{{ $stats['commandes_attente'] }}</h3>
                                <p>À traiter</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="stat-card green">
                            <div class="stat-icon green">🚚</div>
                            <div class="stat-info">
                                <h3>{{ $stats['livraisons_en_cours'] }}</h3>
                                <p>En livraison</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="stat-card blue">
                            <div class="stat-icon blue">👥</div>
                            <div class="stat-info">
                                <h3>{{ $stats['total_clients'] }}</h3>
                                <p>Clients</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="stat-card red">
                            <div class="stat-icon red">🧾</div>
                            <div class="stat-info">
                                <h3>{{ $stats['factures_impayees'] }}</h3>
                                <p>Factures impayées</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Stats ligne 2 --}}
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="stat-card green">
                            <div class="stat-icon green">💰</div>
                            <div class="stat-info">
                                <h3>{{ number_format($stats['ventes_du_jour'], 0, ',', ' ') }}</h3>
                                <p>FCFA ventes aujourd'hui</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card blue">
                            <div class="stat-icon blue">📋</div>
                            <div class="stat-info">
                                <h3>{{ $stats['commandes_livrees_mois'] }}</h3>
                                <p>Livrées ce mois</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card purple">
                            <div class="stat-icon purple">📊</div>
                            <div class="stat-info">
                                <h3>{{ number_format($stats['ca_mois'], 0, ',', ' ') }}</h3>
                                <p>FCFA CA ce mois</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ===== RECHERCHE PRODUITS ===== --}}
                <div class="section-card">
                    <div class="section-card-header">
                        <h5>🔍 Recherche rapide de produits</h5>
                        <a href="{{ route('vendeur.produits') }}" class="btn-sm-fm btn-primary-fm">
                            Catalogue complet <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                    <div class="search-bar-wrapper">
                        <form action="{{ route('vendeur.produits') }}" method="GET">
                            <div class="search-input-group">
                                <select name="categorie">
                                    <option value="">Toutes catégories</option>
                                    @foreach($categories as $cat)
                                    <option value="{{ $cat->id_categorie }}">{{ $cat->libelle }}</option>
                                    @endforeach
                                </select>
                                <input type="text" name="q" placeholder="Nom du produit...">
                                <button type="submit"><i class="fas fa-search"></i> Rechercher</button>
                            </div>
                        </form>
                    </div>
                    <div class="produit-result-grid">
                        @php
                            $icons = ['Poissons frais'=>'🐟','Poissons fumés'=>'🔥','Viandes de bœuf'=>'🥩','Volailles'=>'🍗','Escargots'=>'🐌','Abats'=>'🫀'];
                        @endphp
                        @forelse($produits_disponibles as $produit)
                        <div class="produit-card-mini">
                            <div style="font-size:26px;margin-bottom:6px;">
                                {{ $icons[$produit->categorie->libelle ?? ''] ?? '📦' }}
                            </div>
                            <div class="pname">{{ $produit->libelle_prod }}</div>
                            <div class="pprice">{{ number_format($produit->prix_vente, 0, ',', ' ') }} FCFA</div>
                            <div class="pstock">
                                Stock : <strong>{{ $produit->stock->quantite_stock ?? 0 }} {{ $produit->calibre->unite_vente ?? 'kg' }}</strong>
                            </div>
                        </div>
                        @empty
                        <div style="grid-column:1/-1;text-align:center;padding:30px;color:var(--muted);">
                            <span style="font-size:40px;display:block;margin-bottom:10px;">📦</span>
                            Aucun produit disponible
                        </div>
                        @endforelse
                    </div>
                </div>

                {{-- ===== COMMANDES À TRAITER ===== --}}
                <div class="section-card">
                    <div class="section-card-header">
                        <h5>📦 Commandes à traiter</h5>
                        <a href="{{ route('vendeur.commandes') }}" class="btn-sm-fm btn-primary-fm">
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
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($commandes_a_traiter as $commande)
                            <tr>
                                <td><strong>{{ $commande->reference }}</strong></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="client-avatar">
                                            {{ strtoupper(substr($commande->client->prenom_client ?? '?', 0, 1)) }}
                                        </div>
                                        <div>
                                            <div style="font-weight:700;font-size:13px;">
                                                {{ $commande->client->prenom_client ?? '—' }} {{ $commande->client->nom_client ?? '' }}
                                            </div>
                                            <div style="font-size:11px;color:var(--muted);">{{ $commande->client->telephone ?? '' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td><strong>{{ number_format($commande->montant_total, 0, ',', ' ') }} FCFA</strong></td>
                                <td>
                                    <span class="status-badge status-{{ $commande->statut_cmd }}">
                                        {{ ucfirst(str_replace('_', ' ', $commande->statut_cmd)) }}
                                    </span>
                                </td>
                                <td style="font-size:12px;color:var(--muted);">{{ $commande->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('vendeur.commandes.show', $commande->id_cmd) }}" class="btn-sm-fm btn-primary-fm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($commande->statut_cmd === 'en_attente')
                                        <form action="{{ route('vendeur.commandes.confirmer', $commande->id_cmd) }}" method="POST" style="display:inline;">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="btn-sm-fm btn-success-fm" title="Confirmer">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        @endif
                                        @if($commande->statut_cmd === 'confirmee')
                                        <form action="{{ route('vendeur.commandes.preparer', $commande->id_cmd) }}" method="POST" style="display:inline;">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="btn-sm-fm btn-warning-fm" title="Préparer">
                                                <i class="fas fa-box-open"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" style="text-align:center;color:var(--muted);padding:30px;">
                                    ✅ Aucune commande en attente
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- ===== LIVRAISONS EN COURS ===== --}}
                <div class="section-card">
                    <div class="section-card-header">
                        <h5>🚚 Livraisons en cours</h5>
                        <a href="{{ route('vendeur.livraisons') }}" class="btn-sm-fm btn-success-fm">
                            Gérer livraisons <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Commande</th>
                                <th>Client</th>
                                <th>Adresse</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($livraisons_en_cours as $livraison)
                            <tr>
                                <td><strong>{{ $livraison->commande->reference ?? '—' }}</strong></td>
                                <td>
                                    <div style="font-weight:700;font-size:13px;">
                                        {{ $livraison->commande->client->prenom_client ?? '—' }}
                                        {{ $livraison->commande->client->nom_client ?? '' }}
                                    </div>
                                    <div style="font-size:11px;color:var(--muted);">
                                        {{ $livraison->commande->client->telephone ?? '' }}
                                    </div>
                                </td>
                                <td>
                                    <span class="zone-tag">
                                        <i class="fas fa-map-marker-alt"></i>
                                        {{ $livraison->adresse_livcl ?? '—' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="status-badge status-en_livraison">🚚 En cours</span>
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('vendeur.livraisons.show', $livraison->id_livcl) }}" class="btn-sm-fm btn-primary-fm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <form action="{{ route('vendeur.livraisons.livrer', $livraison->id_livcl) }}" method="POST" style="display:inline;">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="btn-sm-fm btn-success-fm"
                                                onclick="return confirm('Confirmer la livraison ?')">
                                                <i class="fas fa-check-double"></i> Livré
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" style="text-align:center;color:var(--muted);padding:30px;">
                                    Aucune livraison en cours
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- ===== CLIENTS RÉCENTS ===== --}}
                <div class="section-card">
                    <div class="section-card-header">
                        <h5>👥 Clients récents</h5>
                        <a href="{{ route('vendeur.clients') }}" class="btn-sm-fm btn-primary-fm">
                            Voir tous <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Client</th>
                                <th>Téléphone</th>
                                <th>Commandes</th>
                                <th>Total dépensé</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($clients_recents as $client)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="client-avatar">
                                            {{ strtoupper(substr($client->prenom_client ?? '?', 0, 1)) }}
                                        </div>
                                        <div>
                                            <div style="font-weight:700;">{{ $client->prenom_client }} {{ $client->nom_client }}</div>
                                            <div style="font-size:11px;color:var(--muted);">{{ $client->email ?? '' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $client->telephone ?? '—' }}</td>
                                <td><span class="badge bg-primary">{{ $client->commandes_count ?? 0 }}</span></td>
                                <td>
                                    <strong style="color:var(--green);">
                                        {{ number_format($client->commandes_sum_montant_total ?? 0, 0, ',', ' ') }} FCFA
                                    </strong>
                                </td>
                                <td>
                                    <a href="{{ route('vendeur.clients.show', $client->id_client) }}" class="btn-sm-fm btn-primary-fm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" style="text-align:center;color:var(--muted);padding:30px;">
                                    Aucun client enregistré
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- ===== FACTURES IMPAYÉES ===== --}}
                @if($factures_impayees->count() > 0)
                <div class="section-card">
                    <div class="section-card-header">
                        <h5>🧾 Factures impayées</h5>
                        <a href="{{ route('vendeur.factures') }}" class="btn-sm-fm btn-purple-fm">
                            Gérer factures <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>N° Facture</th>
                                <th>Client</th>
                                <th>Montant</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($factures_impayees as $facture)
                            <tr>
                                <td>
                                    <span class="facture-tag">
                                        <i class="fas fa-file-invoice"></i> #{{ $facture->id_fact }}
                                    </span>
                                </td>
                                <td>
                                    <strong>
                                        {{ $facture->commande->client->prenom_client ?? '—' }}
                                        {{ $facture->commande->client->nom_client ?? '' }}
                                    </strong>
                                </td>
                                <td>
                                    <strong style="color:var(--accent);">
                                        {{ number_format($facture->montant_fact, 0, ',', ' ') }} FCFA
                                    </strong>
                                </td>
                                <td style="font-size:12px;color:var(--muted);">
                                    {{ $facture->date_fact }}
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('vendeur.factures.show', $facture->id_fact) }}" class="btn-sm-fm btn-primary-fm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('vendeur.factures.pdf', $facture->id_fact) }}" class="btn-sm-fm btn-purple-fm" target="_blank">
                                            <i class="fas fa-print"></i>
                                        </a>
                                        <form action="{{ route('vendeur.factures.payer', $facture->id_fact) }}" method="POST" style="display:inline;">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="btn-sm-fm btn-success-fm"
                                                onclick="return confirm('Confirmer le paiement ?')">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif

            </div>{{-- /col-lg-9 --}}
        </div>{{-- /row --}}
    </div>{{-- /container --}}
</div>{{-- /dashboard-wrapper --}}

@endsection