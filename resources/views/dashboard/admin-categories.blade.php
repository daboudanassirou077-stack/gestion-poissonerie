@extends('layouts.app')

@section('title', 'Gestion Catégories - Admin FreshMarket')

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
    .section-card-header { padding:18px 22px; border-bottom:2px solid var(--light-bg); display:flex; align-items:center; justify-content:space-between; }
    .section-card-header h5 { font-size:15px; font-weight:800; color:var(--dark); margin:0; }
    .section-card-body { padding:22px; }

    /* Formulaire ajout */
    .add-form { background:var(--light-bg); border-radius:12px; padding:20px; margin-bottom:24px; }
    .add-form h6 { font-size:14px; font-weight:700; color:var(--dark); margin-bottom:16px; }
    .form-label-fm { display:block; font-size:11px; font-weight:700; color:var(--primary); text-transform:uppercase; letter-spacing:.8px; margin-bottom:7px; }
    .form-ctrl { width:100%; padding:11px 14px; border:1.5px solid #e5e5e5; border-radius:10px; font-size:14px; color:var(--dark); background:#fff; transition:border-color .3s; }
    .form-ctrl:focus { outline:none; border-color:var(--primary); box-shadow:0 0 0 3px rgba(10,79,110,.08); }

    /* Catégories grid */
    .cat-grid { display:grid; grid-template-columns:repeat(2,1fr); gap:16px; }
    .cat-card { background:#fff; border:1.5px solid #e8e8e8; border-radius:14px; padding:18px 20px; display:flex; align-items:center; justify-content:space-between; transition:all .3s; }
    .cat-card:hover { border-color:var(--primary); box-shadow:0 4px 15px rgba(0,0,0,.08); }
    .cat-info { display:flex; align-items:center; gap:14px; }
    .cat-icon { width:46px; height:46px; border-radius:12px; background:rgba(10,79,110,.08); display:flex; align-items:center; justify-content:center; font-size:20px; flex-shrink:0; }
    .cat-name { font-size:15px; font-weight:700; color:var(--dark); margin-bottom:3px; }
    .cat-count { font-size:12px; color:var(--muted); }
    .cat-actions { display:flex; gap:6px; }

    .btn-sm-fm { padding:6px 12px; border-radius:8px; font-size:12px; font-weight:700; border:none; cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; gap:4px; transition:all .2s; }
    .btn-primary-fm { background:var(--primary); color:#fff; }
    .btn-primary-fm:hover { background:#083d56; color:#fff; }
    .btn-danger-fm { background:var(--accent); color:#fff; }
    .btn-danger-fm:hover { background:#a93226; color:#fff; }

    .alert-fm { border-radius:10px; padding:12px 16px; font-size:14px; font-weight:600; margin-bottom:20px; display:flex; align-items:center; gap:8px; }
    .alert-success-fm { background:rgba(39,174,96,.1); border:1.5px solid rgba(39,174,96,.25); color:#1e7e44; }
    .alert-error-fm   { background:rgba(192,57,43,.08); border:1.5px solid rgba(192,57,43,.2); color:var(--accent); }

    @media(max-width:576px) { .cat-grid { grid-template-columns:1fr; } }
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
                <h1>Gestion des Catégories 🏷️</h1>
                <p>{{ $categories->count() }} catégorie(s) enregistrée(s)</p>
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
                    <a href="{{ route('admin.users') }}" class="dash-menu-item">
                        <i class="fas fa-users"></i> Utilisateurs
                    </a>
                    <a href="{{ route('admin.create-user') }}" class="dash-menu-item">
                        <i class="fas fa-user-plus"></i> Créer un compte
                    </a>
                    <a href="{{ route('admin.categories') }}" class="dash-menu-item active">
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

                {{-- Formulaire ajout --}}
                <div class="section-card">
                    <div class="section-card-header">
                        <h5>➕ Ajouter une catégorie</h5>
                    </div>
                    <div class="section-card-body">
                        <form action="{{ route('admin.store-categorie') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-5 mb-3">
                                    <label class="form-label-fm">Libellé <span style="color:var(--accent)">*</span></label>
                                    <input type="text" name="libelle"
                                        class="form-ctrl"
                                        placeholder="Ex: Poissons frais"
                                        value="{{ old('libelle') }}" required>
                                </div>
                                <div class="col-md-5 mb-3">
                                    <label class="form-label-fm">Description</label>
                                    <input type="text" name="description"
                                        class="form-ctrl"
                                        placeholder="Description courte..."
                                        value="{{ old('description') }}">
                                </div>
                                <div class="col-md-2 mb-3 d-flex align-items-end">
                                    <button type="submit" class="btn-sm-fm btn-primary-fm w-100" style="padding:12px;">
                                        <i class="fas fa-plus"></i> Ajouter
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Liste catégories --}}
                <div class="section-card">
                    <div class="section-card-header">
                        <h5>🏷️ Toutes les catégories</h5>
                    </div>
                    <div class="section-card-body">

                        @php
                            $icons = [
                                'Poissons frais'  => '🐟',
                                'Poissons fumés'  => '🔥',
                                'Viandes de bœuf' => '🥩',
                                'Volailles'       => '🍗',
                                'Escargots'       => '🐌',
                                'Abats'           => '🫀',
                            ];
                        @endphp

                        @if($categories->isEmpty())
                            <div style="text-align:center;padding:40px;color:var(--muted);">
                                <span style="font-size:48px;display:block;margin-bottom:14px;">🏷️</span>
                                <p>Aucune catégorie pour le moment</p>
                            </div>
                        @else
                            <div class="cat-grid">
                                @foreach($categories as $cat)
                                <div class="cat-card">
                                    <div class="cat-info">
                                        <div class="cat-icon">
                                            {{ $icons[$cat->libelle] ?? '📦' }}
                                        </div>
                                        <div>
                                            <p class="cat-name">{{ $cat->libelle }}</p>
                                            <p class="cat-count">
                                                {{ $cat->produits_count }} produit(s)
                                                @if($cat->description)
                                                    — {{ $cat->description }}
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                    <div class="cat-actions">
                                        @if($cat->produits_count === 0)
                                        <form action="{{ route('admin.delete-categorie', $cat->id_categorie) }}"
                                              method="POST"
                                              onsubmit="return confirm('Supprimer la catégorie {{ $cat->libelle }} ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-sm-fm btn-danger-fm">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                        @else
                                        <span style="font-size:11px;color:var(--muted);font-style:italic;">
                                            Non supprimable
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @endif

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection