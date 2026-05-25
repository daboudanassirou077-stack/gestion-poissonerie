@extends('layouts.dashboard')

@section('title', 'Mon Espace Client - FreshMarket')

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
        background: linear-gradient(135deg, #e8830a, #d4720a);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 24px; font-weight: 800; color: #fff;
        border: 3px solid rgba(255,255,255,.3);
        flex-shrink: 0;
    }

    /* ===== STATS CARDS ===== */
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
    }
    .stat-card:hover { transform: translateY(-4px); box-shadow: 0 8px 25px rgba(0,0,0,.1); }
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
    .stat-info h3 { font-size: 26px; font-weight: 800; color: var(--dark); margin: 0; }
    .stat-info p  { font-size: 13px; color: var(--muted); margin: 0; }

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
    .section-card-body { padding: 20px 22px; }

    /* ===== COMMANDES ===== */
    .order-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 0;
        border-bottom: 1px solid #f5f5f5;
        flex-wrap: wrap;
        gap: 10px;
    }
    .order-item:last-child { border-bottom: none; }
    .order-ref  { font-size: 14px; font-weight: 700; color: var(--dark); }
    .order-date { font-size: 12px; color: var(--muted); margin-top: 3px; }
    .order-total { font-size: 15px; font-weight: 800; color: var(--primary); }

    /* Badges statut */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .5px;
    }
    .status-en_attente    { background: rgba(232,131,10,.12); color: #a05c00; }
    .status-confirmee     { background: rgba(10,79,110,.1);   color: var(--primary); }
    .status-en_livraison  { background: rgba(39,174,96,.1);   color: var(--green); }
    .status-livree        { background: rgba(39,174,96,.15);  color: #1a7a3a; }
    .status-annulee       { background: rgba(192,57,43,.1);   color: var(--accent); }
    .status-dot { width: 6px; height: 6px; border-radius: 50%; background: currentColor; }

    /* ===== PROFIL ===== */
    .profile-row {
        display: flex;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #f5f5f5;
        font-size: 14px;
    }
    .profile-row:last-child { border-bottom: none; }
    .profile-label { color: var(--muted); font-weight: 600; min-width: 140px; }
    .profile-value { color: var(--dark); font-weight: 700; }

    /* ===== BOUTONS ===== */
    .btn-primary-fm {
        background: var(--primary);
        color: #fff;
        padding: 9px 20px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all .3s;
        border: none;
        cursor: pointer;
    }
    .btn-primary-fm:hover { background: var(--secondary); color: #fff; }
    .btn-outline-fm {
        background: transparent;
        color: var(--primary);
        border: 1.5px solid var(--primary);
        padding: 9px 20px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all .3s;
    }
    .btn-outline-fm:hover { background: var(--primary); color: #fff; }

    /* ===== MENU SIDEBAR ===== */
    .dash-menu {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,.06);
        overflow: hidden;
        margin-bottom: 24px;
    }
    .dash-menu-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 20px;
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

    /* Empty state */
    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: var(--muted);
    }
    .empty-state .empty-icon { font-size: 48px; margin-bottom: 14px; display: block; }
    .empty-state h5 { font-size: 16px; font-weight: 700; color: var(--dark); margin-bottom: 8px; }

    @media(max-width: 991px) {
        .dash-header h1 { font-size: 20px; }
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
                <h1>Bonjour, {{ auth()->user()->prenom }} ! 👋</h1>
                <p>{{ auth()->user()->email }} — Client depuis {{ auth()->user()->created_at->format('d/m/Y') }}</p>
            </div>
        </div>
    </div>
</div>

<div class="dashboard-wrapper">
    <div class="container">

        @if(session('success'))
        <div style="background:rgba(39,174,96,.1);border:1.5px solid rgba(39,174,96,.25);color:#1e7e44;border-radius:10px;padding:12px 16px;font-size:14px;font-weight:600;margin-bottom:20px;">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
        @endif

        <div class="row">

            {{-- ===== SIDEBAR MENU ===== --}}
            <div class="col-lg-3 mb-4">
                <div class="dash-menu">
                    <a href="{{ route('client.dashboard') }}" class="dash-menu-item active">
                        <i class="fas fa-tachometer-alt"></i> Tableau de bord
                    </a>
                    <a href="{{ route('cart') }}" class="dash-menu-item">
                        <i class="fas fa-shopping-cart"></i> Mon panier
                        @php $cartCount = collect(session('cart', []))->sum('quantite'); @endphp
                        @if($cartCount > 0)
                            <span class="badge bg-danger ms-auto">{{ $cartCount }}</span>
                        @endif
                    </a>
                    <a href="#mes-commandes" class="dash-menu-item">
                        <i class="fas fa-box"></i> Mes commandes
                    </a>
                    <a href="#mon-profil" class="dash-menu-item">
                        <i class="fas fa-user"></i> Mon profil
                    </a>
                    <a href="#changer-mdp" class="dash-menu-item">
                        <i class="fas fa-lock"></i> Changer mot de passe
                    </a>
                    <a href="{{ route('shop') }}" class="dash-menu-item">
                        <i class="fas fa-store"></i> Retour boutique
                    </a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dash-menu-item w-100 text-danger" style="background:none;border:none;text-align:left;">
                            <i class="fas fa-sign-out-alt"></i> Déconnexion
                        </button>
                    </form>
                </div>

                {{-- Bloc info livraison --}}
                <div style="background:#fff;border-radius:16px;padding:18px;box-shadow:0 2px 12px rgba(0,0,0,.06);">
                    <p style="font-size:12px;font-weight:700;color:var(--primary);text-transform:uppercase;letter-spacing:1px;margin-bottom:12px;">
                        📞 Besoin d'aide ?
                    </p>
                    <p style="font-size:13px;color:var(--muted);margin-bottom:8px;">
                        <i class="fab fa-whatsapp" style="color:#25d366;"></i>
                        <strong>+229 00 000 000</strong>
                    </p>
                    <p style="font-size:13px;color:var(--muted);margin:0;">
                        <i class="fas fa-clock" style="color:var(--primary);"></i>
                        Lun–Sam : 7h–20h
                    </p>
                </div>
            </div>

            {{-- ===== CONTENU PRINCIPAL ===== --}}
            <div class="col-lg-9">

                {{-- Stats --}}
                <div class="row mb-2">
                    <div class="col-6 col-md-3">
                        <div class="stat-card">
                            <div class="stat-icon blue">📦</div>
                            <div class="stat-info">
                                <h3>{{ auth()->user()->client->commandes->count() ?? 0 }}</h3>
                                <p>Commandes</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="stat-card">
                            <div class="stat-icon green">✅</div>
                            <div class="stat-info">
                                <h3>{{ auth()->user()->client->commandes->where('statut_cmd', 'livree')->count() ?? 0 }}</h3>
                                <p>Livrées</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="stat-card">
                            <div class="stat-icon orange">⏳</div>
                            <div class="stat-info">
                                <h3>{{ auth()->user()->client->commandes->where('statut_cmd', 'en_attente')->count() ?? 0 }}</h3>
                                <p>En attente</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="stat-card">
                            <div class="stat-icon blue">💰</div>
                            <div class="stat-info">
                                <h3>{{ number_format(auth()->user()->client->commandes->sum('montant_total') ?? 0, 0, ',', ' ') }}</h3>
                                <p>FCFA dépensés</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Mes commandes --}}
                <div class="section-card" id="mes-commandes">
                    <div class="section-card-header">
                        <h5>📦 Mes commandes récentes</h5>
                        <a href="{{ route('shop') }}" class="btn-primary-fm">
                            <i class="fas fa-plus"></i> Nouvelle commande
                        </a>
                    </div>
                    <div class="section-card-body">

                        @php
                            $commandes = auth()->user()->client->commandes->sortByDesc('created_at')->take(5) ?? collect();
                        @endphp

                        @if($commandes->isEmpty())
                            <div class="empty-state">
                                <span class="empty-icon">📭</span>
                                <h5>Aucune commande pour le moment</h5>
                                <p>Découvrez nos produits frais et passez votre première commande !</p>
                                <a href="{{ route('shop') }}" class="btn-primary-fm mt-3">
                                    <i class="fas fa-fish"></i> Aller à la boutique
                                </a>
                            </div>
                        @else
                            @foreach($commandes as $commande)
                            <div class="order-item">
                                <div>
                                    <p class="order-ref">{{ $commande->reference }}</p>
                                    <p class="order-date">
                                        <i class="fas fa-calendar-alt"></i>
                                        {{ $commande->created_at->format('d/m/Y à H:i') }}
                                    </p>
                                </div>
                                <span class="status-badge status-{{ $commande->statut_cmd }}">
                                    <span class="status-dot"></span>
                                    {{ ucfirst(str_replace('_', ' ', $commande->statut_cmd)) }}
                                </span>
                                <div class="text-end">
                                    <p class="order-total">{{ number_format($commande->montant_total, 0, ',', ' ') }} FCFA</p>
                                </div>
                            </div>
                            @endforeach
                        @endif

                    </div>
                </div>

                {{-- Mon profil --}}
                <div class="section-card" id="mon-profil">
                    <div class="section-card-header">
                        <h5>👤 Mon profil</h5>
                        <button class="btn-outline-fm" onclick="toggleEdit()">
                            <i class="fas fa-edit"></i> Modifier
                        </button>
                    </div>
                    <div class="section-card-body">

                        {{-- Vue profil --}}
                        <div id="profil-view">
                            <div class="profile-row">
                                <span class="profile-label">Prénom</span>
                                <span class="profile-value">{{ auth()->user()->prenom }}</span>
                            </div>
                            <div class="profile-row">
                                <span class="profile-label">Nom</span>
                                <span class="profile-value">{{ auth()->user()->nom }}</span>
                            </div>
                            <div class="profile-row">
                                <span class="profile-label">Email</span>
                                <span class="profile-value">{{ auth()->user()->email }}</span>
                            </div>
                            <div class="profile-row">
                                <span class="profile-label">Téléphone</span>
                                <span class="profile-value">{{ auth()->user()->telephone ?? 'Non renseigné' }}</span>
                            </div>
                            <div class="profile-row">
                                <span class="profile-label">Adresse</span>
                                <span class="profile-value">{{ auth()->user()->client->adresse ?? 'Non renseignée' }}</span>
                            </div>
                        </div>

                        {{-- Formulaire modification --}}
                        <form id="profil-form" action="{{ route('client.profile.update') }}" method="POST" style="display:none;">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label style="font-size:12px;font-weight:700;color:var(--primary);text-transform:uppercase;letter-spacing:.8px;display:block;margin-bottom:6px;">Prénom</label>
                                    <input type="text" name="prenom" class="form-control" value="{{ auth()->user()->prenom }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label style="font-size:12px;font-weight:700;color:var(--primary);text-transform:uppercase;letter-spacing:.8px;display:block;margin-bottom:6px;">Nom</label>
                                    <input type="text" name="nom" class="form-control" value="{{ auth()->user()->nom }}" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label style="font-size:12px;font-weight:700;color:var(--primary);text-transform:uppercase;letter-spacing:.8px;display:block;margin-bottom:6px;">Téléphone</label>
                                <input type="tel" name="telephone" class="form-control" value="{{ auth()->user()->telephone }}">
                            </div>
                            <div class="mb-3">
                                <label style="font-size:12px;font-weight:700;color:var(--primary);text-transform:uppercase;letter-spacing:.8px;display:block;margin-bottom:6px;">Adresse</label>
                                <input type="text" name="adresse" class="form-control" value="{{ auth()->user()->client->adresse ?? '' }}">
                            </div>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn-primary-fm">
                                    <i class="fas fa-save"></i> Enregistrer
                                </button>
                                <button type="button" class="btn-outline-fm" onclick="toggleEdit()">
                                    Annuler
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Changer mot de passe --}}
                <div class="section-card" id="changer-mdp">
                    <div class="section-card-header">
                        <h5>🔑 Changer mon mot de passe</h5>
                    </div>
                    <div class="section-card-body">
                        <form action="{{ route('client.password.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label style="font-size:12px;font-weight:700;color:var(--primary);text-transform:uppercase;letter-spacing:.8px;display:block;margin-bottom:6px;">
                                    Mot de passe actuel
                                </label>
                                <input type="password" name="mdp_actuel" class="form-control" placeholder="••••••••" required>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label style="font-size:12px;font-weight:700;color:var(--primary);text-transform:uppercase;letter-spacing:.8px;display:block;margin-bottom:6px;">
                                        Nouveau mot de passe
                                    </label>
                                    <input type="password" name="mdp" class="form-control" placeholder="Minimum 6 caractères" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label style="font-size:12px;font-weight:700;color:var(--primary);text-transform:uppercase;letter-spacing:.8px;display:block;margin-bottom:6px;">
                                        Confirmer
                                    </label>
                                    <input type="password" name="mdp_confirmation" class="form-control" placeholder="Répétez le mot de passe" required>
                                </div>
                            </div>
                            <button type="submit" class="btn-primary-fm">
                                <i class="fas fa-lock"></i> Mettre à jour
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function toggleEdit() {
        const view = document.getElementById('profil-view');
        const form = document.getElementById('profil-form');
        if (form.style.display === 'none') {
            view.style.display = 'none';
            form.style.display = 'block';
        } else {
            view.style.display = 'block';
            form.style.display = 'none';
        }
    }
</script>
@endsection