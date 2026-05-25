@extends('layouts.dashboard')

@section('title', 'Gestion Produits - Gérant FreshMarket')

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

    /* Info calibre */
    .calibre-info { background:rgba(10,79,110,.06); border:1.5px solid rgba(10,79,110,.15); border-radius:10px; padding:10px 14px; font-size:13px; color:var(--primary); font-weight:600; margin-top:8px; display:none; }
    .calibre-info.show { display:block; }

    .admin-table { width:100%; border-collapse:collapse; }
    .admin-table th { background:var(--light-bg); padding:11px 16px; font-size:11px; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:.8px; text-align:left; }
    .admin-table td { padding:12px 16px; border-bottom:1px solid #f5f5f5; font-size:14px; color:var(--dark); vertical-align:middle; }
    .admin-table tr:last-child td { border-bottom:none; }
    .admin-table tr:hover td { background:#fafafa; }

    .prod-img { width:50px; height:50px; border-radius:10px; object-fit:cover; background:var(--light-bg); }
    .prod-img-placeholder { width:50px; height:50px; border-radius:10px; background:var(--light-bg); display:flex; align-items:center; justify-content:center; font-size:22px; }

    .stock-ok      { background:rgba(39,174,96,.1);   color:var(--green);  padding:3px 10px; border-radius:20px; font-size:11px; font-weight:700; }
    .stock-faible  { background:rgba(232,131,10,.12); color:#a05c00;       padding:3px 10px; border-radius:20px; font-size:11px; font-weight:700; }
    .stock-epuise  { background:rgba(192,57,43,.1);   color:var(--accent); padding:3px 10px; border-radius:20px; font-size:11px; font-weight:700; }
    .actif-badge   { background:rgba(39,174,96,.1);  color:var(--green);  padding:3px 10px; border-radius:20px; font-size:11px; font-weight:700; }
    .inactif-badge { background:rgba(0,0,0,.08);     color:#666;          padding:3px 10px; border-radius:20px; font-size:11px; font-weight:700; }

    .btn-sm-fm { padding:5px 12px; border-radius:7px; font-size:12px; font-weight:700; border:none; cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; gap:4px; transition:all .2s; }
    .btn-primary-fm { background:var(--primary); color:#fff; }
    .btn-primary-fm:hover { background:#083d56; color:#fff; }
    .btn-warning-fm { background:var(--secondary); color:#fff; }
    .btn-warning-fm:hover { background:#c97009; color:#fff; }
    .btn-danger-fm  { background:var(--accent); color:#fff; }
    .btn-danger-fm:hover { background:#a93226; color:#fff; }
    .btn-success-fm { background:var(--green); color:#fff; }

    .alert-fm { border-radius:10px; padding:12px 16px; font-size:14px; font-weight:600; margin-bottom:20px; display:flex; align-items:center; gap:8px; }
    .alert-success-fm { background:rgba(39,174,96,.1);  border:1.5px solid rgba(39,174,96,.25);  color:#1e7e44; }
    .alert-error-fm   { background:rgba(192,57,43,.08); border:1.5px solid rgba(192,57,43,.2);   color:var(--accent); }

    /* ===== PAGINATION MODERNE ===== */

.pagination-wrap{
    display:flex;
    justify-content:center;
    padding:20px;
}

.pagination{
    display:flex;
    gap:8px;
    list-style:none;
    padding:0;
    margin:0;
}

.page-item{
    list-style:none;
}

.page-link{
    width:38px;
    height:38px;

    display:flex;
    align-items:center;
    justify-content:center;

    border-radius:10px;
    border:1px solid #e2e8f0;

    background:#fff;
    color:#1a1a2e;

    font-size:14px;
    font-weight:600;

    text-decoration:none;

    transition:.25s ease;
    box-shadow:0 2px 8px rgba(0,0,0,.05);
}

.page-link:hover{
    background:#0a4f6e;
    color:#fff;
    border-color:#0a4f6e;
    transform:translateY(-2px);
}

.page-item.active .page-link{
    background:#0a4f6e;
    color:#fff;
    border-color:#0a4f6e;
}

.page-item.disabled .page-link{
    opacity:.4;
    pointer-events:none;
}
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
                <h1>Gestion des Produits 🐟</h1>
                <p>{{ $produits->total() }} produit(s) enregistré(s)</p>
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

        {{-- Passer les catégories poisson en JS --}}
        @php
            $categoriesPoisson = $categories->filter(fn($c) =>
                str_contains(strtolower($c->libelle), 'poisson')
            )->pluck('id_categorie')->toArray();
        @endphp

        <div class="row">

            {{-- Sidebar --}}
            <div class="col-lg-3 mb-4">
                <div class="dash-menu">
                    <div class="dash-menu-title">Gestion</div>
                    <a href="{{ route('gerant.dashboard') }}" class="dash-menu-item">
                        <i class="fas fa-tachometer-alt"></i> Tableau de bord
                    </a>
                    <a href="{{ route('gerant.produits') }}" class="dash-menu-item active">
                        <i class="fas fa-fish"></i> Produits
                    </a>
                    <a href="{{ route('gerant.stocks') }}" class="dash-menu-item">
                        <i class="fas fa-boxes"></i> Stocks
                    </a>
                    <a href="{{ route('gerant.commandes') }}" class="dash-menu-item">
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

                {{-- Formulaire ajout produit --}}
                <div class="section-card">
                    <div class="section-card-header">
                        <h5>➕ Ajouter un produit</h5>
                        <button class="btn-sm-fm btn-primary-fm" onclick="toggleForm()">
                            <i class="fas fa-plus"></i> Nouveau produit
                        </button>
                    </div>
                    <div id="addForm" style="display:none;">
                        <div class="section-card-body">
                            <form action="{{ route('gerant.store-produit') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @if($errors->any())
                                    <div style="background:rgba(192,57,43,.08);border:1.5px solid rgba(192,57,43,.2);color:#c0392b;border-radius:10px;padding:12px 16px;font-size:14px;font-weight:600;margin-bottom:16px;">
                                        <i class="fas fa-exclamation-circle"></i>
                                        <ul style="margin:8px 0 0 16px;">
                                            @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    @endif


                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label-fm">Nom du produit <em>*</em></label>
                                        <input type="text" name="libelle_prod" class="form-ctrl"
                                               placeholder="Ex: Silure frais" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label-fm">Prix (FCFA) <em>*</em></label>
                                        <input type="number" name="prix" class="form-ctrl"
                                               placeholder="1500" min="0" required>
                                    </div>
                                </div>

                                <div class="row">
                                    {{-- Catégorie --}}
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label-fm">Catégorie <em>*</em></label>
                                        <select name="id_categorie" id="categorieSelect"
                                                class="form-ctrl" required
                                                onchange="toggleCalibre(this)">
                                            <option value="">Choisir une catégorie</option>
                                            @foreach($categories as $cat)
                                            <option value="{{ $cat->id_categorie }}"
                                                    data-libelle="{{ strtolower($cat->libelle) }}">
                                                {{ $cat->libelle }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- Calibre — visible seulement pour les poissons --}}
                                    <div class="col-md-6 mb-3" id="calibreField" style="display:none;">
                                        <label class="form-label-fm">Calibre <em>*</em></label>
                                        <select name="id_calibre" id="calibreSelect" class="form-ctrl">
                                            <option value="">Choisir un calibre</option>
                                            @foreach($calibres as $cal)
                                            <option value="{{ $cal->id_calibre }}">
                                                @if($cal->taille === 'grande')
                                                    🐟 Grande (≥ 1,5 kg)
                                                @elseif($cal->taille === 'moyen')
                                                    🐟 Moyen (0,5 — 1,5 kg)
                                                @elseif($cal->taille === 'petit')
                                                    🐟 Petit / Silivie (≤ 0,5 kg)
                                                @else
                                                    {{ ucfirst($cal->taille ?? $cal->type_produit) }}
                                                @endif
                                            </option>
                                            @endforeach
                                        </select>
                                        <div class="calibre-info show">
                                            🐟 Le calibre s'applique uniquement aux poissons.
                                            Les clients pourront choisir leur calibre à l'achat.
                                        </div>
                                    </div>

                                    {{-- Message pour les non-poissons --}}
                                    <div class="col-md-6 mb-3" id="noCalibреField" style="display:none;">
                                        <label class="form-label-fm">Calibre</label>
                                        <div style="background:var(--light-bg);border-radius:10px;padding:12px 14px;font-size:13px;color:var(--muted);font-weight:600;">
                                            ⚖️ Vendu au kg — pas de calibre pour cette catégorie
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label-fm">Quantité en stock <em>*</em></label>
                                        <input type="number" name="quantite_stock" class="form-ctrl"
                                               placeholder="50" min="0" step="0.01" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label-fm">Seuil d'alerte <em>*</em></label>
                                        <input type="number" name="seuil_alerte" class="form-ctrl"
                                               placeholder="5" min="0" step="0.01" required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label-fm">Description</label>
                                    <textarea name="description" class="form-ctrl" rows="2"
                                              placeholder="Description du produit..."></textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label-fm">Image du produit</label>
                                    <input type="file" name="image" class="form-ctrl" accept="image/*">
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn-sm-fm btn-success-fm" style="padding:10px 20px;">
                                        <i class="fas fa-save"></i> Enregistrer
                                    </button>
                                    <button type="button" class="btn-sm-fm"
                                            style="background:#f0f0f0;color:#666;padding:10px 20px;"
                                            onclick="toggleForm()">
                                        Annuler
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Liste produits --}}
                <div class="section-card">
                    <div class="section-card-header">
                        <h5>🐟 Liste des produits</h5>
                    </div>
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Produit</th>
                                <th>Catégorie</th>
                                <th>Calibre</th>
                                <th>Prix</th>
                                <th>Stock</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($produits as $produit)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        @if($produit->image)
                                            <img src="{{ asset('images/produits/' . $produit->image) }}"
                                                 class="prod-img"
                                                 alt="{{ $produit->libelle_prod }}"
                                                 onerror="this.style.display='none'">
                                        @else
                                            <div class="prod-img-placeholder">
                                                @if($produit->calibre && $produit->calibre->type_produit === 'poisson')
                                                    🐟
                                                @elseif(str_contains(strtolower($produit->categorie->libelle ?? ''), 'viande'))
                                                    🥩
                                                @elseif(str_contains(strtolower($produit->categorie->libelle ?? ''), 'volaille'))
                                                    🍗
                                                @elseif(str_contains(strtolower($produit->categorie->libelle ?? ''), 'escargot'))
                                                    🐌
                                                @else
                                                    📦
                                                @endif
                                            </div>
                                        @endif
                                        <div>
                                            <div style="font-weight:700;">{{ $produit->libelle_prod }}</div>
                                            @if($produit->description)
                                            <div style="font-size:11px;color:var(--muted);">
                                                {{ Str::limit($produit->description, 40) }}
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $produit->categorie->libelle ?? '—' }}</td>
                                <td>
                                    @if($produit->calibre && $produit->calibre->type_produit === 'poisson')
                                        @if($produit->calibre->taille === 'grande')
                                            <span style="background:rgba(10,79,110,.1);color:var(--primary);padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700;">🐟 Grande</span>
                                        @elseif($produit->calibre->taille === 'moyen')
                                            <span style="background:rgba(39,174,96,.1);color:var(--green);padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700;">🐟 Moyen</span>
                                        @elseif($produit->calibre->taille === 'petit')
                                            <span style="background:rgba(232,131,10,.12);color:#a05c00;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700;">🐟 Petit</span>
                                        @endif
                                    @else
                                        <span style="font-size:12px;color:var(--muted);">⚖️ Au kg</span>
                                    @endif
                                </td>
                                <td><strong>{{ number_format($produit->prix, 0, ',', ' ') }} FCFA</strong></td>
                                <td>
                                    @if($produit->stock)
                                        @if($produit->stock->quantite_stock == 0)
                                            <span class="stock-epuise">🔴 Épuisé</span>
                                        @elseif($produit->stock->quantite_stock <= $produit->stock->seuil_alerte)
                                            <span class="stock-faible">🟡 {{ $produit->stock->quantite_stock }}</span>
                                        @else
                                            <span class="stock-ok">🟢 {{ $produit->stock->quantite_stock }}</span>
                                        @endif
                                    @else
                                        <span style="color:var(--muted);">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($produit->actif)
                                        <span class="actif-badge">Actif</span>
                                    @else
                                        <span class="inactif-badge">Inactif</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('gerant.edit-produit', $produit->id_prod) }}"
                                           class="btn-sm-fm btn-warning-fm" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('gerant.delete-produit', $produit->id_prod) }}"
                                              method="POST" style="display:inline;"
                                              onsubmit="return confirm('Supprimer ce produit ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-sm-fm btn-danger-fm" title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" style="text-align:center;color:var(--muted);padding:40px;">
                                    Aucun produit enregistré
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                   @if ($produits->hasPages())
<div class="pagination-wrap">

    <ul class="pagination">

        {{-- Précédent --}}
        @if ($produits->onFirstPage())
            <li class="page-item disabled">
                <span class="page-link">‹</span>
            </li>
        @else
            <li class="page-item">
                <a class="page-link" href="{{ $produits->previousPageUrl() }}">
                    ‹
                </a>
            </li>
        @endif

        {{-- Numéros --}}
        @foreach ($produits->getUrlRange(1, $produits->lastPage()) as $page => $url)

            @if ($page == $produits->currentPage())
                <li class="page-item active">
                    <span class="page-link">{{ $page }}</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $url }}">
                        {{ $page }}
                    </a>
                </li>
            @endif

        @endforeach

        {{-- Suivant --}}
        @if ($produits->hasMorePages())
            <li class="page-item">
                <a class="page-link" href="{{ $produits->nextPageUrl() }}">
                    ›
                </a>
            </li>
        @else
            <li class="page-item disabled">
                <span class="page-link">›</span>
            </li>
        @endif

    </ul>

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
    // ===== TOGGLE FORMULAIRE =====
    function toggleForm() {
        const form = document.getElementById('addForm');
        form.style.display = form.style.display === 'none' ? 'block' : 'none';
    }

    // ===== TOGGLE CALIBRE SELON CATÉGORIE =====
    function toggleCalibre(select) {
        const libelle       = select.options[select.selectedIndex]?.dataset.libelle || '';
        const isPoisson = libelle === 'poisson';
        const calibreField  = document.getElementById('calibreField');
        const noCalibreField= document.getElementById('noCalibреField');
        const calibreSelect = document.getElementById('calibreSelect');

        if (isPoisson) {
            // Afficher le select calibre
            calibreField.style.display   = 'block';
            noCalibreField.style.display = 'none';
            calibreSelect.required       = true;
        } else if (select.value !== '') {
            // Autre catégorie choisie
            calibreField.style.display   = 'none';
            noCalibreField.style.display = 'block';
            calibreSelect.required       = false;
            calibreSelect.value          = '';
        } else {
            // Aucune catégorie choisie
            calibreField.style.display   = 'none';
            noCalibreField.style.display = 'none';
            calibreSelect.required       = false;
            calibreSelect.value          = '';
        }
    }
</script>
@endsection