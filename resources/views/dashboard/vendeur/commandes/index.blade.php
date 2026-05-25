@extends('layouts.dashboard')

@section('title', 'Commandes - FreshMarket')

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

    /* Section card */
    .section-card { background:#fff; border-radius:16px; box-shadow:0 2px 12px rgba(0,0,0,.06); margin-bottom:24px; overflow:hidden; }
    .section-card-header { padding:16px 22px; border-bottom:2px solid var(--light-bg); display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px; }
    .section-card-header h5 { font-size:15px; font-weight:800; color:var(--dark); margin:0; }

    /* Filtres */
    .filter-bar { padding:16px 22px; background:var(--light-bg); border-bottom:1px solid #e8f0f5; display:flex; gap:10px; flex-wrap:wrap; align-items:center; }
    .filter-bar input, .filter-bar select { padding:9px 14px; border:2px solid #e0eaf0; border-radius:10px; font-size:13px; color:var(--dark); outline:none; background:#fff; transition:border .2s; }
    .filter-bar input:focus, .filter-bar select:focus { border-color:var(--primary); }
    .filter-bar button { padding:9px 18px; background:var(--primary); color:#fff; border:none; border-radius:10px; font-size:13px; font-weight:700; cursor:pointer; }
    .filter-bar a.reset { padding:9px 14px; background:#fff; color:var(--muted); border:2px solid #e0eaf0; border-radius:10px; font-size:13px; font-weight:600; text-decoration:none; }

    /* Table */
    .admin-table { width:100%; border-collapse:collapse; }
    .admin-table th { background:var(--light-bg); padding:11px 16px; font-size:11px; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:.8px; text-align:left; }
    .admin-table td { padding:13px 16px; border-bottom:1px solid #f5f5f5; font-size:14px; color:var(--dark); vertical-align:middle; }
    .admin-table tr:last-child td { border-bottom:none; }
    .admin-table tr:hover td { background:#fafafa; }

    /* Badges */
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

    /* Alert */
    .alert-fm { border-radius:10px; padding:12px 16px; font-size:14px; font-weight:600; margin-bottom:20px; display:flex; align-items:center; gap:8px; }
    .alert-success-fm { background:rgba(39,174,96,.1); border:1.5px solid rgba(39,174,96,.25); color:#1e7e44; }
    .alert-error-fm   { background:rgba(192,57,43,.08); border:1.5px solid rgba(192,57,43,.2); color:var(--accent); }

    /* Client avatar */
    .client-avatar { width:34px; height:34px; border-radius:50%; background:linear-gradient(135deg,var(--primary),#1a7fb5); display:inline-flex; align-items:center; justify-content:center; font-size:13px; font-weight:800; color:#fff; flex-shrink:0; }

    /* Pagination */
    .pagination { display:flex; gap:6px; justify-content:center; padding:20px; }
    .pagination .page-item .page-link { padding:7px 14px; border-radius:8px; border:2px solid #e0eaf0; color:var(--primary); font-weight:600; font-size:13px; text-decoration:none; transition:all .2s; }
    .pagination .page-item.active .page-link { background:var(--primary); color:#fff; border-color:var(--primary); }
    .pagination .page-item.disabled .page-link { color:var(--muted); pointer-events:none; }
</style>
@endsection

@section('content')

<div class="dash-header">
    <div class="container">
        <div class="d-flex align-items-center gap-3">
            <div class="dash-avatar">{{ strtoupper(substr(auth()->user()->prenom, 0, 1)) }}</div>
            <div>
                <h1>Gestion des commandes 📦</h1>
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
                    <a href="{{ route('vendeur.commandes') }}" class="dash-menu-item active">
                        <i class="fas fa-shopping-bag"></i> Commandes
                    </a>
                    <a href="{{ route('vendeur.livraisons') }}" class="dash-menu-item">
                        <i class="fas fa-truck"></i> Livraisons
                    </a>
                    <div class="dash-menu-title" style="margin-top:4px;">Gestion</div>
                    <a href="{{ route('vendeur.clients') }}" class="dash-menu-item">
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

                {{-- Résumé rapide --}}
                <div class="section-card">
                    <div class="section-card-header">
                        <h5>📊 Résumé</h5>
                    </div>
                    <div style="padding:16px;">
                        @php
                            $statutsCount = $commandes->groupBy('statut_cmd');
                            $statutLabels = [
                                'en_attente'     => ['label'=>'En attente',     'color'=>'#a05c00',  'bg'=>'rgba(232,131,10,.12)'],
                                'confirmee'      => ['label'=>'Confirmée',      'color'=>'#0a4f6e',  'bg'=>'rgba(10,79,110,.1)'],
                                'en_preparation' => ['label'=>'En préparation', 'color'=>'#6c3483',  'bg'=>'rgba(155,89,182,.1)'],
                                'en_livraison'   => ['label'=>'En livraison',   'color'=>'#27ae60',  'bg'=>'rgba(39,174,96,.1)'],
                                'livree'         => ['label'=>'Livrée',         'color'=>'#1a7a3a',  'bg'=>'rgba(39,174,96,.15)'],
                                'annulee'        => ['label'=>'Annulée',        'color'=>'#c0392b',  'bg'=>'rgba(192,57,43,.1)'],
                            ];
                        @endphp
                        @foreach($statutLabels as $key => $info)
                        @php $count = $commandes->where('statut_cmd', $key)->count(); @endphp
                        @if($count > 0)
                        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;">
                            <span style="font-size:13px;font-weight:600;color:var(--dark);">{{ $info['label'] }}</span>
                            <span style="background:{{ $info['bg'] }};color:{{ $info['color'] }};padding:3px 10px;border-radius:20px;font-size:12px;font-weight:700;">
                                {{ $count }}
                            </span>
                        </div>
                        @endif
                        @endforeach
                        <div style="border-top:1px solid #f0f0f0;margin-top:10px;padding-top:10px;display:flex;justify-content:space-between;">
                            <span style="font-size:13px;font-weight:800;">Total</span>
                            <span style="font-size:14px;font-weight:800;color:var(--primary);">{{ $commandes->total() }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Contenu --}}
            <div class="col-lg-9">
                <div class="section-card">
                    <div class="section-card-header">
                        <h5>📦 Toutes les commandes</h5>
                        <span style="font-size:13px;color:var(--muted);">{{ $commandes->total() }} commande(s)</span>
                    </div>

                    {{-- Filtres --}}
                    <form action="{{ route('vendeur.commandes') }}" method="GET">
                        <div class="filter-bar">
                            <input type="text" name="q" value="{{ request('q') }}" placeholder="Référence ou client...">
                            <select name="statut">
                                <option value="">Tous les statuts</option>
                                <option value="en_attente"     {{ request('statut') === 'en_attente'     ? 'selected' : '' }}>En attente</option>
                                <option value="confirmee"      {{ request('statut') === 'confirmee'      ? 'selected' : '' }}>Confirmée</option>
                                <option value="en_preparation" {{ request('statut') === 'en_preparation' ? 'selected' : '' }}>En préparation</option>
                                <option value="en_livraison"   {{ request('statut') === 'en_livraison'   ? 'selected' : '' }}>En livraison</option>
                                <option value="livree"         {{ request('statut') === 'livree'         ? 'selected' : '' }}>Livrée</option>
                                <option value="annulee"        {{ request('statut') === 'annulee'        ? 'selected' : '' }}>Annulée</option>
                            </select>
                            <select name="paiement">
                                <option value="">Tout paiement</option>
                                <option value="en_attente" {{ request('paiement') === 'en_attente' ? 'selected' : '' }}>Non payé</option>
                                <option value="paye"       {{ request('paiement') === 'paye'       ? 'selected' : '' }}>Payé</option>
                            </select>
                            <button type="submit"><i class="fas fa-search"></i> Filtrer</button>
                            <a href="{{ route('vendeur.commandes') }}" class="reset"><i class="fas fa-times"></i> Reset</a>
                        </div>
                    </form>

                    {{-- Tableau --}}
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Référence</th>
                                <th>Client</th>
                                <th>Montant</th>
                                <th>Statut</th>
                                <th>Paiement</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($commandes as $commande)
                            <tr>
                                <td><strong>{{ $commande->reference }}</strong></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="client-avatar">
                                            {{ strtoupper(substr($commande->client->prenom_client ?? '?', 0, 1)) }}
                                        </div>
                                        <div>
                                            <div style="font-weight:700;font-size:13px;">
                                                {{ $commande->client->prenom_client ?? '—' }}
                                                {{ $commande->client->nom_client ?? '' }}
                                            </div>
                                            <div style="font-size:11px;color:var(--muted);">
                                                {{ $commande->client->telephone ?? '' }}
                                            </div>
                                        </div>
                                    </div>
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
                                <td style="font-size:12px;color:var(--muted);">
                                    {{ $commande->created_at->format('d/m/Y') }}<br>
                                    <span style="font-size:11px;">{{ $commande->created_at->format('H:i') }}</span>
                                </td>
                                <td>
                                    <div class="d-flex gap-1 flex-wrap">
                                        <a href="{{ route('vendeur.commandes.show', $commande->id_cmd) }}" class="btn-sm-fm btn-primary-fm" title="Voir détail">
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
                                        @if($commande->statut_cmd === 'en_preparation')
                                        <a href="{{ route('vendeur.commandes.envoyer', $commande->id_cmd) }}" class="btn-sm-fm btn-success-fm" title="Envoyer en livraison">
                                            <i class="fas fa-truck"></i>
                                        </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" style="text-align:center;color:var(--muted);padding:40px;">
                                    <span style="font-size:40px;display:block;margin-bottom:10px;">📦</span>
                                    Aucune commande trouvée
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{-- Pagination --}}
                    @if($commandes->hasPages())
                    <div style="padding:16px;">
                        {{ $commandes->withQueryString()->links() }}
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>

@endsection