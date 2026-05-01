@extends('layouts.app')

@section('title', 'Approvisionnement - Gérant FreshMarket')

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
    .dash-menu-item:last-child { border-bottom:none; }
    .dash-menu-item:hover { background:var(--light-bg); color:var(--primary); padding-left:26px; }
    .dash-menu-item.active { background:rgba(10,79,110,.06); color:var(--primary); border-left:3px solid var(--primary); }
    .dash-menu-item i { width:18px; text-align:center; color:var(--primary); }

    .section-card { background:#fff; border-radius:16px; box-shadow:0 2px 12px rgba(0,0,0,.06); margin-bottom:24px; overflow:hidden; }
    .section-card-header { padding:16px 22px; border-bottom:2px solid var(--light-bg); display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px; }
    .section-card-header h5 { font-size:15px; font-weight:800; color:var(--dark); margin:0; }
    .section-card-body { padding:22px; }

    .form-label-fm { display:block; font-size:11px; font-weight:700; color:var(--primary); text-transform:uppercase; letter-spacing:.8px; margin-bottom:7px; }
    .form-label-fm em { color:var(--accent); font-style:normal; }
    .form-ctrl { width:100%; padding:11px 14px; border:1.5px solid #e5e5e5; border-radius:10px; font-size:14px; color:var(--dark); background:#fff; transition:border-color .3s; }
    .form-ctrl:focus { outline:none; border-color:var(--primary); box-shadow:0 0 0 3px rgba(10,79,110,.08); }

    .admin-table { width:100%; border-collapse:collapse; }
    .admin-table th { background:var(--light-bg); padding:11px 16px; font-size:11px; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:.8px; text-align:left; }
    .admin-table td { padding:12px 16px; border-bottom:1px solid #f5f5f5; font-size:14px; color:var(--dark); vertical-align:middle; }
    .admin-table tr:last-child td { border-bottom:none; }
    .admin-table tr:hover td { background:#fafafa; }

    .status-badge { display:inline-flex; align-items:center; gap:5px; padding:4px 10px; border-radius:20px; font-size:11px; font-weight:700; }
    .status-brouillon { background:rgba(0,0,0,.08);     color:#666; }
    .status-envoye    { background:rgba(10,79,110,.1);  color:var(--primary); }
    .status-recu      { background:rgba(39,174,96,.1);  color:var(--green); }
    .status-annule    { background:rgba(192,57,43,.1);  color:var(--accent); }

    .fournisseur-card { background:var(--light-bg); border-radius:12px; padding:16px; margin-bottom:12px; display:flex; align-items:center; justify-content:space-between; gap:12px; }
    .fournisseur-info h6 { font-size:14px; font-weight:700; color:var(--dark); margin:0 0 4px; }
    .fournisseur-info p  { font-size:12px; color:var(--muted); margin:0; }

    .btn-sm-fm { padding:6px 14px; border-radius:8px; font-size:12px; font-weight:700; border:none; cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; gap:5px; transition:all .2s; }
    .btn-primary-fm { background:var(--primary); color:#fff; }
    .btn-primary-fm:hover { background:#083d56; color:#fff; }
    .btn-success-fm { background:var(--green); color:#fff; }
    .btn-success-fm:hover { background:#219a52; color:#fff; }
    .btn-warning-fm { background:var(--secondary); color:#fff; }

    .alert-fm { border-radius:10px; padding:12px 16px; font-size:14px; font-weight:600; margin-bottom:20px; display:flex; align-items:center; gap:8px; }
    .alert-success-fm { background:rgba(39,174,96,.1); border:1.5px solid rgba(39,174,96,.25); color:#1e7e44; }
    .alert-error-fm   { background:rgba(192,57,43,.08); border:1.5px solid rgba(192,57,43,.2); color:var(--accent); }
</style>
@endsection

@section('content')

<div class="dash-header">
    <div class="container">
        <div class="d-flex align-items-center gap-3">
            <div style="width:50px;height:50px;background:linear-gradient(135deg,#27ae60,#2ecc71);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:20px;font-weight:800;color:#fff;">
                {{ strtoupper(substr(auth()->user()->prenom, 0, 1)) }}
            </div>
            <div>
                <h1>Approvisionnement 🏭</h1>
                <p>Gestion des fournisseurs et bons de commande</p>
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
                    <div class="dash-menu-title">Gestion</div>
                    <a href="{{ route('gerant.dashboard') }}" class="dash-menu-item">
                        <i class="fas fa-tachometer-alt"></i> Tableau de bord
                    </a>
                    <a href="{{ route('gerant.produits') }}" class="dash-menu-item">
                        <i class="fas fa-fish"></i> Produits
                    </a>
                    <a href="{{ route('gerant.stocks') }}" class="dash-menu-item">
                        <i class="fas fa-boxes"></i> Stocks
                    </a>
                    <a href="{{ route('gerant.commandes') }}" class="dash-menu-item">
                        <i class="fas fa-shopping-bag"></i> Commandes
                    </a>
                    <a href="{{ route('gerant.approvisionnement') }}" class="dash-menu-item active">
                        <i class="fas fa-truck-loading"></i> Approvisionnement
                    </a>
                    <div class="dash-menu-title">Rapports</div>
                    <a href="{{ route('gerant.etat-stock') }}" class="dash-menu-item">
                        <i class="fas fa-chart-bar"></i> État des stocks
                    </a>
                    <a href="{{ route('gerant.etat-ventes') }}" class="dash-menu-item">
                        <i class="fas fa-chart-line"></i> État des ventes
                    </a>
                    <div class="dash-menu-title">Système</div>
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

                <div class="row">

                    {{-- Formulaire ajout fournisseur --}}
                    <div class="col-lg-5 mb-4">
                        <div class="section-card">
                            <div class="section-card-header">
                                <h5>➕ Nouveau fournisseur</h5>
                            </div>
                            <div class="section-card-body">
                                <form action="{{ route('gerant.store-fournisseur') }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label-fm">Nom <em>*</em></label>
                                        <input type="text" name="nom_frs" class="form-ctrl" placeholder="Nom du fournisseur" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label-fm">Prénom</label>
                                        <input type="text" name="prenom_frs" class="form-ctrl" placeholder="Prénom">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label-fm">Téléphone <em>*</em></label>
                                        <input type="tel" name="telephone" class="form-ctrl" placeholder="+229 00 000 000" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label-fm">Email</label>
                                        <input type="email" name="email" class="form-ctrl" placeholder="email@example.com">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label-fm">Adresse</label>
                                        <input type="text" name="adresse" class="form-ctrl" placeholder="Adresse...">
                                    </div>
                                    <button type="submit" class="btn-sm-fm btn-success-fm w-100" style="padding:11px;justify-content:center;">
                                        <i class="fas fa-save"></i> Enregistrer
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- Liste fournisseurs --}}
                    <div class="col-lg-7 mb-4">
                        <div class="section-card">
                            <div class="section-card-header">
                                <h5>🏭 Fournisseurs ({{ $fournisseurs->count() }})</h5>
                            </div>
                            <div class="section-card-body">
                                @forelse($fournisseurs as $frs)
                                <div class="fournisseur-card">
                                    <div class="fournisseur-info">
                                        <h6>{{ $frs->nom_frs }} {{ $frs->prenom_frs }}</h6>
                                        <p>
                                            <i class="fas fa-phone" style="color:var(--primary);"></i>
                                            {{ $frs->telephone }}
                                            @if($frs->email)
                                                &nbsp;·&nbsp;
                                                <i class="fas fa-envelope" style="color:var(--primary);"></i>
                                                {{ $frs->email }}
                                            @endif
                                        </p>
                                        @if($frs->adresse)
                                        <p><i class="fas fa-map-marker-alt" style="color:var(--primary);"></i> {{ $frs->adresse }}</p>
                                        @endif
                                    </div>
                                </div>
                                @empty
                                <div style="text-align:center;padding:30px;color:var(--muted);">
                                    <span style="font-size:36px;display:block;margin-bottom:10px;">🏭</span>
                                    Aucun fournisseur enregistré
                                </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Bons de commande fournisseur --}}
                <div class="section-card">
                    <div class="section-card-header">
                        <h5>📋 Bons de commande fournisseurs</h5>
                    </div>
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>N° Bon</th>
                                <th>Fournisseur</th>
                                <th>Date</th>
                                <th>Montant</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bons as $bon)
                            <tr>
                                <td><strong>#{{ $bon->id_bon }}</strong></td>
                                <td>{{ $bon->fournisseur->nom_frs ?? '—' }}</td>
                                <td>{{ $bon->date_bon instanceof \Carbon\Carbon ? $bon->date_bon->format('d/m/Y') : $bon->date_bon }}</td>
                                <td><strong>{{ number_format($bon->montant_total, 0, ',', ' ') }} FCFA</strong></td>
                                <td>
                                    <span class="status-badge status-{{ $bon->statut }}">
                                        {{ ucfirst($bon->statut) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" style="text-align:center;color:var(--muted);padding:40px;">
                                    Aucun bon de commande pour le moment
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                    @if($bons->hasPages())
                    <div style="padding:16px 18px;border-top:1px solid #f0f0f0;">
                        {{ $bons->links() }}
                    </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>

@endsection