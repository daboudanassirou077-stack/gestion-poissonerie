@extends('layouts.dashboard')

@section('title', 'Assigner un livreur - FreshMarket')

@section('styles')
<style>
    :root { --primary:#0a4f6e; --secondary:#e8830a; --green:#27ae60; --accent:#c0392b; --light-bg:#f4f9fc; --dark:#1a1a2e; --muted:#6c757d; }
    .dashboard-wrapper { background:var(--light-bg); min-height:100vh; padding:30px 0 60px; }
    .dash-header { background:linear-gradient(135deg,#073a52,#0a4f6e); padding:30px 0; margin-bottom:30px; }
    .dash-header h1 { font-size:24px; color:#fff; font-weight:800; margin-bottom:4px; }
    .dash-header p { color:rgba(255,255,255,.7); font-size:14px; margin:0; }
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
    .form-label-fm { display:block; font-size:11px; font-weight:700; color:var(--primary); text-transform:uppercase; letter-spacing:.8px; margin-bottom:7px; }
    .form-ctrl { width:100%; padding:11px 14px; border:1.5px solid #e5e5e5; border-radius:10px; font-size:14px; color:var(--dark); background:#fff; transition:border-color .3s; }
    .form-ctrl:focus { outline:none; border-color:var(--primary); box-shadow:0 0 0 3px rgba(10,79,110,.08); }
    .btn-sm-fm { padding:10px 20px; border-radius:8px; font-size:13px; font-weight:700; border:none; cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; gap:6px; transition:all .2s; }
    .btn-success-fm { background:var(--green); color:#fff; }
    .btn-success-fm:hover { background:#1e8449; color:#fff; }
    .btn-outline-fm { background:#fff; color:var(--primary); border:2px solid var(--primary); }
    .btn-outline-fm:hover { background:var(--primary); color:#fff; }
    .info-grid { display:grid; grid-template-columns:1fr 1fr; gap:16px; padding:22px; }
    .info-block { background:var(--light-bg); border-radius:12px; padding:16px; }
    .info-block label { font-size:11px; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:.8px; display:block; margin-bottom:6px; }
    .info-block span { font-size:14px; font-weight:700; color:var(--dark); }
    .livreur-option { display:flex; align-items:center; gap:12px; padding:14px 16px; border:2px solid #e0eaf0; border-radius:12px; cursor:pointer; transition:all .2s; margin-bottom:10px; }
    .livreur-option:hover { border-color:var(--primary); background:rgba(10,79,110,.03); }
    .livreur-option input[type="radio"] { display:none; }
    .livreur-option.selected { border-color:var(--primary); background:rgba(10,79,110,.06); }
    .livreur-avatar { width:42px; height:42px; border-radius:50%; background:linear-gradient(135deg,var(--green),#2ecc71); display:flex; align-items:center; justify-content:center; font-size:16px; font-weight:800; color:#fff; flex-shrink:0; }
    .livreur-name { font-size:14px; font-weight:700; color:var(--dark); }
    .livreur-tel { font-size:12px; color:var(--muted); }
    .alert-fm { border-radius:10px; padding:12px 16px; font-size:14px; font-weight:600; margin-bottom:20px; display:flex; align-items:center; gap:8px; }
    .alert-success-fm { background:rgba(39,174,96,.1); border:1.5px solid rgba(39,174,96,.25); color:#1e7e44; }
    @media(max-width:768px) { .info-grid { grid-template-columns:1fr; } }
</style>
@endsection

@section('content')

<div class="dash-header">
    <div class="container">
        <div class="d-flex align-items-center gap-3">
            <div class="dash-avatar">{{ strtoupper(substr(auth()->user()->prenom, 0, 1)) }}</div>
            <div>
                <h1>🚚 Assigner un livreur</h1>
                <p>Commande #{{ $commande->reference }} — {{ now()->format('d/m/Y') }}</p>
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
                    <a href="{{ route('vendeur.dashboard') }}" class="dash-menu-item"><i class="fas fa-tachometer-alt"></i> Tableau de bord</a>
                    <a href="{{ route('vendeur.produits') }}" class="dash-menu-item"><i class="fas fa-search"></i> Recherche produits</a>
                    <a href="{{ route('vendeur.commandes') }}" class="dash-menu-item active"><i class="fas fa-shopping-bag"></i> Commandes</a>
                    <a href="{{ route('vendeur.livraisons') }}" class="dash-menu-item"><i class="fas fa-truck"></i> Livraisons</a>
                    <div class="dash-menu-title" style="margin-top:4px;">Gestion</div>
                    <a href="{{ route('vendeur.clients') }}" class="dash-menu-item"><i class="fas fa-users"></i> Clients</a>
                    <a href="{{ route('vendeur.factures') }}" class="dash-menu-item"><i class="fas fa-file-invoice"></i> Factures</a>
                    <div class="dash-menu-title" style="margin-top:4px;">Système</div>
                    <a href="{{ route('home') }}" class="dash-menu-item"><i class="fas fa-home"></i> Voir le site</a>
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

                {{-- Infos commande --}}
                <div class="section-card">
                    <div class="section-card-header">
                        <h5>📦 Commande à livrer</h5>
                    </div>
                    <div class="info-grid">
                        <div class="info-block">
                            <label>Référence</label>
                            <span>{{ $commande->reference }}</span>
                        </div>
                        <div class="info-block">
                            <label>Client</label>
                            <span>{{ $commande->client->prenom_client ?? '—' }} {{ $commande->client->nom_client ?? '' }}</span>
                        </div>
                        <div class="info-block">
                            <label>Téléphone</label>
                            <span>{{ $commande->client->telephone ?? '—' }}</span>
                        </div>
                        <div class="info-block">
                            <label>Montant</label>
                            <span style="color:var(--primary);">{{ number_format($commande->montant_total, 0, ',', ' ') }} FCFA</span>
                        </div>
                        <div class="info-block" style="grid-column:1/-1;">
                            <label>Adresse de livraison</label>
                            <span>📍 {{ $commande->adresse_livraison ?? '—' }} {{ $commande->quartier ? '— '.$commande->quartier : '' }}</span>
                        </div>
                    </div>
                </div>

                {{-- Formulaire assignation --}}
                <div class="section-card">
                    <div class="section-card-header">
                        <h5>🚚 Choisir le livreur et la date</h5>
                    </div>
                    <div style="padding:22px;">
                        <form action="{{ route('vendeur.commandes.assigner-livreur', $commande->id_cmd) }}" method="POST">
                            @csrf

                            {{-- Choix livreur --}}
                            <div class="mb-4">
                                <label class="form-label-fm">Livreur <span style="color:var(--accent)">*</span></label>
                                @forelse($livreurs as $livreur)
                                <label class="livreur-option" id="livreur-{{ $livreur->id_livreur }}"
                                       onclick="selectLivreur({{ $livreur->id_livreur }})">
                                    <input type="radio" name="id_livreur"
                                           value="{{ $livreur->id_livreur }}" required>
                                    <div class="livreur-avatar">
                                        {{ strtoupper(substr($livreur->prenom, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="livreur-name">{{ $livreur->prenom }} {{ $livreur->nom }}</div>
                                        <div class="livreur-tel">📞 {{ $livreur->telephone }}</div>
                                    </div>
                                    <div style="margin-left:auto;">
                                        <span style="background:rgba(39,174,96,.1);color:var(--green);padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700;">
                                            ✅ Disponible
                                        </span>
                                    </div>
                                </label>
                                @empty
                                <div style="background:rgba(232,131,10,.08);border:1.5px solid rgba(232,131,10,.2);border-radius:10px;padding:16px;color:#a05c00;font-weight:600;">
                                    ⚠️ Aucun livreur disponible. Ajoutez des livreurs d'abord.
                                </div>
                                @endforelse
                            </div>

                            {{-- Date et adresse --}}
                            <div class="row mb-4">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label-fm">Date de livraison prévue <span style="color:var(--accent)">*</span></label>
                                    <input type="date" name="date_livcl" class="form-ctrl"
                                           value="{{ now()->addDay()->toDateString() }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label-fm">Adresse de livraison <span style="color:var(--accent)">*</span></label>
                                    <input type="text" name="adresse_livcl" class="form-ctrl"
                                           value="{{ $commande->adresse_livraison . ($commande->quartier ? ', '.$commande->quartier : '') }}"
                                           placeholder="Adresse complète" required>
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn-sm-fm btn-success-fm">
                                    <i class="fas fa-truck"></i> Confirmer et envoyer en livraison
                                </button>
                                <a href="{{ route('vendeur.commandes.show', $commande->id_cmd) }}"
                                   class="btn-sm-fm btn-outline-fm">
                                    <i class="fas fa-arrow-left"></i> Annuler
                                </a>
                            </div>
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
function selectLivreur(id) {
    document.querySelectorAll('.livreur-option').forEach(el => el.classList.remove('selected'));
    document.getElementById('livreur-' + id).classList.add('selected');
    document.querySelector(`input[value="${id}"]`).checked = true;
}
</script>
@endsection