@extends('layouts.dashboard')

@section('title', 'Dashboard Admin - FreshMarket')

@section('styles')
<style>
    :root {
        --primary:   #0a4f6e;
        --secondary: #e8830a;
        --green:     #27ae60;
        --accent:    #c0392b;
        --light-bg:  #f4f9fc;
        --dark:      #1a1a2e;
        --muted:     #6c757d;
    }

    .dashboard-wrapper { background: var(--light-bg); min-height: 100vh; padding: 30px 0 60px; }

    /* ===== HEADER ===== */
    .dash-header {
        background: linear-gradient(135deg, #073a52, #0a4f6e);
        padding: 30px 0;
        margin-bottom: 30px;
    }
    .dash-header h1 { font-size: 24px; color: #fff; font-weight: 800; margin-bottom: 4px; }
    .dash-header p  { color: rgba(255,255,255,.7); font-size: 14px; margin: 0; }
    .dash-avatar {
        width: 60px; height: 60px;
        background: linear-gradient(135deg, #c0392b, #e74c3c);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 24px; font-weight: 800; color: #fff;
        border: 3px solid rgba(255,255,255,.3);
        flex-shrink: 0;
    }

    /* ===== STATS ===== */
    .stat-card {
        background: #fff;
        border-radius: 16px;
        padding: 22px 20px;
        box-shadow: 0 2px 12px rgba(0,0,0,.06);
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 20px;
        transition: all .3s;
        border-left: 4px solid transparent;
    }
    .stat-card:hover { transform: translateY(-4px); box-shadow: 0 8px 25px rgba(0,0,0,.1); }
    .stat-card.blue   { border-left-color: var(--primary); }
    .stat-card.orange { border-left-color: var(--secondary); }
    .stat-card.green  { border-left-color: var(--green); }
    .stat-card.red    { border-left-color: var(--accent); }
    .stat-icon {
        width: 52px; height: 52px;
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 22px; flex-shrink: 0;
    }
    .stat-icon.blue   { background: rgba(10,79,110,.1); }
    .stat-icon.orange { background: rgba(232,131,10,.1); }
    .stat-icon.green  { background: rgba(39,174,96,.1); }
    .stat-icon.red    { background: rgba(192,57,43,.1); }
    .stat-info h3 { font-size: 28px; font-weight: 800; color: var(--dark); margin: 0; }
    .stat-info p  { font-size: 13px; color: var(--muted); margin: 0; }

    /* ===== SIDEBAR MENU ===== */
    .dash-menu {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,.06);
        overflow: hidden;
        margin-bottom: 24px;
    }
    .dash-menu-title {
        padding: 14px 20px;
        font-size: 11px;
        font-weight: 700;
        color: var(--muted);
        text-transform: uppercase;
        letter-spacing: 1.2px;
        border-bottom: 1px solid #f0f0f0;
        background: var(--light-bg);
    }
    .dash-menu-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 13px 20px;
        border-bottom: 1px solid #f5f5f5;
        text-decoration: none;
        color: var(--dark);
        font-size: 14px;
        font-weight: 600;
        transition: all .2s;
    }
    .dash-menu-item:last-child { border-bottom: none; }
    .dash-menu-item:hover { background: var(--light-bg); color: var(--primary); padding-left: 26px; }
    .dash-menu-item.active { background: rgba(10,79,110,.06); color: var(--primary); border-left: 3px solid var(--primary); }
    .dash-menu-item i { width: 18px; text-align: center; color: var(--primary); }
    .dash-menu-item .badge { margin-left: auto; }

    /* ===== SECTION CARD ===== */
    .section-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,.06);
        margin-bottom: 24px;
        overflow: hidden;
    }
    .section-card-header {
        padding: 18px 22px;
        border-bottom: 2px solid var(--light-bg);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
    }
    .section-card-header h5 {
        font-size: 15px;
        font-weight: 800;
        color: var(--dark);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .section-card-body { padding: 0; }

    /* ===== TABLE ===== */
    .admin-table { width: 100%; border-collapse: collapse; }
    .admin-table th {
        background: var(--light-bg);
        padding: 12px 18px;
        font-size: 11px;
        font-weight: 700;
        color: var(--muted);
        text-transform: uppercase;
        letter-spacing: .8px;
        text-align: left;
    }
    .admin-table td {
        padding: 14px 18px;
        border-bottom: 1px solid #f5f5f5;
        font-size: 14px;
        color: var(--dark);
        vertical-align: middle;
    }
    .admin-table tr:last-child td { border-bottom: none; }
    .admin-table tr:hover td { background: #fafafa; }

    /* Role badges */
    .role-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
    }
    .role-admin   { background: rgba(192,57,43,.1);  color: var(--accent); }
    .role-gerant  { background: rgba(10,79,110,.1);  color: var(--primary); }
    .role-vendeur { background: rgba(232,131,10,.1); color: #a05c00; }
    .role-client  { background: rgba(39,174,96,.1);  color: var(--green); }
    .role-bloque  { background: rgba(0,0,0,.08);     color: #666; }

    /* Status commande */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
    }
    .status-en_attente   { background: rgba(232,131,10,.12); color: #a05c00; }
    .status-confirmee    { background: rgba(10,79,110,.1);   color: var(--primary); }
    .status-en_livraison { background: rgba(39,174,96,.1);   color: var(--green); }
    .status-livree       { background: rgba(39,174,96,.15);  color: #1a7a3a; }
    .status-annulee      { background: rgba(192,57,43,.1);   color: var(--accent); }

    /* Boutons */
    .btn-sm-fm {
        padding: 5px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 700;
        border: none;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: all .2s;
    }
    .btn-primary-fm { background: var(--primary); color: #fff; }
    .btn-primary-fm:hover { background: #083d56; color: #fff; }
    .btn-success-fm { background: var(--green); color: #fff; }
    .btn-success-fm:hover { background: #219a52; color: #fff; }
    .btn-danger-fm  { background: var(--accent); color: #fff; }
    .btn-danger-fm:hover { background: #a93226; color: #fff; }
    .btn-warning-fm { background: var(--secondary); color: #fff; }
    .btn-warning-fm:hover { background: #c97009; color: #fff; }

    /* Avatar mini */
    .user-avatar-mini {
        width: 34px; height: 34px;
        border-radius: 50%;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 13px; font-weight: 800; color: #fff;
        flex-shrink: 0;
    }

    /* Alert */
    .alert-fm {
        border-radius: 10px;
        padding: 12px 16px;
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .alert-success-fm { background: rgba(39,174,96,.1);  border: 1.5px solid rgba(39,174,96,.25);  color: #1e7e44; }
    .alert-error-fm   { background: rgba(192,57,43,.08); border: 1.5px solid rgba(192,57,43,.2);   color: var(--accent); }

    @media(max-width:991px) {
        .dash-header h1 { font-size: 20px; }
        .admin-table { font-size: 13px; }
    }
</style>
@endsection

@section('content')

{{-- ===== HEADER ===== --}}
<div class="dash-header">
    <div class="container">
        <div class="d-flex align-items-center gap-3">
            <div class="dash-avatar">
                {{ strtoupper(substr(auth()->user()->prenom, 0, 1)) }}
            </div>
            <div>
                <h1>Dashboard Administrateur 🛡️</h1>
                <p>{{ auth()->user()->prenom }} {{ auth()->user()->nom }} — {{ now()->format('d/m/Y') }}</p>
            </div>
        </div>
    </div>
</div>

<div class="dashboard-wrapper">
    <div class="container">

        {{-- Alertes --}}
        @if(session('success'))
        <div class="alert-fm alert-success-fm">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="alert-fm alert-error-fm">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
        @endif

        <div class="row">

            {{-- ===== SIDEBAR ===== --}}
            <div class="col-lg-3 mb-4">
                <div class="dash-menu">
                    <div class="dash-menu-title">Navigation</div>
                    <a href="{{ route('admin.dashboard') }}" class="dash-menu-item active">
                        <i class="fas fa-tachometer-alt"></i> Tableau de bord
                    </a>
                    <a href="{{ route('admin.users') }}" class="dash-menu-item">
                        <i class="fas fa-users"></i> Utilisateurs
                        <span class="badge bg-primary ms-auto">{{ $stats['total_users'] }}</span>
                    </a>
                    <a href="{{ route('admin.create-user') }}" class="dash-menu-item">
                        <i class="fas fa-user-plus"></i> Créer un compte
                    </a>
                    <a href="{{ route('admin.categories') }}" class="dash-menu-item">
                        <i class="fas fa-tags"></i> Catégories
                    </a>
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

                {{-- Info système --}}
                <div style="background:#fff;border-radius:16px;padding:18px;box-shadow:0 2px 12px rgba(0,0,0,.06);">
                    <p style="font-size:12px;font-weight:700;color:var(--primary);text-transform:uppercase;letter-spacing:1px;margin-bottom:12px;">
                        ⚙️ Système
                    </p>
                    <p style="font-size:13px;color:var(--muted);margin-bottom:8px;">
                        <i class="fas fa-server" style="color:var(--primary);"></i>
                        Laravel {{ app()->version() }}
                    </p>
                    <p style="font-size:13px;color:var(--muted);margin-bottom:8px;">
                        <i class="fas fa-database" style="color:var(--primary);"></i>
                        MySQL
                    </p>
                    <p style="font-size:13px;color:var(--muted);margin:0;">
                        <i class="fas fa-clock" style="color:var(--green);"></i>
                        <span style="color:var(--green);font-weight:700;">En ligne</span>
                    </p>
                </div>
            </div>

            {{-- ===== CONTENU PRINCIPAL ===== --}}
            <div class="col-lg-9">

                {{-- Stats --}}
                <div class="row mb-2">
                    <div class="col-6 col-md-3">
                        <div class="stat-card blue">
                            <div class="stat-icon blue">👥</div>
                            <div class="stat-info">
                                <h3>{{ $stats['total_users'] }}</h3>
                                <p>Utilisateurs</p>
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
                    <div class="col-6 col-md-3">
                        <div class="stat-card red">
                            <div class="stat-icon red">⏳</div>
                            <div class="stat-info">
                                <h3>{{ $stats['commandes_attente'] }}</h3>
                                <p>En attente</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Répartition comptes --}}
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="stat-card blue">
                            <div class="stat-icon blue">🛒</div>
                            <div class="stat-info">
                                <h3>{{ $stats['total_clients'] }}</h3>
                                <p>Clients</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card orange">
                            <div class="stat-icon orange">📊</div>
                            <div class="stat-info">
                                <h3>{{ $stats['total_gerants'] }}</h3>
                                <p>Gérants</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card green">
                            <div class="stat-icon green">🏪</div>
                            <div class="stat-info">
                                <h3>{{ $stats['total_vendeurs'] }}</h3>
                                <p>Vendeurs</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Derniers utilisateurs --}}
                <div class="section-card">
                    <div class="section-card-header">
                        <h5>👥 Derniers utilisateurs inscrits</h5>
                        <a href="{{ route('admin.users') }}" class="btn-sm-fm btn-primary-fm">
                            Voir tous <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                    <div class="section-card-body">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Utilisateur</th>
                                    <th>Email</th>
                                    <th>Rôle</th>
                                    <th>Inscrit le</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($derniers_users as $user)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="user-avatar-mini" style="background: linear-gradient(135deg, #0a4f6e, #1a7a9a);">
                                                {{ strtoupper(substr($user->prenom, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div style="font-weight:700;">{{ $user->prenom }} {{ $user->nom }}</div>
                                                <div style="font-size:12px;color:var(--muted);">{{ $user->telephone }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <span class="role-badge role-{{ $user->role }}">
                                            {{ $user->role }}
                                        </span>
                                    </td>
                                    <td>{{ optional($user->created_at)->format('d/m/Y') }}</td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            @if($user->role !== 'admin')
                                            <form action="{{ route('admin.toggle-user', $user->id_user) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="btn-sm-fm {{ $user->role === 'bloque' ? 'btn-success-fm' : 'btn-warning-fm' }}">
                                                    <i class="fas fa-{{ $user->role === 'bloque' ? 'unlock' : 'lock' }}"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.delete-user', $user->id_user) }}" method="POST" style="display:inline;"
                                                  onsubmit="return confirm('Supprimer ce compte ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-sm-fm btn-danger-fm">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                            @else
                                                <span style="font-size:12px;color:var(--muted);">Protégé</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Dernières commandes --}}
                <div class="section-card">
                    <div class="section-card-header">
                        <h5>📦 Dernières commandes</h5>
                    </div>
                    <div class="section-card-body">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Référence</th>
                                    <th>Date</th>
                                    <th>Montant</th>
                                    <th>Statut</th>
                                    <th>Paiement</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($dernieres_commandes as $commande)
                                <tr>
                                    <td><strong>{{ $commande->reference }}</strong></td>
                                    <td>{{ $commande->created_at->format('d/m/Y H:i') }}</td>
                                    <td><strong>{{ number_format($commande->montant_total, 0, ',', ' ') }} FCFA</strong></td>
                                    <td>
                                        <span class="status-badge status-{{ $commande->statut_cmd }}">
                                            {{ ucfirst(str_replace('_', ' ', $commande->statut_cmd)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="status-badge {{ $commande->statut_paiement === 'paye' ? 'status-livree' : 'status-en_attente' }}">
                                            {{ $commande->statut_paiement === 'paye' ? '✅ Payé' : '⏳ En attente' }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" style="text-align:center;color:var(--muted);padding:30px;">
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
</div>

@endsection