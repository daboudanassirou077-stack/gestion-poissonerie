@extends('layouts.app')

@section('title', 'Finaliser ma commande - FreshMarket')

@section('styles')
<style>
    :root {
        --primary:      #0a4f6e;
        --primary-dark: #073a52;
        --secondary:    #e8830a;
        --accent:       #c0392b;
        --green:        #27ae60;
        --light-bg:     #f4f9fc;
        --dark:         #1a1a2e;
        --muted:        #6c757d;
    }

    /* ===== HERO ===== */
    .page-hero {
        background: linear-gradient(135deg, var(--primary-dark), var(--primary));
        padding: 36px 0;
        position: relative;
        overflow: hidden;
    }
    .page-hero::before { content:'💳'; position:absolute; font-size:160px; opacity:.04; right:10px; top:-20px; }
    .page-hero h1 { font-size: 30px; color:#fff; font-weight:800; margin-bottom:8px; }
    .breadcrumb   { background:transparent; padding:0; margin:0; }
    .breadcrumb-item a { color:rgba(255,255,255,.85); text-decoration:none; }
    .breadcrumb-item.active { color:var(--secondary); }
    .breadcrumb-item + .breadcrumb-item::before { color:rgba(255,255,255,.4); }

    /* ===== ÉTAPES ===== */
    .steps-bar { background:#fff; box-shadow:0 2px 10px rgba(0,0,0,.06); }
    .steps-inner { display:flex; max-width:700px; margin:0 auto; }
    .step-item {
        flex:1; display:flex; align-items:center; gap:10px;
        padding:16px 20px; position:relative;
        border-right:1px solid #f0f0f0;
    }
    .step-item:last-child { border-right:none; }
    .step-num {
        width:30px; height:30px; border-radius:50%;
        background:#e0e0e0; display:flex; align-items:center; justify-content:center;
        font-size:13px; font-weight:800; color:#999; flex-shrink:0;
    }
    .step-item.done   .step-num { background:var(--green); color:#fff; }
    .step-item.active .step-num { background:var(--primary); color:#fff; }
    .step-text { font-size:13px; font-weight:700; color:#bbb; }
    .step-item.done   .step-text { color:var(--green); }
    .step-item.active .step-text { color:var(--primary); }

    /* ===== WRAPPER ===== */
    .checkout-wrapper { padding:40px 0 70px; background:var(--light-bg); }

    /* ===== CARTES ===== */
    .checkout-card {
        background:#fff; border-radius:16px; padding:28px 26px;
        box-shadow:0 3px 15px rgba(0,0,0,.07); margin-bottom:20px;
    }
    .card-header-fm {
        font-size:17px; font-weight:800; color:var(--dark);
        margin-bottom:22px; display:flex; align-items:center; gap:10px;
        padding-bottom:14px; border-bottom:2px solid var(--light-bg);
    }
    .card-header-fm span { font-size:22px; }

    /* Champs */
    .form-label-fm {
        display:block; font-size:11px; font-weight:700;
        color:var(--primary); text-transform:uppercase; letter-spacing:.8px;
        margin-bottom:7px;
    }
    .form-label-fm em { color:var(--accent); font-style:normal; }
    .form-ctrl {
        width:100%; padding:12px 15px;
        border:1.5px solid #e5e5e5; border-radius:10px;
        font-size:14px; color:var(--dark); background:#fff;
        transition:border-color .3s, box-shadow .3s;
    }
    .form-ctrl:focus { outline:none; border-color:var(--primary); box-shadow:0 0 0 3px rgba(10,79,110,.08); }
    .form-ctrl.is-invalid { border-color:var(--accent); }
    .field-error { font-size:12px; color:var(--accent); margin-top:4px; }

    /* ===== MOBILE MONEY ===== */
    .momo-grid { display:grid; grid-template-columns:1fr 1fr; gap:14px; margin-bottom:20px; }
    .momo-card {
        border:2px solid #e0e0e0; border-radius:14px;
        padding:18px 14px; text-align:center; cursor:pointer;
        transition:all .25s; position:relative;
    }
    .momo-card:hover { border-color:#aaa; }
    .momo-card.selected { border-color:var(--primary); background:rgba(10,79,110,.04); }
    .momo-card input[type="radio"] { position:absolute; opacity:0; pointer-events:none; }
    .momo-logo { font-size:36px; margin-bottom:8px; }
    .momo-name { font-size:14px; font-weight:800; color:var(--dark); margin-bottom:2px; }
    .momo-desc { font-size:12px; color:var(--muted); }
    .momo-check {
        position:absolute; top:10px; right:10px;
        width:22px; height:22px; border-radius:50%;
        background:var(--primary); color:#fff;
        display:none; align-items:center; justify-content:center;
        font-size:11px;
    }
    .momo-card.selected .momo-check { display:flex; }

    /* MTN */
    .momo-card.mtn.selected { border-color:#f5a623; background:#fffbf0; }
    .momo-card.mtn.selected .momo-check { background:#f5a623; }

    /* Moov */
    .momo-card.moov.selected { border-color:#0055a5; background:#f0f5ff; }
    .momo-card.moov.selected .momo-check { background:#0055a5; }

    /* Espèces */
    .momo-card.especes.selected { border-color:var(--green); background:rgba(39,174,96,.04); }
    .momo-card.especes.selected .momo-check { background:var(--green); }

    /* Infos MTN/Moov */
    .momo-info-box {
        background:var(--light-bg); border-radius:10px; padding:14px 16px;
        font-size:13px; margin-bottom:16px; display:none;
    }
    .momo-info-box.show { display:block; }
    .momo-info-box strong { color:var(--primary); }

    /* ===== RÉSUMÉ ===== */
    .summary-card {
        background:#fff; border-radius:16px; padding:24px;
        box-shadow:0 3px 15px rgba(0,0,0,.07); position:sticky; top:80px;
    }
    .sum-title {
        font-size:11px; font-weight:700; color:var(--primary);
        text-transform:uppercase; letter-spacing:1.2px;
        margin-bottom:16px; padding-bottom:10px; border-bottom:2px solid var(--light-bg);
    }
    .sum-item { display:flex; gap:10px; align-items:flex-start; padding:10px 0; border-bottom:1px solid #f5f5f5; }
    .sum-item:last-of-type { border-bottom:none; }
    .sum-item-img {
        width:42px; height:42px; border-radius:8px;
        background:var(--light-bg); display:flex; align-items:center;
        justify-content:center; font-size:18px; flex-shrink:0;
    }
    .sum-item-info { flex:1; }
    .sum-item-name { font-size:13px; font-weight:700; color:var(--dark); }
    .sum-item-qty  { font-size:12px; color:var(--muted); margin-top:2px; }
    .sum-item-price { font-size:14px; font-weight:800; color:var(--primary); flex-shrink:0; }
    .sum-row {
        display:flex; justify-content:space-between; align-items:center;
        padding:8px 0; border-bottom:1px solid #f5f5f5; font-size:13px;
    }
    .sum-row .l { color:var(--muted); }
    .sum-row .v { font-weight:700; color:var(--dark); }
    .sum-row .v.free { color:var(--green); }
    .sum-total {
        display:flex; justify-content:space-between; align-items:center;
        padding:16px 0 0; border-top:2px solid var(--light-bg); margin-top:8px;
    }
    .sum-total .tl { font-size:15px; font-weight:700; color:var(--dark); }
    .sum-total .tv { font-size:22px; font-weight:800; color:var(--primary); }

    /* Bouton payer */
    .btn-pay {
        display:flex; align-items:center; justify-content:center; gap:10px;
        width:100%; padding:16px; border:none; border-radius:12px;
        font-size:16px; font-weight:800; cursor:pointer;
        background:linear-gradient(135deg, var(--green) 0%, #2ecc71 100%);
        color:#fff; transition:all .3s; margin-top:16px;
    }
    .btn-pay:hover { transform:translateY(-2px); box-shadow:0 10px 30px rgba(39,174,96,.4); }

    .delivery-estimate {
        background:rgba(10,79,110,.06); border-radius:10px;
        padding:12px 14px; font-size:13px; margin-top:14px; line-height:1.6;
        color:var(--dark);
    }
    .delivery-estimate strong { color:var(--primary); }

    /* ===== RESPONSIVE ===== */
    @media(max-width:991px) {
        .summary-card { position:static; margin-top:24px; }
        .steps-inner  { overflow-x:auto; }
    }
    @media(max-width:576px) {
        .momo-grid { grid-template-columns:1fr; }
        .step-text { font-size:11px; }
        .page-hero h1 { font-size:22px; }
    }
</style>
@endsection

@section('content')

{{-- ===== HERO ===== --}}
<div class="page-hero">
    <div class="container" style="position:relative;z-index:2;">
        <h1>Finaliser ma commande</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                <li class="breadcrumb-item"><a href="{{ route('cart.index') }}">Panier</a></li>
                <li class="breadcrumb-item active">Commande</li>
            </ol>
        </nav>
    </div>
</div>

{{-- ===== ÉTAPES ===== --}}
<div class="steps-bar">
    <div class="container">
        <div class="steps-inner">
            <div class="step-item done">
                <div class="step-num"><i class="fas fa-check" style="font-size:11px;"></i></div>
                <span class="step-text">Panier</span>
            </div>
            <div class="step-item active">
                <div class="step-num">2</div>
                <span class="step-text">Livraison</span>
            </div>
            <div class="step-item active">
                <div class="step-num">3</div>
                <span class="step-text">Paiement</span>
            </div>
            <div class="step-item">
                <div class="step-num">4</div>
                <span class="step-text">Confirmation</span>
            </div>
        </div>
    </div>
</div>

{{-- ===== CONTENU ===== --}}
<section class="checkout-wrapper">
    <div class="container">
        <form action="{{ route('cart.order') }}" method="POST" id="checkoutForm">
            @csrf
            <div class="row">

                {{-- ===== GAUCHE : Formulaires ===== --}}
                <div class="col-lg-8">

                    {{-- Infos livraison --}}
                    <div class="checkout-card">
                        <p class="card-header-fm"><span>📍</span> Informations de livraison</p>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label-fm" for="prenom">Prénom <em>*</em></label>
                                <input type="text" id="prenom" name="prenom"
                                    class="form-ctrl @error('prenom') is-invalid @enderror"
                                    placeholder="Votre prénom"
                                    value="{{ old('prenom') }}" required>
                                @error('prenom')<p class="field-error">{{ $message }}</p>@enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label-fm" for="nom">Nom <em>*</em></label>
                                <input type="text" id="nom" name="nom"
                                    class="form-ctrl @error('nom') is-invalid @enderror"
                                    placeholder="Votre nom"
                                    value="{{ old('nom') }}" required>
                                @error('nom')<p class="field-error">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label-fm" for="telephone">Téléphone <em>*</em></label>
                                <input type="tel" id="telephone" name="telephone"
                                    class="form-ctrl @error('telephone') is-invalid @enderror"
                                    placeholder="+229 00 000 000"
                                    value="{{ old('telephone') }}" required>
                                @error('telephone')<p class="field-error">{{ $message }}</p>@enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label-fm" for="quartier">Quartier <em>*</em></label>
                                <input type="text" id="quartier" name="quartier"
                                    class="form-ctrl @error('quartier') is-invalid @enderror"
                                    placeholder="Ex: Akpakpa, Fidjrossè..."
                                    value="{{ old('quartier') }}" required>
                                @error('quartier')<p class="field-error">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label-fm" for="adresse">Adresse complète <em>*</em></label>
                            <input type="text" id="adresse" name="adresse"
                                class="form-ctrl @error('adresse') is-invalid @enderror"
                                placeholder="Rue, numéro de maison, bâtiment..."
                                value="{{ old('adresse') }}" required>
                            @error('adresse')<p class="field-error">{{ $message }}</p>@enderror
                        </div>

                        <div class="mb-0">
                            <label class="form-label-fm" for="instructions">Instructions pour le livreur</label>
                            <input type="text" id="instructions" name="instructions"
                                class="form-ctrl"
                                placeholder="Ex: Près de la pharmacie, 2e maison à gauche..."
                                value="{{ old('instructions') }}">
                        </div>
                    </div>

                    {{-- Mode de paiement --}}
                    <div class="checkout-card">
                        <p class="card-header-fm"><span>�</span> Mode de paiement</p>

                        <div style="background:rgba(39,174,96,.07);border:1.5px solid rgba(39,174,96,.2);border-radius:10px;padding:12px 14px;font-size:13px;color:#1e7e44;margin-bottom:16px;">
                            <i class="fas fa-mobile-alt"></i>
                            <strong>Paiement sécurisé avec Kkiapay</strong> — Acceptez MTN MoMo, Moov Money et cartes bancaires.
                        </div>

                        <p style="font-size:14px;color:var(--muted);line-height:1.6;">
                            Après avoir rempli vos informations de livraison, cliquez sur "Payer maintenant" pour procéder au paiement sécurisé via Kkiapay.
                        </p>
                    </div>

                    {{-- Bouton retour --}}
                    {{-- Bouton retour --}}
                            <a href="{{ route('cart') }}" style="color:var(--primary);font-weight:600;font-size:14px;text-decoration:none;">
                                <i class="fas fa-arrow-left"></i> Retour au panier
                            </a>

                </div>

                {{-- ===== DROITE : Résumé ===== --}}
                <div class="col-lg-4">
                    <div class="summary-card">
                        <p class="sum-title">Votre commande</p>

                        {{-- Articles --}}
                        @foreach($cart as $id => $item)
                        <div class="sum-item">
                            <div class="sum-item-img">🐟</div>
                            <div class="sum-item-info">
                                <p class="sum-item-name">{{ $item['nom'] }}</p>
                                <p class="sum-item-qty">{{ $item['quantite'] }} {{ $item['unite'] ?? 'kg' }} × {{ number_format($item['prix'], 0, ',', ' ') }} FCFA</p>
                            </div>
                            <div class="sum-item-price">
                                {{ number_format($item['prix'] * $item['quantite'], 0, ',', ' ') }} FCFA
                            </div>
                        </div>
                        @endforeach

                        {{-- Totaux --}}
                        <div class="sum-row" style="margin-top:8px;">
                            <span class="l">Sous-total</span>
                            <span class="v">{{ number_format($total, 0, ',', ' ') }} FCFA</span>
                        </div>
                        <div class="sum-row">
                            <span class="l">Livraison</span>
                            <span class="v free"><i class="fas fa-check-circle"></i> Gratuite</span>
                        </div>

                        <div class="sum-total">
                            <span class="tl">Total</span>
                            <span class="tv">{{ number_format($total, 0, ',', ' ') }} FCFA</span>
                        </div>

                        {{-- Bouton payer --}}
                        {{-- Bouton payer --}}
                                @guest
                                    {{-- Visiteur : rediriger vers login --}}
                                    <a href="{{ route('login') }}" class="btn-pay" style="text-decoration:none;">
                                        <i class="fas fa-sign-in-alt"></i>
                                        Connectez-vous pour payer
                                    </a>
                                @else
                                    {{-- Connecté : bouton payer --}}
                                    <button type="submit" class="btn-pay" id="payBtn">
                                        <i class="fas fa-mobile-alt"></i>
                                        Payer {{ number_format($total, 0, ',', ' ') }} FCFA
                                    </button>
                                @endguest

                        {{-- Estimation livraison --}}
                        <div class="delivery-estimate">
                            ⏰ <strong>Livraison estimée :</strong><br>
                            Avant 12h → livraison <strong>aujourd'hui</strong><br>
                            Après 12h → livraison <strong>demain matin</strong>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>
</section>

@endsection

@section('scripts')
<script>
    // Anti double-clic sur le bouton payer
    document.getElementById('checkoutForm').addEventListener('submit', function () {
        const btn = document.getElementById('payBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Redirection vers le paiement...';
    });
</script>
@endsection
