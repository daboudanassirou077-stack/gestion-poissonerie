@extends('layouts.dashboard')

@section('title', 'Gestion Utilisateurs - Admin FreshMarket')

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

    .section-card { background:#fff; border-radius:16px; box-shadow:0 2px 12px rgba(0,0,0,.06); margin-bottom:24px; overflow:hidden; }
    .section-card-header { padding:18px 22px; border-bottom:2px solid var(--light-bg); display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px; }
    .section-card-header h5 { font-size:15px; font-weight:800; color:var(--dark); margin:0; }

    .admin-table { width:100%; border-collapse:collapse; }
    .admin-table th { background:var(--light-bg); padding:12px 18px; font-size:11px; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:.8px; text-align:left; }
    .admin-table td { padding:14px 18px; border-bottom:1px solid #f5f5f5; font-size:14px; color:var(--dark); vertical-align:middle; }
    .admin-table tr:last-child td { border-bottom:none; }
    .admin-table tr:hover td { background:#fafafa; }

    .role-badge { display:inline-flex; align-items:center; gap:4px; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:700; text-transform:uppercase; }
    .role-admin   { background:rgba(192,57,43,.1);  color:var(--accent); }
    .role-gerant  { background:rgba(10,79,110,.1);  color:var(--primary); }
    .role-vendeur { background:rgba(232,131,10,.1); color:#a05c00; }
    .role-client  { background:rgba(39,174,96,.1);  color:var(--green); }
    .role-bloque  { background:rgba(0,0,0,.08);     color:#666; }

    .btn-sm-fm { padding:5px 12px; border-radius:6px; font-size:12px; font-weight:700; border:none; cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; gap:4px; transition:all .2s; }
    .btn-primary-fm { background:var(--primary); color:#fff; }
    .btn-primary-fm:hover { background:#083d56; color:#fff; }
    .btn-success-fm { background:var(--green); color:#fff; }
    .btn-success-fm:hover { background:#219a52; color:#fff; }
    .btn-danger-fm  { background:var(--accent); color:#fff; }
    .btn-danger-fm:hover { background:#a93226; color:#fff; }
    .btn-warning-fm { background:var(--secondary); color:#fff; }
    .btn-warning-fm:hover { background:#c97009; color:#fff; }

    .user-avatar-mini { width:34px; height:34px; border-radius:50%; display:inline-flex; align-items:center; justify-content:center; font-size:13px; font-weight:800; color:#fff; flex-shrink:0; }

    /* Filtre */
    .filter-bar { background:#fff; border-radius:12px; padding:16px 20px; box-shadow:0 2px 10px rgba(0,0,0,.05); margin-bottom:20px; display:flex; gap:12px; flex-wrap:wrap; align-items:center; }
    .filter-bar input, .filter-bar select { padding:8px 14px; border:1.5px solid #e0e0e0; border-radius:8px; font-size:13px; color:#444; background:#fff; }
    .filter-bar input:focus, .filter-bar select:focus { outline:none; border-color:var(--primary); }

    .alert-fm { border-radius:10px; padding:12px 16px; font-size:14px; font-weight:600; margin-bottom:20px; display:flex; align-items:center; gap:8px; }
    .alert-success-fm { background:rgba(39,174,96,.1);  border:1.5px solid rgba(39,174,96,.25);  color:#1e7e44; }
    .alert-error-fm   { background:rgba(192,57,43,.08); border:1.5px solid rgba(192,57,43,.2);   color:var(--accent); }
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
                <h1>Gestion des Utilisateurs 👥</h1>
                <p>{{ $users->total() }} utilisateur(s) enregistré(s)</p>
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
                    <div class="dash-menu-title">Navigation</div>
                    <a href="{{ route('admin.dashboard') }}" class="dash-menu-item">
                        <i class="fas fa-tachometer-alt"></i> Tableau de bord
                    </a>
                    <a href="{{ route('admin.users') }}" class="dash-menu-item active">
                        <i class="fas fa-users"></i> Utilisateurs
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
            </div>

            {{-- Contenu --}}
            <div class="col-lg-9">

                {{-- Filtre --}}
                <div class="filter-bar">
                    <form action="{{ route('admin.users') }}" method="GET" class="d-flex gap-2 flex-wrap w-100">
                        <input type="text" name="q" placeholder="Rechercher un utilisateur..." value="{{ request('q') }}" style="flex:1;min-width:200px;">
                        <select name="role">
                            <option value="">Tous les rôles</option>
                            <option value="client"  {{ request('role') === 'client'  ? 'selected' : '' }}>Clients</option>
                            <option value="gerant"  {{ request('role') === 'gerant'  ? 'selected' : '' }}>Gérants</option>
                            <option value="vendeur" {{ request('role') === 'vendeur' ? 'selected' : '' }}>Vendeurs</option>
                            <option value="admin"   {{ request('role') === 'admin'   ? 'selected' : '' }}>Admins</option>
                            <option value="bloque"  {{ request('role') === 'bloque'  ? 'selected' : '' }}>Bloqués</option>
                        </select>
                        <button type="submit" class="btn-sm-fm btn-primary-fm" style="padding:8px 16px;">
                            <i class="fas fa-search"></i> Filtrer
                        </button>
                        <a href="{{ route('admin.users') }}" class="btn-sm-fm" style="background:#f0f0f0;color:#666;padding:8px 16px;">
                            Réinitialiser
                        </a>
                    </form>
                </div>

                {{-- Table --}}
                <div class="section-card">
                    <div class="section-card-header">
                        <h5>👥 Liste des utilisateurs</h5>
                        <a href="{{ route('admin.create-user') }}" class="btn-sm-fm btn-primary-fm">
                            <i class="fas fa-user-plus"></i> Nouveau compte
                        </a>
                    </div>
                    <div class="section-card-body">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Utilisateur</th>
                                    <th>Email</th>
                                    <th>Téléphone</th>
                                    <th>Rôle</th>
                                    <th>Inscrit le</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="user-avatar-mini" style="background:linear-gradient(135deg,#0a4f6e,#1a7a9a);">
                                                {{ strtoupper(substr($user->prenom, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div style="font-weight:700;">{{ $user->prenom }} {{ $user->nom }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->telephone ?? '—' }}</td>
                                    <td>
                                        <span class="role-badge role-{{ $user->role }}">
                                            {{ $user->role }}
                                        </span>
                                    </td>
                                    <td>{{ $user->created_at ? $user->created_at->format('d/m/Y') : '-' }}</td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            @if($user->role !== 'admin')
                                            <form action="{{ route('admin.toggle-user', $user->id_user) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="btn-sm-fm {{ $user->role === 'bloque' ? 'btn-success-fm' : 'btn-warning-fm' }}"
                                                    title="{{ $user->role === 'bloque' ? 'Débloquer' : 'Bloquer' }}">
                                                    <i class="fas fa-{{ $user->role === 'bloque' ? 'unlock' : 'lock' }}"></i>
                                                    {{ $user->role === 'bloque' ? 'Débloquer' : 'Bloquer' }}
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.delete-user', $user->id_user) }}" method="POST" style="display:inline;"
                                                  onsubmit="return confirm('Supprimer le compte de {{ $user->prenom }} ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-sm-fm btn-danger-fm" title="Supprimer">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                            @else
                                                <span style="font-size:12px;color:var(--muted);font-style:italic;">Protégé</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" style="text-align:center;color:var(--muted);padding:40px;">
                                        Aucun utilisateur trouvé
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>

                        {{-- Pagination --}}
                        @if($users->hasPages())
                        <div style="padding:16px 18px;border-top:1px solid #f0f0f0;">
                            {{ $users->appends(request()->query())->links() }}
                        </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection