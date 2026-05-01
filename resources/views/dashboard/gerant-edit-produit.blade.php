@extends('layouts.app')

@section('title', 'Modifier Produit - Gérant FreshMarket')

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

    .form-card { background:#fff; border-radius:16px; padding:30px; box-shadow:0 2px 12px rgba(0,0,0,.06); }
    .form-card h4 { font-size:18px; font-weight:800; color:var(--dark); margin-bottom:6px; }
    .form-card p  { font-size:14px; color:var(--muted); margin-bottom:24px; }

    .form-label-fm { display:block; font-size:11px; font-weight:700; color:var(--primary); text-transform:uppercase; letter-spacing:.8px; margin-bottom:7px; }
    .form-label-fm em { color:var(--accent); font-style:normal; }
    .form-ctrl { width:100%; padding:12px 15px; border:1.5px solid #e5e5e5; border-radius:10px; font-size:14px; color:var(--dark); background:#fff; transition:border-color .3s; }
    .form-ctrl:focus { outline:none; border-color:var(--primary); box-shadow:0 0 0 3px rgba(10,79,110,.08); }
    .form-ctrl.is-invalid { border-color:var(--accent); }
    .field-error { font-size:12px; color:var(--accent); margin-top:4px; }

    /* Image preview */
    .img-preview-wrap {
        border:2px dashed #e0e0e0;
        border-radius:12px;
        padding:20px;
        text-align:center;
        cursor:pointer;
        transition:border-color .3s;
        position:relative;
    }
    .img-preview-wrap:hover { border-color:var(--primary); }
    .img-preview-wrap img { max-height:180px; border-radius:10px; object-fit:cover; }
    .img-placeholder { font-size:48px; margin-bottom:10px; display:block; }
    .img-preview-wrap input { position:absolute; inset:0; opacity:0; cursor:pointer; }

    /* Toggle actif */
    .toggle-wrap { display:flex; align-items:center; gap:12px; }
    .toggle-switch { position:relative; width:50px; height:26px; }
    .toggle-switch input { opacity:0; width:0; height:0; }
    .toggle-slider { position:absolute; inset:0; background:#ccc; border-radius:26px; cursor:pointer; transition:.3s; }
    .toggle-slider:before { content:''; position:absolute; width:20px; height:20px; left:3px; bottom:3px; background:#fff; border-radius:50%; transition:.3s; }
    input:checked + .toggle-slider { background:var(--green); }
    input:checked + .toggle-slider:before { transform:translateX(24px); }

    .btn-submit { background:var(--primary); color:#fff; border:none; padding:13px 28px; border-radius:10px; font-size:15px; font-weight:700; cursor:pointer; transition:all .3s; display:inline-flex; align-items:center; gap:8px; }
    .btn-submit:hover { background:var(--secondary); transform:translateY(-2px); }

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
                <h1>Modifier le produit ✏️</h1>
                <p>{{ $produit->libelle_prod }}</p>
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

            {{-- Formulaire --}}
            <div class="col-lg-9">
                <div class="form-card">
                    <h4>Modifier le produit</h4>
                    <p>Modifiez les informations du produit ci-dessous.</p>

                    <form action="{{ route('gerant.update-produit', $produit->id_prod) }}"
                          method="POST"
                          enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            {{-- Infos principales --}}
                            <div class="col-lg-8">

                                <div class="row">
                                    <div class="col-md-8 mb-3">
                                        <label class="form-label-fm">Nom du produit <em>*</em></label>
                                        <input type="text" name="libelle_prod"
                                            class="form-ctrl @error('libelle_prod') is-invalid @enderror"
                                            value="{{ old('libelle_prod', $produit->libelle_prod) }}"
                                            required>
                                        @error('libelle_prod')<p class="field-error">{{ $message }}</p>@enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label-fm">Prix (FCFA) <em>*</em></label>
                                        <input type="number" name="prix"
                                            class="form-ctrl @error('prix') is-invalid @enderror"
                                            value="{{ old('prix', $produit->prix) }}"
                                            min="0" required>
                                        @error('prix')<p class="field-error">{{ $message }}</p>@enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label-fm">Catégorie <em>*</em></label>
                                        <select name="id_categorie"
                                            class="form-ctrl @error('id_categorie') is-invalid @enderror"
                                            required>
                                            <option value="">Choisir...</option>
                                            @foreach($categories as $cat)
                                            <option value="{{ $cat->id_categorie }}"
                                                {{ old('id_categorie', $produit->id_categorie) == $cat->id_categorie ? 'selected' : '' }}>
                                                {{ $cat->libelle }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('id_categorie')<p class="field-error">{{ $message }}</p>@enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label-fm">Calibre <em>*</em></label>
                                        <select name="id_calibre"
                                            class="form-ctrl @error('id_calibre') is-invalid @enderror"
                                            required>
                                            <option value="">Choisir...</option>
                                            @foreach($calibres as $cal)
                                            <option value="{{ $cal->id_calibre }}"
                                                {{ old('id_calibre', $produit->id_calibre) == $cal->id_calibre ? 'selected' : '' }}>
                                                {{ $cal->type_produit }} — {{ $cal->unite_vente }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('id_calibre')<p class="field-error">{{ $message }}</p>@enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label-fm">Description</label>
                                    <textarea name="description" class="form-ctrl" rows="3">{{ old('description', $produit->description) }}</textarea>
                                </div>

                                {{-- Stock --}}
                                @if($produit->stock)
                                <div style="background:var(--light-bg);border-radius:12px;padding:16px;margin-bottom:20px;">
                                    <p style="font-size:13px;font-weight:700;color:var(--primary);margin-bottom:14px;">
                                        <i class="fas fa-boxes"></i> Stock actuel
                                    </p>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label-fm">Quantité en stock</label>
                                            <input type="number" name="quantite_stock_edit"
                                                class="form-ctrl"
                                                value="{{ $produit->stock->quantite_stock }}"
                                                min="0" step="0.01">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label-fm">Seuil d'alerte</label>
                                            <input type="number" name="seuil_alerte_edit"
                                                class="form-ctrl"
                                                value="{{ $produit->stock->seuil_alerte }}"
                                                min="0" step="0.01">
                                        </div>
                                    </div>
                                </div>
                                @endif

                                {{-- Statut actif --}}
                                <div class="mb-4">
                                    <label class="form-label-fm">Statut du produit</label>
                                    <div class="toggle-wrap">
                                        <label class="toggle-switch">
                                            <input type="checkbox" name="actif" {{ $produit->actif ? 'checked' : '' }}>
                                            <span class="toggle-slider"></span>
                                        </label>
                                        <span style="font-size:14px;font-weight:600;color:var(--dark);">
                                            {{ $produit->actif ? 'Produit actif' : 'Produit inactif' }}
                                        </span>
                                    </div>
                                </div>

                            </div>

                            {{-- Image --}}
                            <div class="col-lg-4">
                                <label class="form-label-fm">Image du produit</label>
                                <div class="img-preview-wrap" onclick="document.getElementById('imageInput').click()">
                                    @if($produit->image)
                                        <img src="{{ asset('images/produits/' . $produit->image) }}"
                                             id="imgPreview"
                                             alt="{{ $produit->libelle_prod }}"
                                             style="width:100%;">
                                    @else
                                        <span class="img-placeholder" id="imgPlaceholder">🐟</span>
                                        <p style="font-size:13px;color:var(--muted);">Cliquer pour choisir une image</p>
                                        <img id="imgPreview" style="display:none;width:100%;" alt="">
                                    @endif
                                    <input type="file" id="imageInput" name="image"
                                           accept="image/*"
                                           onchange="previewImage(this)"
                                           style="position:absolute;inset:0;opacity:0;cursor:pointer;">
                                </div>
                                <p style="font-size:12px;color:var(--muted);margin-top:8px;text-align:center;">
                                    JPG, PNG, WEBP — Max 2MB
                                </p>
                            </div>

                        </div>

                        <div class="d-flex gap-3 align-items-center mt-2">
                            <button type="submit" class="btn-submit">
                                <i class="fas fa-save"></i> Enregistrer les modifications
                            </button>
                            <a href="{{ route('gerant.produits') }}"
                               style="color:var(--muted);font-size:14px;text-decoration:none;">
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
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('imgPreview');
                const placeholder = document.getElementById('imgPlaceholder');
                preview.src = e.target.result;
                preview.style.display = 'block';
                if (placeholder) placeholder.style.display = 'none';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection