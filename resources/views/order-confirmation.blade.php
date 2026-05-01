@extends('layouts.app')

@section('title', 'Commande confirmée - FreshMarket')

@section('styles')
<style>
    :root {
        --primary:#0a4f6e; --primary-dark:#073a52;
        --secondary:#e8830a; --accent:#c0392b;
        --green:#27ae60; --light-bg:#f4f9fc;
        --dark:#1a1a2e; --muted:#6c757d;
    }
    .confirm-wrapper { padding:60px 0 80px; background:var(--light-bg); min-height:70vh; }

    /* Icône succès animée */
    .success-circle {
        width:90px; height:90px; border-radius:50%;
        background:linear-gradient(135deg, var(--green), #2ecc71);
        display:flex; align-items:center; justify-content:center;
        font-size:38px; color:#fff; margin:0 auto 20px;
        animation: popIn .5s cubic-bezier(.175,.885,.32,1.275) both;
        box-shadow:0 10px 30px rgba(39,174,96,.35);
    }
    @keyframes popIn { from{transform:scale(0);opacity:0;} to{transform:scale(1);opacity:1;} }

    .confirm-header { text-align:center; margin-bottom:40px; }
    .confirm-header h1 { font-size:32px; font-weight:800; color:var(--dark); margin-bottom:8px; }
    .confirm-header p  { font-size:16px; color:var(--muted); }

    /* Badge référence */
    .ref-badge {
        display:inline-flex; align-items:center; gap:8px;
        background:#fff; border:2px solid var(--primary);
        border-radius:12px; padding:10px 20px;
        font-size:16px; font-weight:800; color:var(--primary);
        margin:14px auto 0; cursor:pointer;
        transition:all .2s;
    }
    .ref-badge:hover { background:var(--primary); color:#fff; }

    /* Carte principale */
    .order-card {
        background:#fff; border-radius:20px; padding:30px 28px;
        box-shadow:0 4px 20px rgba(0,0,0,.08); margin-bottom:20px;
    }
    .oc-title {
        font-size:12px; font-weight:700; color:var(--primary);
        text-transform:uppercase; letter-spacing:1.2px;
        margin-bottom:18px; padding-bottom:12px;
        border-bottom:2px solid var(--light-bg);
        display:flex; align-items:center; gap:8px;
    }

    /* Infos en grille */
    .info-grid { display:grid; grid-template-columns:1fr 1fr; gap:16px; }
    .info-item {}
    .info-item .label { font-size:11px; color:var(--muted); text-transform:uppercase; letter-spacing:.6px; font-weight:600; margin-bottom:4px; }
    .info-item .value { font-size:14px; font-weight:700; color:var(--dark); }

    /* Statut commande */
    .status-badge {
        display:inline-flex; align-items:center; gap:6px;
        padding:6px 14px; border-radius:25px;
        font-size:13px; font-weight:700;
    }
    .status-pending  { background:rgba(232,131,10,.12); color:#a05c00; }
    .status-dot { width:8px; height:8px; border-radius:50%; background:currentColor; animation:pulse 1.5s infinite; }
    @keyframes pulse { 0%,100%{opacity:1;} 50%{opacity:.4;} }

    /* Articles */
    .order-item { display:flex; align-items:center; gap:14px; padding:12px 0; border-bottom:1px solid #f5f5f5; }
    .order-item:last-child { border-bottom:none; }
    .oi-img {
        width:50px; height:50px; border-radius:10px;
        background:var(--light-bg); display:flex; align-items:center;
        justify-content:center; font-size:22px; flex-shrink:0;
    }
    .oi-name  { font-size:14px; font-weight:700; color:var(--dark); }
    .oi-qty   { font-size:12px; color:var(--muted); margin-top:2px; }
    .oi-price { font-size:15px; font-weight:800; color:var(--primary); margin-left:auto; flex-shrink:0; }

    /* Total */
    .order-total {
        display:flex; justify-content:space-between; align-items:center;
        padding:16px 0 0; margin-top:8px; border-top:2px solid var(--light-bg);
    }
    .order-total .tl { font-size:16px; font-weight:700; color:var(--dark); }
    .order-total .tv { font-size:24px; font-weight:800; color:var(--primary); }

    /* Instructions Mobile Money */
    .momo-steps {
        background:linear-gradient(135deg, rgba(10,79,110,.05), rgba(10,79,110,.02));
        border:1.5px solid rgba(10,79,110,.12);
        border-radius:14px; padding:20px 22px;
    }
    .momo-step {
        display:flex; align-items:flex-start; gap:12px;
        padding:10px 0; border-bottom:1px solid rgba(10,79,110,.08);
    }
    .momo-step:last-child { border-bottom:none; }
    .ms-num {
        width:28px; height:28px; border-radius:50%;
        background:var(--primary); color:#fff;
        display:flex; align-items:center; justify-content:center;
        font-size:13px; font-weight:800; flex-shrink:0;
    }
    .ms-text { font-size:14px; color:var(--dark); line-height:1.5; }
    .ms-text strong { color:var(--primary); }

    /* Boutons d'action */
    .action-btns { display:flex; gap:12px; flex-wrap:wrap; justify-content:center; margin-top:30px; }
    .btn-action {
        padding:12px 28px; border-radius:50px; font-size:14px; font-weight:700;
        text-decoration:none; display:inline-flex; align-items:center; gap:8px;
        transition:all .3s;
    }
    .btn-primary-fm { background:var(--primary); color:#fff; }
    .btn-primary-fm:hover { background:var(--secondary); color:#fff; transform:translateY(-2px); }
    .btn-outline-fm { background:#fff; color:var(--primary); border:2px solid var(--primary); }
    .btn-outline-fm:hover { background:var(--primary); color:#fff; }

    /* WhatsApp */
    .whatsapp-card {
        background:linear-gradient(135deg, #25d366, #128c7e);
        border-radius:16px; padding:22px 24px;
        display:flex; align-items:center; gap:16px;
        color:#fff; margin-bottom:20px;
    }
    .wa-icon { font-size:40px; flex-shrink:0; }
    .wa-text h4 { font-size:17px; font-weight:800; margin-bottom:4px; }
    .wa-text p  { font-size:13px; opacity:.9; margin-bottom:10px; }
    .btn-wa {
        background:#fff; color:#128c7e; padding:8px 18px;
        border-radius:25px; font-size:13px; font-weight:700;
        text-decoration:none; display:inline-block; transition:all .2s;
    }
    .btn-wa:hover { background:#f0fff4; color:#128c7e; }

    @media(max-width:576px) {
        .info-grid { grid-template-columns:1fr; }
        .confirm-header h1 { font-size:24px; }
        .action-btns { flex-direction:column; align-items:stretch; }
        .btn-action { justify-content:center; }
    }
</style>
@endsection

@section('content')

<section class="confirm-wrapper">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                {{-- ===== HEADER SUCCÈS ===== --}}
                <div class="confirm-header">
                    <div class="success-circle">✓</div>
                    <h1>Commande confirmée !</h1>
                    <p>Merci <strong>{{ $order['prenom'] }}</strong> ! Votre commande a bien été enregistrée.</p>
                    <div
                        class="ref-badge"
                        onclick="navigator.clipboard.writeText('{{ $order['reference'] }}').then(()=>this.innerHTML='✅ Copié !')"
                        title="Cliquer pour copier"
                    >
                        <i class="fas fa-hashtag"></i>
                        {{ $order['reference'] }}
                        <i class="fas fa-copy" style="font-size:12px;opacity:.6;"></i>
                    </div>
                    <p style="font-size:12px;color:var(--muted);margin-top:8px;">
                        Passée le {{ $order['created_at'] }}
                    </p>
                </div>

                {{-- ===== STATUT ===== --}}
                <div class="order-card">
                    <p class="oc-title"><i class="fas fa-info-circle"></i> Statut de la commande</p>
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                        @if(!empty($order['statut_paiement']) && $order['statut_paiement'] === 'paye')
                            <span style="color:var(--green);" class="status-badge  status-success">
                                <span class="status-dot"></span>
                                Paiement confirmé
                            </span>
                        @else
                            <span class="status-badge status-pending">
                                <span class="status-dot"></span>
                                En attente de paiement
                            </span>
                        @endif
                        <span style="font-size:13px;color:var(--muted);">
                            <i class="fas fa-clock"></i> Mise à jour dans quelques minutes
                        </span>
                    </div>
                </div>

                {{-- ===== INSTRUCTIONS DE PAIEMENT ===== --}}
                <div class="order-card">
                    <p class="oc-title">
                        <i class="fas fa-mobile-alt"></i>
                        @if(!empty($order['momo_operateur']))
                            Finaliser le paiement —
                            {{ $order['momo_operateur'] === 'mtn' ? '🟡 MTN MoMo' : '🔵 Moov Money' }}
                        @else
                            Paiement sécurisé via Kkiapay
                        @endif
                    </p>

                    <div class="momo-steps">
                        @if(!empty($order['momo_operateur']))
                            @if($order['momo_operateur'] === 'mtn')
                                <div class="momo-step">
                                    <div class="ms-num">1</div>
                                    <p class="ms-text">Composez le <strong>*880#</strong> ou ouvrez l'app <strong>MTN MoMo</strong> sur votre téléphone.</p>
                                </div>
                                <div class="momo-step">
                                    <div class="ms-num">2</div>
                                    <p class="ms-text">Sélectionnez <strong>« Paiement marchand »</strong> puis entrez le code marchand <strong>FreshMarket</strong>.</p>
                                </div>
                                <div class="momo-step">
                                    <div class="ms-num">3</div>
                                    <p class="ms-text">Entrez le montant exact : <strong>{{ number_format($order['total'], 0, ',', ' ') }} FCFA</strong></p>
                                </div>
                                <div class="momo-step">
                                    <div class="ms-num">4</div>
                                    <p class="ms-text">Confirmez avec votre <strong>code PIN MTN MoMo</strong>. Vous recevrez un SMS de confirmation.</p>
                                </div>
                            @else
                                <div class="momo-step">
                                    <div class="ms-num">1</div>
                                    <p class="ms-text">Composez le <strong>*155#</strong> ou ouvrez l'app <strong>Moov Money</strong> sur votre téléphone.</p>
                                </div>
                                <div class="momo-step">
                                    <div class="ms-num">2</div>
                                    <p class="ms-text">Sélectionnez <strong>« Paiement »</strong> puis entrez le code marchand <strong>FreshMarket</strong>.</p>
                                </div>
                                <div class="momo-step">
                                    <div class="ms-num">3</div>
                                    <p class="ms-text">Entrez le montant exact : <strong>{{ number_format($order['total'], 0, ',', ' ') }} FCFA</strong></p>
                                </div>
                                <div class="momo-step">
                                    <div class="ms-num">4</div>
                                    <p class="ms-text">Confirmez avec votre <strong>code PIN Flooz</strong>. Vous recevrez un SMS de confirmation.</p>
                                </div>
                            @endif
                        @else
                            <div class="momo-step">
                                <div class="ms-num">1</div>
                                <p class="ms-text">Votre paiement a été effectué via Kkiapay. Votre commande est confirmée.</p>
                            </div>
                            <div class="momo-step">
                                <div class="ms-num">2</div>
                                <p class="ms-text">Vous pouvez vérifier votre transaction depuis le dashboard Kkiapay ou via votre SMS de confirmation.</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- ===== DÉTAILS COMMANDE ===== --}}
                <div class="order-card">
                    <p class="oc-title"><i class="fas fa-box"></i> Détails de la commande</p>

                    {{-- Articles --}}
                    @foreach($order['articles'] as $item)
                    <div class="order-item">
                        <div class="oi-img">🐟</div>
                        <div>
                            <p class="oi-name">{{ $item['nom'] }}</p>
                            <p class="oi-qty">{{ $item['quantite'] }} {{ $item['unite'] ?? 'kg' }} × {{ number_format($item['prix'], 0, ',', ' ') }} FCFA</p>
                        </div>
                        <p class="oi-price">{{ number_format($item['prix'] * $item['quantite'], 0, ',', ' ') }} FCFA</p>
                    </div>
                    @endforeach

                    <div class="order-total">
                        <span class="tl">Total payé</span>
                        <span class="tv">{{ number_format($order['total'], 0, ',', ' ') }} FCFA</span>
                    </div>
                </div>

                {{-- ===== LIVRAISON ===== --}}
                <div class="order-card">
                    <p class="oc-title"><i class="fas fa-truck"></i> Informations de livraison</p>
                    <div class="info-grid">
                        <div class="info-item">
                            <p class="label">Destinataire</p>
                            <p class="value">{{ $order['prenom'] }} {{ $order['nom'] }}</p>
                        </div>
                        <div class="info-item">
                            <p class="label">Téléphone</p>
                            <p class="value">{{ $order['telephone'] }}</p>
                        </div>
                        <div class="info-item" style="grid-column:1/-1;">
                            <p class="label">Adresse</p>
                            <p class="value">{{ $order['adresse'] }}</p>
                        </div>
                        <div class="info-item">
                            <p class="label">Livraison estimée</p>
                            <p class="value">
                                @if(now()->hour < 12)
                                    Aujourd'hui — après-midi
                                @else
                                    Demain matin
                                @endif
                            </p>
                        </div>
                        <div class="info-item">
                            <p class="label">Frais de livraison</p>
                            <p class="value" style="color:var(--green);">Gratuits 🎉</p>
                        </div>
                    </div>
                </div>

                {{-- ===== WHATSAPP ===== --}}
                <div class="whatsapp-card">
                    <div class="wa-icon">💬</div>
                    <div class="wa-text">
                        <h4>Besoin d'aide pour votre commande ?</h4>
                        <p>Contactez-nous directement sur WhatsApp avec votre référence <strong>{{ $order['reference'] }}</strong></p>
                        <a
                            href="https://wa.me/22900000000?text=Bonjour%2C%20je%20viens%20de%20passer%20la%20commande%20{{ $order['reference'] }}%20sur%20FreshMarket."
                            class="btn-wa"
                            target="_blank"
                        >
                            <i class="fab fa-whatsapp"></i> Contacter sur WhatsApp
                        </a>
                    </div>
                </div>

                {{-- ===== BOUTONS ===== --}}
                <div class="action-btns">
                    <a href="{{ route('home') }}" class="btn-action btn-primary-fm">
                        <i class="fas fa-home"></i> Retour à l'accueil
                    </a>
                    <a href="{{ route('shop') }}" class="btn-action btn-outline-fm">
                        <i class="fas fa-shopping-bag"></i> Continuer mes achats
                    </a>
                </div>

            </div>
        </div>
    </div>
</section>

@endsection
