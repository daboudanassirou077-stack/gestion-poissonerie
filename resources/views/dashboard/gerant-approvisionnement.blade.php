@extends('layouts.dashboard')

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
    .btn-danger-fm  { background:var(--accent); color:#fff; }

    .alert-fm { border-radius:10px; padding:12px 16px; font-size:14px; font-weight:600; margin-bottom:20px; display:flex; align-items:center; gap:8px; }
    .alert-success-fm { background:rgba(39,174,96,.1); border:1.5px solid rgba(39,174,96,.25); color:#1e7e44; }
    .alert-error-fm   { background:rgba(192,57,43,.08); border:1.5px solid rgba(192,57,43,.2); color:var(--accent); }

    /* Lignes produits dans le bon */
    .produit-ligne { display:flex; align-items:center; gap:10px; background:var(--light-bg); border-radius:10px; padding:10px 14px; margin-bottom:8px; }
    .produit-ligne select, .produit-ligne input { flex:1; padding:8px 12px; border:1.5px solid #e5e5e5; border-radius:8px; font-size:13px; color:var(--dark); background:#fff; }
    .produit-ligne .btn-remove-ligne { width:32px; height:32px; background:#fff; border:1.5px solid rgba(192,57,43,.3); border-radius:8px; color:var(--accent); cursor:pointer; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
    .produit-ligne .btn-remove-ligne:hover { background:var(--accent); color:#fff; }

    /* Produits tags dans le tableau */
    .produit-tag { display:inline-flex; align-items:center; gap:4px; background:rgba(10,79,110,.08); color:var(--primary); padding:2px 8px; border-radius:20px; font-size:11px; font-weight:600; margin:2px; }
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
                    <a href="{{ route('gerant.dashboard') }}" class="dash-menu-item"><i class="fas fa-tachometer-alt"></i> Tableau de bord</a>
                    <a href="{{ route('gerant.produits') }}" class="dash-menu-item"><i class="fas fa-fish"></i> Produits</a>
                    <a href="{{ route('gerant.stocks') }}" class="dash-menu-item"><i class="fas fa-boxes"></i> Stocks</a>
                    <a href="{{ route('gerant.commandes') }}" class="dash-menu-item"><i class="fas fa-shopping-bag"></i> Commandes</a>
                    <a href="{{ route('gerant.approvisionnement') }}" class="dash-menu-item active"><i class="fas fa-truck-loading"></i> Approvisionnement</a>
                    <div class="dash-menu-title">Rapports</div>
                    <a href="{{ route('gerant.etat-stock') }}" class="dash-menu-item"><i class="fas fa-chart-bar"></i> État des stocks</a>
                    <a href="{{ route('gerant.etat-ventes') }}" class="dash-menu-item"><i class="fas fa-chart-line"></i> État des ventes</a>
                    <div class="dash-menu-title">Système</div>
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
                                            <i class="fas fa-phone" style="color:var(--primary);"></i> {{ $frs->telephone }}
                                            @if($frs->email) &nbsp;·&nbsp; <i class="fas fa-envelope" style="color:var(--primary);"></i> {{ $frs->email }} @endif
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

                {{-- ===== FORMULAIRE BON DE COMMANDE ===== --}}
                <div class="section-card">
                    <div class="section-card-header">
                        <h5>📋 Nouveau bon de commande fournisseur</h5>
                        <button class="btn-sm-fm btn-primary-fm" onclick="toggleBonForm()">
                            <i class="fas fa-plus"></i> Créer un bon
                        </button>
                    </div>
                    <div id="bonForm" style="display:none;">
                        <div class="section-card-body">
                            <form action="{{ route('gerant.store-bon') }}" method="POST" id="formBon">
                                @csrf

                                <div class="row mb-3">
                                    {{-- Fournisseur --}}
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label-fm">Fournisseur <em>*</em></label>
                                        <select name="id_frs" class="form-ctrl" required>
                                            <option value="">Choisir un fournisseur</option>
                                            @foreach($fournisseurs as $frs)
                                            <option value="{{ $frs->id_frs }}">{{ $frs->nom_frs }} {{ $frs->prenom_frs }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    {{-- Date --}}
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label-fm">Date <em>*</em></label>
                                        <input type="date" name="date_bon" class="form-ctrl"
                                               value="{{ now()->toDateString() }}" required>
                                    </div>
                                    {{-- Statut --}}
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label-fm">Statut</label>
                                        <select name="statut" class="form-ctrl">
                                            <option value="brouillon">📝 Brouillon</option>
                                            <option value="envoye">📤 Envoyé</option>
                                            <option value="recu">✅ Reçu</option>
                                            <option value="annule">❌ Annulé</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- Lignes produits --}}
                                <label class="form-label-fm">Produits commandés <em>*</em></label>
                                <div id="lignesProduits">
                                    <div class="produit-ligne">
                                        <select name="produits[0][id_prod]" required style="flex:2;">
                                            <option value="">Choisir un produit</option>
                                            @foreach($produits as $prod)
                                            <option value="{{ $prod->id_prod }}">{{ $prod->libelle_prod }}</option>
                                            @endforeach
                                        </select>
                                        <input type="number" name="produits[0][quantite]"
                                               placeholder="Qté (kg)" min="0.01" step="0.01" required>
                                        <input type="number" name="produits[0][prix]"
                                               placeholder="Prix/kg (FCFA)" min="0" step="0.01" required>
                                        <button type="button" class="btn-remove-ligne" onclick="removeLigne(this)" title="Supprimer">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>

                                <button type="button" class="btn-sm-fm btn-warning-fm mt-2 mb-3" onclick="ajouterLigne()">
                                    <i class="fas fa-plus"></i> Ajouter un produit
                                </button>

                                {{-- Montant total calculé --}}
                                <div style="background:rgba(10,79,110,.06);border-radius:10px;padding:12px 16px;margin-bottom:16px;display:flex;justify-content:space-between;align-items:center;">
                                    <span style="font-size:14px;font-weight:700;color:var(--dark);">Montant total calculé</span>
                                    <span style="font-size:18px;font-weight:800;color:var(--primary);" id="montantTotal">0 FCFA</span>
                                </div>
                                <input type="hidden" name="montant_total" id="inputMontantTotal" value="0">

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn-sm-fm btn-success-fm" style="padding:10px 20px;">
                                        <i class="fas fa-save"></i> Enregistrer le bon
                                    </button>
                                    <button type="button" class="btn-sm-fm"
                                            style="background:#f0f0f0;color:#666;padding:10px 20px;"
                                            onclick="toggleBonForm()">
                                        Annuler
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- ===== TABLEAU BONS DE COMMANDE ===== --}}
                <div class="section-card">
                    <div class="section-card-header">
                        <h5>📋 Bons de commande fournisseurs</h5>
                        <span style="font-size:13px;color:var(--muted);">{{ $bons->total() }} bon(s)</span>
                    </div>
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>N° Bon</th>
                                <th>Fournisseur</th>
                                <th>Date</th>
                                <th>Produits envoyés</th>
                                <th>Montant</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bons as $bon)
                            <tr>
                                <td><strong>#{{ $bon->id_bon }}</strong></td>
                                <td>{{ $bon->fournisseur->nom_frs ?? '—' }}</td>
                                <td style="font-size:13px;color:var(--muted);">
                                    {{ \Carbon\Carbon::parse($bon->date_bon)->format('d/m/Y') }}
                                </td>
                                <td>
                                    {{-- Afficher les produits du bon --}}
                                    @if($bon->produits && $bon->produits->count() > 0)
                                        @foreach($bon->produits as $prod)
                                        <span class="produit-tag">
                                            🐟 {{ $prod->libelle_prod }}
                                            <span style="opacity:.7;">{{ $prod->pivot->quantite_cmd }}kg</span>
                                        </span>
                                        @endforeach
                                    @else
                                        <span style="color:var(--muted);font-size:12px;">—</span>
                                    @endif
                                </td>
                                <td><strong>{{ number_format($bon->montant_total, 0, ',', ' ') }} FCFA</strong></td>
                                <td>
                                    <span class="status-badge status-{{ $bon->statut }}">
                                        @if($bon->statut === 'brouillon') 📝 Brouillon
                                        @elseif($bon->statut === 'envoye') 📤 Envoyé
                                        @elseif($bon->statut === 'recu') ✅ Reçu
                                        @else ❌ Annulé
                                        @endif
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" style="text-align:center;color:var(--muted);padding:40px;">
                                    <span style="font-size:36px;display:block;margin-bottom:10px;">📋</span>
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

@section('scripts')
<script>
// Toggle formulaire bon
function toggleBonForm() {
    const form = document.getElementById('bonForm');
    form.style.display = form.style.display === 'none' ? 'block' : 'none';
}

// Compteur de lignes
let ligneIndex = 1;

// Ajouter une ligne produit
function ajouterLigne() {
    const container = document.getElementById('lignesProduits');
    const div = document.createElement('div');
    div.className = 'produit-ligne';
    div.innerHTML = `
        <select name="produits[${ligneIndex}][id_prod]" required style="flex:2;">
            <option value="">Choisir un produit</option>
            @foreach($produits as $prod)
            <option value="{{ $prod->id_prod }}">{{ $prod->libelle_prod }}</option>
            @endforeach
        </select>
        <input type="number" name="produits[${ligneIndex}][quantite]"
               placeholder="Qté (kg)" min="0.01" step="0.01" required oninput="recalcTotal()">
        <input type="number" name="produits[${ligneIndex}][prix]"
               placeholder="Prix/kg (FCFA)" min="0" step="0.01" required oninput="recalcTotal()">
        <button type="button" class="btn-remove-ligne" onclick="removeLigne(this)">
            <i class="fas fa-times"></i>
        </button>
    `;
    container.appendChild(div);
    ligneIndex++;
    // Attacher les événements
    div.querySelectorAll('input[type="number"]').forEach(i => i.addEventListener('input', recalcTotal));
}

// Supprimer une ligne
function removeLigne(btn) {
    const ligne = btn.closest('.produit-ligne');
    const container = document.getElementById('lignesProduits');
    if (container.children.length > 1) {
        ligne.remove();
        recalcTotal();
    }
}

// Recalculer le montant total
function recalcTotal() {
    let total = 0;
    document.querySelectorAll('.produit-ligne').forEach(ligne => {
        const qty   = parseFloat(ligne.querySelector('input[name$="[quantite]"]')?.value) || 0;
        const prix  = parseFloat(ligne.querySelector('input[name$="[prix]"]')?.value)     || 0;
        total += qty * prix;
    });
    document.getElementById('montantTotal').textContent    = total.toLocaleString('fr-FR') + ' FCFA';
    document.getElementById('inputMontantTotal').value     = total;
}

// Attacher événements à la première ligne
document.querySelectorAll('.produit-ligne input[type="number"]').forEach(i => {
    i.addEventListener('input', recalcTotal);
});
</script>
@endsection