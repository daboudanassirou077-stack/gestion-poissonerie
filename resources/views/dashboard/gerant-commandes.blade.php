@extends('layouts.dashboard')

@section('title', 'Suivi Commandes - Gérant FreshMarket')

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

    .admin-table { width:100%; border-collapse:collapse; }
    .admin-table th { background:var(--light-bg); padding:11px 16px; font-size:11px; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:.8px; text-align:left; }
    .admin-table td { padding:12px 16px; border-bottom:1px solid #f5f5f5; font-size:14px; color:var(--dark); vertical-align:middle; }
    .admin-table tr:last-child td { border-bottom:none; }
    .admin-table tr:hover td { background:#fafafa; }

    .status-badge { display:inline-flex; align-items:center; gap:5px; padding:4px 10px; border-radius:20px; font-size:11px; font-weight:700; }
    .status-en_attente    { background:rgba(232,131,10,.12); color:#a05c00; }
    .status-confirmee     { background:rgba(10,79,110,.1);   color:var(--primary); }
    .status-en_preparation{ background:rgba(155,89,182,.1);  color:#6c3483; }
    .status-en_livraison  { background:rgba(39,174,96,.1);   color:var(--green); }
    .status-livree        { background:rgba(39,174,96,.15);  color:#1a7a3a; }
    .status-annulee       { background:rgba(192,57,43,.1);   color:var(--accent); }

    /* Modal mise à jour statut */
    .modal-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,.5); z-index:1000; align-items:center; justify-content:center; }
    .modal-overlay.show { display:flex; }
    .modal-box { background:#fff; border-radius:16px; padding:28px; width:100%; max-width:450px; box-shadow:0 20px 60px rgba(0,0,0,.2); }
    .modal-box h4 { font-size:18px; font-weight:800; color:var(--dark); margin-bottom:20px; }
    .form-label-fm { display:block; font-size:11px; font-weight:700; color:var(--primary); text-transform:uppercase; letter-spacing:.8px; margin-bottom:7px; }
    .form-ctrl { width:100%; padding:11px 14px; border:1.5px solid #e5e5e5; border-radius:10px; font-size:14px; color:var(--dark); background:#fff; }
    .form-ctrl:focus { outline:none; border-color:var(--primary); }

    .btn-sm-fm { padding:6px 14px; border-radius:8px; font-size:12px; font-weight:700; border:none; cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; gap:5px; transition:all .2s; }
    .btn-primary-fm { background:var(--primary); color:#fff; }
    .btn-primary-fm:hover { background:#083d56; color:#fff; }
    .btn-success-fm { background:var(--green); color:#fff; }

    /* Filtre */
    .filter-bar { background:#fff; border-radius:12px; padding:14px 18px; box-shadow:0 2px 10px rgba(0,0,0,.05); margin-bottom:20px; display:flex; gap:10px; flex-wrap:wrap; align-items:center; }
    .filter-bar select, .filter-bar input { padding:8px 12px; border:1.5px solid #e0e0e0; border-radius:8px; font-size:13px; color:#444; }
    .filter-bar select:focus, .filter-bar input:focus { outline:none; border-color:var(--primary); }

    .alert-fm { border-radius:10px; padding:12px 16px; font-size:14px; font-weight:600; margin-bottom:20px; display:flex; align-items:center; gap:8px; }
    .alert-success-fm { background:rgba(39,174,96,.1); border:1.5px solid rgba(39,174,96,.25); color:#1e7e44; }
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
                <h1>Suivi des Commandes 📦</h1>
                <p>{{ $commandes->total() }} commande(s) au total</p>
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
                    <a href="{{ route('gerant.commandes') }}" class="dash-menu-item active">
                        <i class="fas fa-shopping-bag"></i> Commandes
                    </a>
                    <a href="{{ route('gerant.approvisionnement') }}" class="dash-menu-item">
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

                {{-- Filtre --}}
                <div class="filter-bar">
                    <form action="{{ route('gerant.commandes') }}" method="GET" class="d-flex gap-2 flex-wrap w-100">
                        <select name="statut" style="flex:1;min-width:150px;">
                            <option value="">Tous les statuts</option>
                            <option value="en_attente"    {{ request('statut') === 'en_attente'    ? 'selected' : '' }}>En attente</option>
                            <option value="confirmee"     {{ request('statut') === 'confirmee'     ? 'selected' : '' }}>Confirmée</option>
                            <option value="en_preparation"{{ request('statut') === 'en_preparation'? 'selected' : '' }}>En préparation</option>
                            <option value="en_livraison"  {{ request('statut') === 'en_livraison'  ? 'selected' : '' }}>En livraison</option>
                            <option value="livree"        {{ request('statut') === 'livree'        ? 'selected' : '' }}>Livrée</option>
                            <option value="annulee"       {{ request('statut') === 'annulee'       ? 'selected' : '' }}>Annulée</option>
                        </select>
                        <select name="paiement" style="flex:1;min-width:150px;">
                            <option value="">Tout paiement</option>
                            <option value="en_attente" {{ request('paiement') === 'en_attente' ? 'selected' : '' }}>En attente</option>
                            <option value="paye"       {{ request('paiement') === 'paye'       ? 'selected' : '' }}>Payé</option>
                            <option value="echoue"     {{ request('paiement') === 'echoue'     ? 'selected' : '' }}>Échoué</option>
                        </select>
                        <button type="submit" class="btn-sm-fm btn-primary-fm" style="padding:8px 16px;">
                            <i class="fas fa-filter"></i> Filtrer
                        </button>
                        <a href="{{ route('gerant.commandes') }}" class="btn-sm-fm" style="background:#f0f0f0;color:#666;padding:8px 16px;">
                            Réinitialiser
                        </a>
                    </form>
                </div>

                {{-- Table commandes --}}
                <div class="section-card">
                    <div class="section-card-header">
                        <h5>📦 Liste des commandes</h5>
                    </div>
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Référence</th>
                                <th>Client</th>
                                <th>Date</th>
                                <th>Montant</th>
                                <th>Statut</th>
                                <th>Paiement</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($commandes as $commande)
                            <tr>
                                <td><strong>{{ $commande->reference }}</strong></td>
                                <td>
                                    <div style="font-weight:600;">
                                        {{ $commande->client->prenom_client ?? '—' }}
                                        {{ $commande->client->nom_client ?? '' }}
                                    </div>
                                    <div style="font-size:12px;color:var(--muted);">
                                        {{ $commande->client->telephone ?? '' }}
                                    </div>
                                </td>
                                <td>{{ $commande->created_at->format('d/m/Y') }}</td>
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
                                <td>
                                    <button class="btn-sm-fm btn-primary-fm"
                                        onclick="openModal(
                                            {{ $commande->id_cmd }},
                                            '{{ $commande->reference }}',
                                            '{{ $commande->statut_cmd }}',
                                            '{{ $commande->statut_paiement }}'
                                        )">
                                        <i class="fas fa-edit"></i> Modifier
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" style="text-align:center;color:var(--muted);padding:40px;">
                                    Aucune commande trouvée
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                    @if($commandes->hasPages())
                    <div style="padding:16px 18px;border-top:1px solid #f0f0f0;">
                        {{ $commandes->appends(request()->query())->links() }}
                    </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>

{{-- Modal mise à jour statut --}}
<div class="modal-overlay" id="modalOverlay">
    <div class="modal-box">
        <h4>📦 Mettre à jour la commande</h4>
        <p id="modalRef" style="font-size:13px;color:var(--muted);margin-bottom:20px;"></p>

        <form id="modalForm" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label-fm">Statut commande</label>
                <select name="statut_cmd" id="modalStatut" class="form-ctrl">
                    <option value="en_attente">En attente</option>
                    <option value="confirmee">Confirmée</option>
                    <option value="en_preparation">En préparation</option>
                    <option value="en_livraison">En livraison</option>
                    <option value="livree">Livrée</option>
                    <option value="annulee">Annulée</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="form-label-fm">Statut paiement</label>
                <select name="statut_paiement" id="modalPaiement" class="form-ctrl">
                    {{-- <option value="en_attente">En attente</option> --}}
                    <option value="paye">Payé</option>
                    {{-- <option value="echoue">Échoué</option> --}}
                </select>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn-sm-fm btn-success-fm" style="padding:10px 20px;">
                    <i class="fas fa-save"></i> Enregistrer
                </button>
                <button type="button" class="btn-sm-fm" style="background:#f0f0f0;color:#666;padding:10px 20px;" onclick="closeModal()">
                    Annuler
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function openModal(id, ref, statut, paiement) {
        document.getElementById('modalRef').textContent = 'Référence : ' + ref;
        document.getElementById('modalForm').action = '/gerant/commandes/' + id + '/update';
        document.getElementById('modalStatut').value   = statut;
        document.getElementById('modalPaiement').value = paiement;
        document.getElementById('modalOverlay').classList.add('show');
    }

    function closeModal() {
        document.getElementById('modalOverlay').classList.remove('show');
    }

    // Fermer en cliquant dehors
    document.getElementById('modalOverlay').addEventListener('click', function(e) {
        if (e.target === this) closeModal();
    });
</script>
@endsection