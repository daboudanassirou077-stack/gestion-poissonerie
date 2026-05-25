{{-- resources/views/dashboard/gerant-categories.blade.php --}}

@extends('layouts.dashboard')
@section('title', 'Catégories — Dashboard Gérant')

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
    .dash-avatar { width:60px; height:60px; background:linear-gradient(135deg,#27ae60,#2ecc71); border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:24px; font-weight:800; color:#fff; border:3px solid rgba(255,255,255,.3); }

    /* Sidebar */
    .dash-menu { background:#fff; border-radius:16px; box-shadow:0 2px 12px rgba(0,0,0,.06); overflow:hidden; margin-bottom:24px; }
    .dash-menu-title { padding:14px 20px; font-size:11px; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:1.2px; border-bottom:1px solid #f0f0f0; background:var(--light-bg); }
    .dash-menu-item { display:flex; align-items:center; gap:12px; padding:13px 20px; border-bottom:1px solid #f5f5f5; text-decoration:none; color:var(--dark); font-size:14px; font-weight:600; transition:all .2s; }
    .dash-menu-item:last-child { border-bottom:none; }
    .dash-menu-item:hover { background:var(--light-bg); color:var(--primary); padding-left:26px; }
    .dash-menu-item.active { background:rgba(10,79,110,.06); color:var(--primary); border-left:3px solid var(--primary); }
    .dash-menu-item i { width:18px; text-align:center; color:var(--primary); }

    /* Card */
    .section-card { background:#fff; border-radius:16px; box-shadow:0 2px 12px rgba(0,0,0,.06); margin-bottom:24px; overflow:hidden; }
    .section-card-header { padding:16px 22px; border-bottom:2px solid var(--light-bg); display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px; }
    .section-card-header h5 { font-size:15px; font-weight:800; color:var(--dark); margin:0; }

    /* ── FORMULAIRE AJOUT ── */
    .form-add-cat { padding:24px 26px; background:var(--light-bg); border-bottom:2px solid #e9ecef; }
    .form-add-cat .form-row { display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:16px; }
    @media(max-width:640px){ .form-add-cat .form-row { grid-template-columns:1fr; } }
    .form-group-cat { display:flex; flex-direction:column; gap:6px; }
    .form-group-cat label { font-size:12px; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:.5px; }
    .form-group-cat input[type=text],
    .form-group-cat textarea {
        padding:10px 14px; border:1.5px solid #dee2e6; border-radius:9px;
        font-size:14px; color:var(--dark); outline:none; transition:border .2s;
        background:#fff; font-family:inherit; resize:none;
    }
    .form-group-cat input:focus,
    .form-group-cat textarea:focus { border-color:var(--primary); box-shadow:0 0 0 3px rgba(10,79,110,.08); }

    /* Zone upload photo */
    .upload-zone {
        border:2px dashed #ced4da; border-radius:12px; padding:28px 20px;
        text-align:center; cursor:pointer; transition:all .22s;
        background:#fff; position:relative; overflow:hidden;
    }
    .upload-zone:hover { border-color:var(--primary); background:rgba(10,79,110,.03); }
    .upload-zone.dragover { border-color:var(--secondary); background:rgba(232,131,10,.04); }
    .upload-zone input[type=file] { position:absolute; inset:0; opacity:0; cursor:pointer; width:100%; height:100%; }
    .upload-zone .uz-ico { font-size:2.2rem; margin-bottom:8px; }
    .upload-zone .uz-text { font-size:14px; font-weight:600; color:var(--dark); margin-bottom:4px; }
    .upload-zone .uz-hint { font-size:12px; color:var(--muted); }
    .upload-zone .uz-required { display:inline-block; background:rgba(192,57,43,.1); color:var(--accent); padding:2px 10px; border-radius:20px; font-size:11px; font-weight:700; margin-top:6px; }

    /* Prévisualisation photo */
    .preview-wrap { display:none; position:relative; margin-top:12px; }
    .preview-wrap img { width:100%; max-height:180px; object-fit:cover; border-radius:10px; border:2px solid var(--primary); }
    .preview-wrap .preview-remove {
        position:absolute; top:8px; right:8px; width:28px; height:28px;
        background:rgba(192,57,43,.9); color:#fff; border-radius:50%;
        display:flex; align-items:center; justify-content:center;
        cursor:pointer; font-size:13px; border:none; transition:all .2s;
    }
    .preview-wrap .preview-remove:hover { background:var(--accent); transform:scale(1.1); }
    .preview-name { font-size:12px; color:var(--muted); margin-top:6px; text-align:center; }

    /* Bouton submit */
    .btn-add-cat {
        width:100%; padding:12px 20px;
        background:linear-gradient(135deg,var(--primary),#1a7a9a);
        color:#fff; border:none; border-radius:10px;
        font-size:15px; font-weight:700; cursor:pointer;
        display:flex; align-items:center; justify-content:center; gap:9px;
        transition:all .2s; box-shadow:0 4px 14px rgba(10,79,110,.25);
    }
    .btn-add-cat:hover { transform:translateY(-2px); box-shadow:0 8px 22px rgba(10,79,110,.35); }

    /* ── GRILLE CATÉGORIES ── */
    .cat-grid { display:grid; grid-template-columns:repeat(auto-fill, minmax(200px, 1fr)); gap:16px; padding:22px; }
    .cat-item {
        background:#fff; border:1.5px solid #e9ecef;
        border-radius:14px; overflow:hidden;
        transition:all .25s; position:relative;
    }
    .cat-item:hover { transform:translateY(-4px); box-shadow:0 10px 28px rgba(0,0,0,.1); border-color:var(--primary); }
    .cat-item-img { width:100%; height:140px; object-fit:cover; display:block; }
    .cat-item-no-img { width:100%; height:140px; background:var(--light-bg); display:flex; align-items:center; justify-content:center; font-size:3rem; }
    .cat-item-body { padding:12px 14px; }
    .cat-item-name { font-size:14px; font-weight:800; color:var(--dark); margin-bottom:4px; }
    .cat-item-meta { font-size:12px; color:var(--muted); display:flex; align-items:center; gap:6px; }
    .cat-item-actions { display:flex; gap:6px; margin-top:10px; }
    .btn-xs { padding:5px 12px; border-radius:7px; font-size:12px; font-weight:700; border:none; cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; gap:4px; transition:all .15s; }
    .btn-xs-view   { background:rgba(39,174,96,.1);  color:var(--green); }
    .btn-xs-view:hover { background:rgba(39,174,96,.2); color:var(--green); }
    .btn-xs-del    { background:rgba(192,57,43,.1);  color:var(--accent); }
    .btn-xs-del:hover  { background:rgba(192,57,43,.2); }

    /* Badges */
    .prod-badge { display:inline-flex; align-items:center; gap:4px; padding:3px 9px; border-radius:20px; font-size:11px; font-weight:700; background:rgba(10,79,110,.08); color:var(--primary); }

    /* Alerts */
    .alert-fm { border-radius:10px; padding:12px 16px; font-size:14px; font-weight:600; margin-bottom:20px; display:flex; align-items:center; gap:8px; }
    .alert-success-fm { background:rgba(39,174,96,.1);  border:1.5px solid rgba(39,174,96,.25);  color:#1e7e44; }
    .alert-error-fm   { background:rgba(192,57,43,.08); border:1.5px solid rgba(192,57,43,.2);   color:var(--accent); }

    /* Info */
    .info-card { background:rgba(10,79,110,.06); border:1.5px solid rgba(10,79,110,.15); border-radius:12px; padding:13px 18px; margin-bottom:20px; font-size:13px; color:var(--primary); display:flex; gap:10px; align-items:flex-start; }

    /* Empty */
    .empty-state { text-align:center; padding:50px 20px; color:var(--muted); }
    .empty-state .ico { font-size:52px; display:block; margin-bottom:14px; }

    /* Erreurs */
    .field-error { font-size:12px; color:var(--accent); margin-top:3px; display:flex; align-items:center; gap:4px; }
</style>
@endsection

@section('content')

<div class="dash-header">
    <div class="container">
        <div class="d-flex align-items-center gap-3">
            <div class="dash-avatar">{{ strtoupper(substr(auth()->user()->prenom, 0, 1)) }}</div>
            <div>
                <h1>Gestion des Catégories 🗂️</h1>
                <p>{{ auth()->user()->prenom }} {{ auth()->user()->nom }} — {{ now()->format('d/m/Y') }}</p>
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

            {{-- ── SIDEBAR ── --}}
            <div class="col-lg-3 mb-4">
                <div class="dash-menu">
                    <div class="dash-menu-title">Gestion</div>
                    <a href="{{ route('gerant.dashboard') }}"        class="dash-menu-item"><i class="fas fa-tachometer-alt"></i> Tableau de bord</a>
                    <a href="{{ route('gerant.produits') }}"         class="dash-menu-item"><i class="fas fa-fish"></i> Produits</a>
                    <a href="{{ route('gerant.categories') }}"       class="dash-menu-item active"><i class="fas fa-tags"></i> Catégories <span class="badge bg-primary ms-auto">{{ $categories->count() }}</span></a>
                    <a href="{{ route('gerant.stocks') }}"           class="dash-menu-item"><i class="fas fa-boxes"></i> Stocks</a>
                    <a href="{{ route('gerant.commandes') }}"        class="dash-menu-item"><i class="fas fa-shopping-bag"></i> Commandes</a>
                    <a href="{{ route('gerant.approvisionnement') }}" class="dash-menu-item"><i class="fas fa-truck-loading"></i> Approvisionnement</a>
                    <div class="dash-menu-title" style="margin-top:4px">Rapports</div>
                    <a href="{{ route('gerant.etat-stock') }}"  class="dash-menu-item"><i class="fas fa-chart-bar"></i> État des stocks</a>
                    <a href="{{ route('gerant.etat-ventes') }}" class="dash-menu-item"><i class="fas fa-chart-line"></i> État des ventes</a>
                    <div class="dash-menu-title" style="margin-top:4px">Système</div>
                    <a href="{{ route('home') }}" target="_blank" class="dash-menu-item"><i class="fas fa-home"></i> Voir le site</a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dash-menu-item w-100 text-danger" style="background:none;border:none;text-align:left;">
                            <i class="fas fa-sign-out-alt"></i> Déconnexion
                        </button>
                    </form>
                </div>
            </div>

            {{-- ── CONTENU ── --}}
            <div class="col-lg-9">

                {{-- Info --}}
                <div class="info-card">
                    <i class="fas fa-info-circle" style="margin-top:2px;flex-shrink:0"></i>
                    <div>
                        Les catégories avec leurs photos apparaissent <strong>automatiquement</strong> sur la
                        <a href="{{ route('home') }}" target="_blank" style="color:var(--primary);font-weight:700;">page d'accueil →</a>
                        dans la section <em>"Toutes nos catégories"</em>.
                        Chaque catégorie affiche sa photo avec son nom en dessous.
                    </div>
                </div>

                {{-- ═══ FORMULAIRE AJOUT ═══ --}}
                <div class="section-card">
                    <div class="section-card-header">
                        <h5>➕ Ajouter une catégorie</h5>
                        <span style="font-size:13px;color:var(--muted)">{{ $categories->count() }} catégorie(s)</span>
                    </div>

                    <div class="form-add-cat">
                        <form action="{{ route('gerant.store-categorie') }}" method="POST"
                              enctype="multipart/form-data" id="form-cat">
                            @csrf

                            <div class="form-row">
                                {{-- Nom --}}
                                <div class="form-group-cat">
                                    <label for="libelle">Nom de la catégorie *</label>
                                    <input type="text" id="libelle" name="libelle"
                                           placeholder="Ex : Poissons frais, Volailles..."
                                           value="{{ old('libelle') }}" required/>
                                    @error('libelle')
                                    <div class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Description --}}
                                <div class="form-group-cat">
                                    <label for="description">Description (optionnel)</label>
                                    <textarea id="description" name="description" rows="2"
                                              placeholder="Ex : Silure, Tilapia, Capitaine...">{{ old('description') }}</textarea>
                                    @error('description')
                                    <div class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Photo obligatoire --}}
                            <div class="form-group-cat" style="margin-bottom:18px">
                                <label>Photo de la catégorie *</label>
                                <div class="upload-zone" id="upload-zone">
                                    <input type="file" name="image" id="image-input"
                                           accept="image/jpeg,image/png,image/jpg,image/webp"
                                           onchange="previewImage(this)" required/>
                                    <div id="upload-placeholder">
                                        <div class="uz-ico">🖼️</div>
                                        <div class="uz-text">Glissez votre photo ici ou cliquez pour choisir</div>
                                        <div class="uz-hint">JPG, PNG, WEBP · Max 3 MB</div>
                                        <span class="uz-required">⚠ Obligatoire</span>
                                    </div>
                                </div>
                                {{-- Prévisualisation --}}
                                <div class="preview-wrap" id="preview-wrap">
                                    <img id="preview-img" src="" alt="Aperçu"/>
                                    <button type="button" class="preview-remove" onclick="removePreview()" title="Supprimer">✕</button>
                                    <div class="preview-name" id="preview-name"></div>
                                </div>
                                @error('image')
                                <div class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn-add-cat">
                                <i class="fas fa-plus-circle"></i> Ajouter la catégorie avec sa photo
                            </button>
                        </form>
                    </div>
                </div>

                {{-- ═══ GRILLE CATÉGORIES ═══ --}}
                <div class="section-card">
                    <div class="section-card-header">
                        <h5>🗂️ Catégories existantes</h5>
                        <a href="{{ route('home') }}" target="_blank"
                           style="font-size:13px;color:var(--green);font-weight:700;text-decoration:none;">
                            <i class="fas fa-external-link-alt"></i> Voir sur le site
                        </a>
                    </div>

                    @if($categories->isEmpty())
                    <div class="empty-state">
                        <span class="ico">🗂️</span>
                        <h5 style="color:var(--dark);font-weight:700;">Aucune catégorie</h5>
                        <p>Ajoutez votre première catégorie avec une photo ci-dessus.</p>
                    </div>
                    @else
                    <div class="cat-grid">
                        @foreach($categories as $cat)
                        <div class="cat-item">
                            {{-- Photo --}}
                            @if($cat->image && file_exists(public_path('images/categories/' . $cat->image)))
                                <img class="cat-item-img"
                                     src="{{ asset('images/categories/' . $cat->image) }}"
                                     alt="{{ $cat->libelle }}"/>
                            @else
                                <div class="cat-item-no-img">🗂️</div>
                            @endif

                            <div class="cat-item-body">
                                <div class="cat-item-name">{{ $cat->libelle }}</div>
                                <div class="cat-item-meta">
                                    <span class="prod-badge">
                                        <i class="fas fa-fish"></i> {{ $cat->produits_count }} produit(s)
                                    </span>
                                </div>
                                @if($cat->description)
                                <div style="font-size:12px;color:var(--muted);margin-top:5px;">{{ $cat->description }}</div>
                                @endif

                                <div class="cat-item-actions">
                                    <a href="{{ route('shop', ['categorie' => $cat->slug]) }}"
                                       target="_blank" class="btn-xs btn-xs-view">
                                        <i class="fas fa-eye"></i> Voir
                                    </a>
                                    @if($cat->produits_count == 0)
                                    <form action="{{ route('gerant.delete-categorie', $cat) }}" method="POST"
                                          onsubmit="return confirm('Supprimer « {{ $cat->libelle }} » et sa photo ?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-xs btn-xs-del">
                                            <i class="fas fa-trash"></i> Suppr.
                                        </button>
                                    </form>
                                    @else
                                    <span class="btn-xs" style="background:#f0f0f0;color:var(--muted);cursor:not-allowed;"
                                          title="{{ $cat->produits_count }} produit(s) lié(s)">
                                        <i class="fas fa-lock"></i> Protégée
                                    </span>
                                    @endif
                                </div>
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

@endsection

@section('scripts')
<script>
// ── Prévisualisation photo ──
function previewImage(input) {
    const wrap  = document.getElementById('preview-wrap');
    const img   = document.getElementById('preview-img');
    const name  = document.getElementById('preview-name');
    const zone  = document.getElementById('upload-zone');
    const placeholder = document.getElementById('upload-placeholder');

    if (input.files && input.files[0]) {
        const file   = input.files[0];
        const reader = new FileReader();
        reader.onload = e => {
            img.src = e.target.result;
            wrap.style.display   = 'block';
            placeholder.style.display = 'none';
            name.textContent     = file.name + ' (' + (file.size / 1024).toFixed(0) + ' KB)';
            zone.style.borderColor = '#27ae60';
        };
        reader.readAsDataURL(file);
    }
}

// ── Supprimer la prévisualisation ──
function removePreview() {
    document.getElementById('image-input').value     = '';
    document.getElementById('preview-wrap').style.display = 'none';
    document.getElementById('upload-placeholder').style.display = 'block';
    document.getElementById('upload-zone').style.borderColor    = '#ced4da';
}

// ── Drag & Drop ──
const zone = document.getElementById('upload-zone');
zone.addEventListener('dragover',  e => { e.preventDefault(); zone.classList.add('dragover'); });
zone.addEventListener('dragleave', () => zone.classList.remove('dragover'));
zone.addEventListener('drop', e => {
    e.preventDefault();
    zone.classList.remove('dragover');
    const input = document.getElementById('image-input');
    input.files = e.dataTransfer.files;
    previewImage(input);
});
</script>
@endsection