@extends('layouts.app')

@section('title', 'Mon Panier - FreshMarket')

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
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 60%, #1a7a9a 100%);
        padding: 40px 0;
        position: relative;
        overflow: hidden;
    }
    .page-hero::before { content:'🛒'; position:absolute; font-size:180px; opacity:.04; right:-10px; top:-20px; transform:rotate(-10deg); }
    .page-hero h1      { font-size: 34px; color: #fff; font-weight: 800; margin-bottom: 8px; }
    .breadcrumb        { background: transparent; padding: 0; margin: 0; }
    .breadcrumb-item a { color: rgba(255,255,255,.85); text-decoration: none; }
    .breadcrumb-item.active { color: var(--secondary); }
    .breadcrumb-item + .breadcrumb-item::before { color: rgba(255,255,255,.4); }

    /* ===== WRAPPER ===== */
    .cart-wrapper { padding: 40px 0 70px; background: var(--light-bg); min-height: 60vh; }

    /* ===== ITEMS ===== */
    .cart-item {
        background: #fff;
        border-radius: 16px;
        padding: 18px 20px;
        display: flex;
        align-items: center;
        gap: 18px;
        margin-bottom: 14px;
        box-shadow: 0 2px 12px rgba(0,0,0,.06);
        transition: all .3s;
    }
    .cart-item:hover { box-shadow: 0 6px 25px rgba(0,0,0,.1); transform: translateY(-2px); }

    .item-img {
        width: 80px;
        height: 80px;
        border-radius: 12px;
        object-fit: cover;
        flex-shrink: 0;
        background: var(--light-bg);
    }
    .item-img-placeholder {
        width: 80px;
        height: 80px;
        border-radius: 12px;
        background: var(--light-bg);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        flex-shrink: 0;
    }

    .item-info    { flex: 1; min-width: 0; }
    .item-cat     { font-size: 11px; color: var(--secondary); font-weight: 700; text-transform: uppercase; letter-spacing: .8px; margin-bottom: 4px; }
    .item-name    { font-size: 16px; font-weight: 700; color: var(--dark); margin-bottom: 6px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .item-unit    { font-size: 13px; color: var(--muted); }

    .item-controls { display: flex; flex-direction: column; align-items: flex-end; gap: 12px; flex-shrink: 0; }
    .item-total   { font-size: 18px; font-weight: 800; color: var(--primary); }

    /* Quantité */
    .qty-ctrl { display: flex; align-items: center; gap: 0; border: 1.5px solid #e0e0e0; border-radius: 10px; overflow: hidden; }
    .qty-btn {
        width: 34px; height: 34px;
        background: #fff;
        border: none;
        cursor: pointer;
        font-size: 18px;
        font-weight: 700;
        color: var(--primary);
        display: flex; align-items: center; justify-content: center;
        transition: background .2s;
    }
    .qty-btn:hover { background: var(--light-bg); }
    .qty-input {
        width: 44px;
        text-align: center;
        border: none;
        border-left: 1.5px solid #e0e0e0;
        border-right: 1.5px solid #e0e0e0;
        font-size: 15px;
        font-weight: 700;
        color: var(--dark);
        padding: 0;
        height: 34px;
        outline: none;
        -moz-appearance: textfield;
    }
    .qty-input::-webkit-outer-spin-button,
    .qty-input::-webkit-inner-spin-button { -webkit-appearance: none; }

    /* Bouton supprimer */
    .btn-remove {
        width: 34px; height: 34px;
        background: #fff5f5;
        border: 1.5px solid rgba(192,57,43,.2);
        border-radius: 8px;
        color: var(--accent);
        cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        font-size: 14px;
        transition: all .2s;
        text-decoration: none;
    }
    .btn-remove:hover { background: var(--accent); color: #fff; }

    /* ===== PANIER VIDE ===== */
    .empty-cart {
        text-align: center;
        padding: 70px 20px;
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,.06);
    }
    .empty-cart .empty-icon { font-size: 72px; margin-bottom: 20px; display: block; }
    .empty-cart h3 { font-size: 24px; font-weight: 800; color: var(--dark); margin-bottom: 10px; }
    .empty-cart p  { color: var(--muted); font-size: 15px; margin-bottom: 25px; }
    .btn-shop {
        background: var(--primary);
        color: #fff;
        padding: 13px 30px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 15px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all .3s;
    }
    .btn-shop:hover { background: var(--secondary); color: #fff; transform: translateY(-2px); }

    /* ===== RÉSUMÉ ===== */
    .summary-card {
        background: #fff;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 3px 15px rgba(0,0,0,.07);
        position: sticky;
        top: 80px;
    }
    .summary-title {
        font-size: 11px;
        font-weight: 700;
        color: var(--primary);
        text-transform: uppercase;
        letter-spacing: 1.2px;
        margin-bottom: 18px;
        padding-bottom: 12px;
        border-bottom: 2px solid var(--light-bg);
    }
    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid #f5f5f5;
        font-size: 14px;
    }
    .summary-row:last-of-type { border-bottom: none; }
    .summary-row .s-label { color: var(--muted); }
    .summary-row .s-val   { font-weight: 700; color: var(--dark); }
    .summary-row .s-val.free   { color: var(--green); }
    .summary-row .s-val.promo  { color: var(--accent); }

    .summary-total {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px 0 0;
        margin-top: 8px;
        border-top: 2px solid var(--light-bg);
    }
    .summary-total .t-label { font-size: 16px; font-weight: 700; color: var(--dark); }
    .summary-total .t-val   { font-size: 24px; font-weight: 800; color: var(--primary); }

    /* Code promo */
    .promo-form { display: flex; gap: 8px; margin: 16px 0; }
    .promo-form input {
        flex: 1;
        padding: 10px 14px;
        border: 1.5px solid #e0e0e0;
        border-radius: 8px;
        font-size: 13px;
        transition: border-color .3s;
    }
    .promo-form input:focus { outline: none; border-color: var(--primary); }
    .promo-form button {
        background: var(--light-bg);
        border: 1.5px solid #e0e0e0;
        padding: 10px 16px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 700;
        color: var(--primary);
        cursor: pointer;
        transition: all .3s;
        white-space: nowrap;
    }
    .promo-form button:hover { background: var(--primary); color: #fff; border-color: var(--primary); }

    /* Bouton commander */
    .btn-checkout {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        width: 100%;
        padding: 15px;
        background: var(--primary);
        color: #fff;
        border: none;
        border-radius: 12px;
        font-size: 16px;
        font-weight: 800;
        cursor: pointer;
        text-decoration: none;
        transition: all .3s;
        margin-top: 4px;
    }
    .btn-checkout:hover {
        background: var(--secondary);
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(232,131,10,.35);
    }

    .btn-clear {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        width: 100%;
        padding: 10px;
        background: transparent;
        border: 1.5px solid #e0e0e0;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        color: var(--muted);
        cursor: pointer;
        text-decoration: none;
        transition: all .3s;
        margin-top: 10px;
    }
    .btn-clear:hover { border-color: var(--accent); color: var(--accent); }

    .secure-note {
        text-align: center;
        font-size: 12px;
        color: var(--muted);
        margin-top: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
    }

    /* Livraison info */
    .delivery-info {
        background: rgba(39,174,96,.07);
        border: 1.5px solid rgba(39,174,96,.2);
        border-radius: 10px;
        padding: 12px 14px;
        font-size: 13px;
        color: #1e7e44;
        font-weight: 600;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* Alertes */
    .alert-success-fm {
        background: rgba(39,174,96,.1);
        border: 1.5px solid rgba(39,174,96,.25);
        color: #1e7e44;
        border-radius: 10px;
        padding: 12px 16px;
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 18px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .alert-error-fm {
        background: rgba(192,57,43,.08);
        border: 1.5px solid rgba(192,57,43,.2);
        color: var(--accent);
        border-radius: 10px;
        padding: 12px 16px;
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 18px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 991px) {
        .summary-card { position: static; margin-top: 24px; }
        .item-name    { white-space: normal; }
    }
    @media (max-width: 576px) {
        .cart-item    { flex-wrap: wrap; }
        .item-controls{ flex-direction: row; align-items: center; width: 100%; justify-content: space-between; }
        .page-hero h1 { font-size: 26px; }
    }
</style>
@endsection

@section('content')

{{-- ===== HERO ===== --}}
<div class="page-hero">
    <div class="container" style="position:relative;z-index:2;">
        <h1>Mon Panier</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                <li class="breadcrumb-item"><a href="{{ route('shop') }}">Boutique</a></li>
                <li class="breadcrumb-item active">Panier</li>
            </ol>
        </nav>
    </div>
</div>

{{-- ===== CONTENU ===== --}}
<section class="cart-wrapper">
    <div class="container">

        {{-- Alertes --}}
        @if(session('success'))
        <div class="alert-success-fm">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="alert-error-fm">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
        @endif

        @if(empty($cart))
        {{-- ===== PANIER VIDE ===== --}}
        <div class="empty-cart">
            <span class="empty-icon">🛒</span>
            <h3>Votre panier est vide</h3>
            <p>Découvrez nos produits frais et ajoutez vos articles préférés.</p>
            <a href="{{ route('shop') }}" class="btn-shop">
                <i class="fas fa-fish"></i> Aller à la boutique
            </a>
        </div>

        @else
        {{-- ===== PANIER REMPLI ===== --}}
        <div class="row">

            {{-- Articles --}}
            <div class="col-lg-8 mb-4 mb-lg-0">

                <div class="delivery-info">
                    <i class="fas fa-truck"></i>
                    Livraison gratuite à Cotonou — Commandez avant 12h pour recevoir aujourd'hui !
                </div>

                {{-- En-tête --}}
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 style="font-weight:700;color:var(--dark);margin:0;">
                        {{ collect($cart)->sum('quantite') }} article(s) dans votre panier
                    </h5>
                    <a href="{{ route('cart.clear') }}"
                       class="btn-clear"
                       style="width:auto;padding:7px 16px;"
                       onclick="return confirm('Vider le panier ?')"
                    >
                        <i class="fas fa-trash-alt"></i> Tout vider
                    </a>
                </div>

                {{-- Liste des articles --}}
                @foreach($cart as $id => $item)
                <div class="cart-item" id="item-{{ $id }}">

                    {{-- Image --}}
                    @if(!empty($item['image']))
                    <img
                        src="{{ asset('images/produits/' . $item['image']) }}"
                        alt="{{ $item['nom'] }}"
                        class="item-img"
                        onerror="this.style.display='none';this.nextElementSibling.style.display='flex';"
                    >
                    @endif
                    <div class="item-img-placeholder" @if(!empty($item['image'])) style="display:none;" @endif>
                        🐟
                    </div>

                    {{-- Infos --}}
                    <div class="item-info">
                        <p class="item-cat">{{ $item['categorie'] ?? 'Produit' }}</p>
                        <h5 class="item-name">{{ $item['nom'] }}</h5>
                        <p class="item-unit">
                            {{ number_format($item['prix'], 0, ',', ' ') }} FCFA / {{ $item['unite'] ?? 'kg' }}
                        </p>
                    </div>

                    {{-- Contrôles --}}
                    <div class="item-controls">

                        {{-- Total ligne --}}
                        <div class="item-total" id="total-{{ $id }}">
                            {{ number_format($item['prix'] * $item['quantite'], 0, ',', ' ') }} FCFA
                        </div>

                        {{-- Quantité --}}
                        <div class="qty-ctrl">
                            <button type="button" class="qty-btn" onclick="changeQty({{ $id }}, -1)">−</button>
                            <input
                                type="number"
                                class="qty-input"
                                id="qty-{{ $id }}"
                                value="{{ $item['quantite'] }}"
                                min="1"
                                max="50"
                                data-price="{{ $item['prix'] }}"
                                data-id="{{ $id }}"
                                onchange="updateQty({{ $id }})"
                            >
                            <button type="button" class="qty-btn" onclick="changeQty({{ $id }}, 1)">+</button>
                        </div>

                        {{-- Supprimer --}}
                        <a href="{{ route('cart.remove', $id) }}"
                           class="btn-remove"
                           title="Retirer"
                           onclick="return confirm('Retirer cet article ?')"
                        >
                            <i class="fas fa-trash-alt"></i>
                        </a>
                    </div>
                </div>
                @endforeach

                {{-- Lien continuer --}}
                <div class="mt-3">
                    <a href="{{ route('shop') }}" style="color:var(--primary);font-weight:600;font-size:14px;text-decoration:none;">
                        <i class="fas fa-arrow-left"></i> Continuer mes achats
                    </a>
                </div>
            </div>

            {{-- Résumé --}}
            <div class="col-lg-4">
                <div class="summary-card">
                    <p class="summary-title">Résumé de commande</p>

                    <div class="summary-row">
                        <span class="s-label">Sous-total</span>
                        <span class="s-val" id="cart-subtotal">
                            {{ number_format($total, 0, ',', ' ') }} FCFA
                        </span>
                    </div>
                    <div class="summary-row">
                        <span class="s-label">Livraison (Cotonou)</span>
                        <span class="s-val free"><i class="fas fa-check-circle"></i> Gratuite</span>
                    </div>
                    <div class="summary-row" id="promo-row" style="{{ session('promo') ? '' : 'display:none;' }}">
                        <span class="s-label">Code promo</span>
                        <span class="s-val promo" id="promo-val">− 0 FCFA</span>
                    </div>

                    <div class="summary-total">
                        <span class="t-label">Total à payer</span>
                        <span class="t-val" id="cart-total">
                            {{ number_format($total, 0, ',', ' ') }} FCFA
                        </span>
                    </div>

                    {{-- Code promo --}}
                    <form class="promo-form" action="#" method="POST">
                        @csrf
                        <input type="text" name="code_promo" placeholder="Code promo..." value="{{ old('code_promo') }}">
                        <button type="submit">Appliquer</button>
                    </form>

                    {{-- Bouton commander --}}
                    <a href="{{ route('cart.checkout') }}" class="btn-checkout">
                        <i class="fas fa-credit-card"></i>
                        Commander maintenant
                    </a>

                    <div class="secure-note">
                        <i class="fas fa-lock"></i>
                        Paiement 100% sécurisé — Mobile Money
                    </div>

                    {{-- Méthodes de paiement --}}
                    <div style="display:flex;justify-content:center;gap:10px;margin-top:14px;flex-wrap:wrap;">
                        <span style="background:var(--light-bg);border-radius:8px;padding:5px 10px;font-size:12px;font-weight:700;color:#555;">📱 MTN MoMo</span>
                        <span style="background:var(--light-bg);border-radius:8px;padding:5px 10px;font-size:12px;font-weight:700;color:#555;">💙 Moov Money</span>
                        <span style="background:var(--light-bg);border-radius:8px;padding:5px 10px;font-size:12px;font-weight:700;color:#555;">💵 Espèces</span>
                    </div>
                </div>
            </div>

        </div>
        @endif

    </div>
</section>

@endsection

@section('scripts')
<script>
    const prices = {
        @foreach($cart as $id => $item)
            "{{ $id }}": {{ $item['prix'] }},
        @endforeach
    };

    function changeQty(id, delta) {
        const input = document.getElementById('qty-' + id);
        let val = parseInt(input.value) + delta;
        val = Math.max(1, Math.min(50, val));
        input.value = val;
        updateQty(id);
    }

    function updateQty(id) {
        const input    = document.getElementById('qty-' + id);
        const qty      = parseInt(input.value) || 1;
        const price    = prices[id];
        const lineTotal = price * qty;

        document.getElementById('total-' + id).textContent =
            lineTotal.toLocaleString('fr-FR') + ' FCFA';

        recalcTotal();

        fetch('{{ url("cart/update") }}/' + encodeURIComponent(id), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify({ quantite: qty })
        });
    }

    function recalcTotal() {
        let total = 0;
        Object.keys(prices).forEach(id => {
            const input = document.getElementById('qty-' + id);
            if (input) total += prices[id] * (parseInt(input.value) || 1);
        });
        document.getElementById('cart-subtotal').textContent = total.toLocaleString('fr-FR') + ' FCFA';
        document.getElementById('cart-total').textContent    = total.toLocaleString('fr-FR') + ' FCFA';
        updateNavBadge();
    }

    function updateNavBadge() {
        let count = 0;
        Object.keys(prices).forEach(id => {
            const input = document.getElementById('qty-' + id);
            if (input) count += parseInt(input.value) || 1;
        });
        const badge = document.getElementById('cart-badge');
        if (badge) badge.textContent = count;
    }
</script>
@endsection