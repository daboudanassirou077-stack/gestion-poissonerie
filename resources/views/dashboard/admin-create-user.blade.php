@extends('layouts.app')

@section('title', 'Créer un compte - Admin FreshMarket')

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
    .dash-menu-item:hover { background:var(--light-bg); color:var(--primary); padding-left:26px; }
    .dash-menu-item.active { background:rgba(10,79,110,.06); color:var(--primary); border-left:3px solid var(--primary); }
    .dash-menu-item i { width:18px; text-align:center; color:var(--primary); }

    .form-card { background:#fff; border-radius:16px; padding:30px; box-shadow:0 2px 12px rgba(0,0,0,.06); }
    .form-card h4 { font-size:18px; font-weight:800; color:var(--dark); margin-bottom:6px; }
    .form-card p  { font-size:14px; color:var(--muted); margin-bottom:24px; }

    .form-label-fm { display:block; font-size:11px; font-weight:700; color:var(--primary); text-transform:uppercase; letter-spacing:.8px; margin-bottom:7px; }
    .form-label-fm em { color:var(--accent); font-style:normal; }
    .form-ctrl { width:100%; padding:12px 15px; border:1.5px solid #e5e5e5; border-radius:10px; font-size:14px; color:var(--dark); background:#fff; transition:border-color .3s; }
    .form-ctrl:focus { outline:none; border-color:var(--primary); box-shadow:0 0 0 3px rgba(10,79,110,.08); }
    .form-ctrl.is-invalid { border-color:var(--accent); }
    .field-error { font-size:12px; color:var(--accent); margin-top:4px; }

    /* Rôle cards */
    .role-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:12px; margin-bottom:20px; }
    .role-card { border:2px solid #e0e0e0; border-radius:12px; padding:16px; text-align:center; cursor:pointer; transition:all .25s; position:relative; }
    .role-card:hover { border-color:#aaa; }
    .role-card.selected-gerant  { border-color:var(--primary); background:rgba(10,79,110,.04); }
    .role-card.selected-vendeur { border-color:var(--secondary); background:rgba(232,131,10,.04); }
    .role-card.selected-admin   { border-color:var(--accent); background:rgba(192,57,43,.04); }
    .role-card input { position:absolute; opacity:0; pointer-events:none; }
    .role-icon { font-size:30px; margin-bottom:8px; }
    .role-name { font-size:14px; font-weight:800; color:var(--dark); margin-bottom:4px; }
    .role-desc { font-size:12px; color:var(--muted); }
    .role-check { position:absolute; top:8px; right:8px; width:20px; height:20px; border-radius:50%; background:var(--primary); color:#fff; display:none; align-items:center; justify-content:center; font-size:10px; }
    .role-card.selected-gerant .role-check,
    .role-card.selected-vendeur .role-check,
    .role-card.selected-admin .role-check { display:flex; }

    .btn-submit { background:var(--primary); color:#fff; border:none; padding:14px 30px; border-radius:10px; font-size:15px; font-weight:700; cursor:pointer; transition:all .3s; display:inline-flex; align-items:center; gap:8px; }
    .btn-submit:hover { background:var(--secondary); transform:translateY(-2px); }

    .alert-fm { border-radius:10px; padding:12px 16px; font-size:14px; font-weight:600; margin-bottom:20px; display:flex; align-items:center; gap:8px; }
    .alert-success-fm { background:rgba(39,174,96,.1); border:1.5px solid rgba(39,174,96,.25); color:#1e7e44; }
    .alert-error-fm   { background:rgba(192,57,43,.08); border:1.5px solid rgba(192,57,43,.2); color:var(--accent); }

    @media(max-width:576px) { .role-grid { grid-template-columns:1fr; } }
</style>
@endsection

@section('content')

<div class="dash-header">
    <div class="container">
        <div class="d-flex align-items-center gap-3">
            <div style="width:50px;height:50px;background:linear-gradient(135deg,#c0392b,#e74c3c);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:20px;font-weight:800;color:#fff;">
                {{ strtoupper(substr(auth()->user()->prenom, 0, 1)) }}
            </div>
            <div>
                <h1>Créer un nouveau compte 👤</h1>
                <p>Gérant, Vendeur ou Administrateur</p>
            </div>
        </div>
    </div>
</div>

<div class="dashboard-wrapper">
    <div class="container">

        @if(session('success'))
        <div class="alert-fm alert-success-fm"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
        @endif
        @if($errors->any())
        <div class="alert-fm alert-error-fm"><i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}</div>
        @endif

        <div class="row">

            {{-- Sidebar --}}
            <div class="col-lg-3 mb-4">
                <div class="dash-menu">
                    <div class="dash-menu-title">Navigation</div>
                    <a href="{{ route('admin.dashboard') }}" class="dash-menu-item">
                        <i class="fas fa-tachometer-alt"></i> Tableau de bord
                    </a>
                    <a href="{{ route('admin.users') }}" class="dash-menu-item">
                        <i class="fas fa-users"></i> Utilisateurs
                    </a>
                    <a href="{{ route('admin.create-user') }}" class="dash-menu-item active">
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
            </div>

            {{-- Formulaire --}}
            <div class="col-lg-9">
                <div class="form-card">
                    <h4>Informations du nouveau compte</h4>
                    <p>Créez un compte pour un gérant, vendeur ou administrateur du système.</p>

                    <form action="{{ route('admin.store-user') }}" method="POST">
                        @csrf

                        {{-- Choix du rôle --}}
                        <div class="mb-4">
                            <label class="form-label-fm">Rôle <em>*</em></label>
                            <div class="role-grid">

                                <label class="role-card" id="card-gerant">
                                    <input type="radio" name="role" value="gerant"
                                        {{ old('role', 'gerant') === 'gerant' ? 'checked' : '' }}
                                        onchange="selectRole('gerant')">
                                    <div class="role-check"><i class="fas fa-check" style="font-size:9px;"></i></div>
                                    <div class="role-icon">📊</div>
                                    <div class="role-name">Gérant</div>
                                    <div class="role-desc">Gère les produits et stocks</div>
                                </label>

                                <label class="role-card" id="card-vendeur">
                                    <input type="radio" name="role" value="vendeur"
                                        {{ old('role') === 'vendeur' ? 'checked' : '' }}
                                        onchange="selectRole('vendeur')">
                                    <div class="role-check"><i class="fas fa-check" style="font-size:9px;"></i></div>
                                    <div class="role-icon">🏪</div>
                                    <div class="role-name">Vendeur</div>
                                    <div class="role-desc">Crée et gère les commandes</div>
                                </label>

                                <label class="role-card" id="card-admin">
                                    <input type="radio" name="role" value="admin"
                                        {{ old('role') === 'admin' ? 'checked' : '' }}
                                        onchange="selectRole('admin')">
                                    <div class="role-check"><i class="fas fa-check" style="font-size:9px;"></i></div>
                                    <div class="role-icon">🛡️</div>
                                    <div class="role-name">Administrateur</div>
                                    <div class="role-desc">Accès complet au système</div>
                                </label>

                            </div>
                            @error('role')<p class="field-error">{{ $message }}</p>@enderror
                        </div>

                        {{-- Nom & Prénom --}}
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label-fm">Prénom <em>*</em></label>
                                <input type="text" name="prenom"
                                    class="form-ctrl @error('prenom') is-invalid @enderror"
                                    placeholder="Prénom"
                                    value="{{ old('prenom') }}" required>
                                @error('prenom')<p class="field-error">{{ $message }}</p>@enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label-fm">Nom <em>*</em></label>
                                <input type="text" name="nom"
                                    class="form-ctrl @error('nom') is-invalid @enderror"
                                    placeholder="Nom"
                                    value="{{ old('nom') }}" required>
                                @error('nom')<p class="field-error">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        {{-- Email --}}
                        <div class="mb-3">
                            <label class="form-label-fm">Email <em>*</em></label>
                            <input type="email" name="email"
                                class="form-ctrl @error('email') is-invalid @enderror"
                                placeholder="email@example.com"
                                value="{{ old('email') }}" required>
                            @error('email')<p class="field-error">{{ $message }}</p>@enderror
                        </div>

                        {{-- Téléphone --}}
                        <div class="mb-3">
                            <label class="form-label-fm">Téléphone <em>*</em></label>
                            <input type="tel" name="telephone"
                                class="form-ctrl @error('telephone') is-invalid @enderror"
                                placeholder="+229 00 000 000"
                                value="{{ old('telephone') }}" required>
                            @error('telephone')<p class="field-error">{{ $message }}</p>@enderror
                        </div>

                        {{-- Mot de passe --}}
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label-fm">Mot de passe <em>*</em></label>
                                <input type="password" name="mdp"
                                    class="form-ctrl @error('mdp') is-invalid @enderror"
                                    placeholder="Minimum 6 caractères" required>
                                @error('mdp')<p class="field-error">{{ $message }}</p>@enderror
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label-fm">Confirmer <em>*</em></label>
                                <input type="password" name="mdp_confirmation"
                                    class="form-ctrl"
                                    placeholder="Répétez le mot de passe" required>
                            </div>
                        </div>

                        <div class="d-flex gap-3 align-items-center">
                            <button type="submit" class="btn-submit">
                                <i class="fas fa-user-plus"></i> Créer le compte
                            </button>
                            <a href="{{ route('admin.users') }}" style="color:var(--muted);font-size:14px;text-decoration:none;">
                                Annuler
                            </a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function selectRole(role) {
        document.querySelectorAll('.role-card').forEach(c => {
            c.classList.remove('selected-gerant', 'selected-vendeur', 'selected-admin');
        });
        document.getElementById('card-' + role).classList.add('selected-' + role);
    }

    document.addEventListener('DOMContentLoaded', function () {
        const checked = document.querySelector('input[name="role"]:checked');
        if (checked) selectRole(checked.value);
        else selectRole('gerant');
    });
</script>
@endsection