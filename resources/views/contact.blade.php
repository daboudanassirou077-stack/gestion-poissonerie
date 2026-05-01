@extends('layouts.app')

@section('title', 'Contact - FreshMarket Poissonnerie & Boucherie')

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

    /* ===== PAGE HERO ===== */
    .page-hero {
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 60%, #1a7a9a 100%);
        padding: 50px 0;
        position: relative;
        overflow: hidden;
    }
    .page-hero::before {
        content: '📞';
        position: absolute;
        font-size: 200px;
        opacity: .04;
        right: -20px;
        top: -30px;
        transform: rotate(-10deg);
    }
    .page-hero::after {
        content: '✉️';
        position: absolute;
        font-size: 150px;
        opacity: .04;
        left: 30px;
        bottom: -20px;
        transform: rotate(10deg);
    }
    .page-hero h1      { font-size: 38px; color: #fff; font-weight: 800; margin-bottom: 10px; }
    .breadcrumb        { background: transparent; padding: 0; margin: 0; }
    .breadcrumb-item a { color: rgba(255,255,255,.85); text-decoration: none; }
    .breadcrumb-item.active              { color: var(--secondary); }
    .breadcrumb-item + .breadcrumb-item::before { color: rgba(255,255,255,.4); }

    /* ===== WRAPPER ===== */
    .contact-wrapper { padding: 50px 0 70px; background: var(--light-bg); }

    /* ===== CARTES INFO ===== */
    .info-card {
        background: #fff;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 3px 15px rgba(0,0,0,.07);
        margin-bottom: 20px;
    }
    .card-section-title {
        font-size: 11px;
        font-weight: 700;
        color: var(--primary);
        text-transform: uppercase;
        letter-spacing: 1.2px;
        margin-bottom: 18px;
        padding-bottom: 10px;
        border-bottom: 2px solid var(--light-bg);
    }

    /* Items de contact */
    .contact-item {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        padding: 12px 0;
        border-bottom: 1px solid #f5f5f5;
    }
    .contact-item:last-child { border-bottom: none; }
    .ci-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: rgba(10,79,110,.08);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }
    .ci-icon.orange { background: rgba(232,131,10,.1); }
    .ci-icon.green  { background: rgba(39,174,96,.1); }
    .ci-label {
        font-size: 11px;
        color: var(--muted);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .6px;
        margin-bottom: 3px;
    }
    .ci-value {
        font-size: 15px;
        color: var(--dark);
        font-weight: 700;
        line-height: 1.4;
    }
    .ci-value a {
        color: var(--dark);
        text-decoration: none;
        transition: color .2s;
    }
    .ci-value a:hover { color: var(--secondary); }

    /* Horaires */
    .hours-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 6px;
    }
    .hours-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 13px;
        padding: 7px 10px;
        border-radius: 8px;
        background: var(--light-bg);
    }
    .hours-row.full   { grid-column: 1 / -1; }
    .hours-row.today  { background: rgba(232,131,10,.12); }
    .hours-row.today .day  { color: var(--secondary); font-weight: 700; }
    .hours-row.today .time { color: var(--secondary); font-weight: 800; }
    .hours-row.closed { background: rgba(192,57,43,.07); }
    .hours-row.closed .day  { color: var(--accent); }
    .hours-row.closed .time { color: var(--accent); font-weight: 700; }
    .hours-row .day   { color: #555; font-weight: 600; }
    .hours-row .time  { color: var(--primary); font-weight: 700; }

    /* Réseaux sociaux */
    .social-links { display: flex; gap: 10px; margin-top: 18px; }
    .social-btn {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: var(--light-bg);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
        font-size: 16px;
        text-decoration: none;
        transition: all .3s;
    }
    .social-btn:hover { background: var(--primary); color: #fff; transform: translateY(-3px); }

    /* Carte / Map */
    .map-frame {
        border-radius: 16px;
        overflow: hidden;
        height: 220px;
        background: #e8f4f8;
        position: relative;
    }
    .map-frame iframe {
        width: 100%;
        height: 100%;
        border: none;
    }
    .map-placeholder {
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #cce8f4 0%, #e1f5ee 100%);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }
    .map-placeholder .map-emoji { font-size: 48px; }
    .map-placeholder p {
        font-size: 14px;
        color: var(--primary);
        font-weight: 600;
        text-align: center;
        margin: 0;
    }
    .map-pin-badge {
        background: var(--accent);
        color: #fff;
        padding: 5px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
    }

    /* ===== FORMULAIRE ===== */
    .form-card {
        background: #fff;
        border-radius: 16px;
        padding: 35px 30px;
        box-shadow: 0 3px 15px rgba(0,0,0,.07);
        height: 100%;
    }
    .form-card h2 {
        font-size: 28px;
        font-weight: 800;
        color: var(--dark);
        margin-bottom: 6px;
    }
    .form-card .subtitle {
        font-size: 15px;
        color: var(--muted);
        margin-bottom: 24px;
    }

    /* Chips sujet */
    .subject-chips {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 24px;
    }
    .subject-chip {
        padding: 7px 16px;
        border-radius: 25px;
        font-size: 13px;
        font-weight: 600;
        border: 1.5px solid #e0e0e0;
        cursor: pointer;
        color: #666;
        background: #fff;
        transition: all .25s;
        user-select: none;
    }
    .subject-chip:hover   { border-color: var(--primary); color: var(--primary); }
    .subject-chip.active  { background: var(--primary); border-color: var(--primary); color: #fff; }

    /* Champs */
    .form-group { margin-bottom: 18px; }
    .form-label {
        display: block;
        font-size: 12px;
        font-weight: 700;
        color: var(--primary);
        text-transform: uppercase;
        letter-spacing: .8px;
        margin-bottom: 7px;
    }
    .form-label span { color: var(--accent); }
    .form-control-custom {
        width: 100%;
        padding: 12px 15px;
        border: 1.5px solid #e5e5e5;
        border-radius: 10px;
        font-size: 14px;
        color: var(--dark);
        background: #fff;
        transition: border-color .3s, box-shadow .3s;
    }
    .form-control-custom:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(10,79,110,.08);
    }
    .form-control-custom.is-invalid { border-color: var(--accent); }
    textarea.form-control-custom { resize: vertical; min-height: 130px; }

    /* Bouton envoi */
    .btn-send {
        background: var(--primary);
        color: #fff;
        border: none;
        padding: 14px 32px;
        border-radius: 50px;
        font-size: 15px;
        font-weight: 700;
        cursor: pointer;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        transition: all .3s;
        margin-top: 6px;
    }
    .btn-send:hover {
        background: var(--secondary);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(232,131,10,.35);
    }

    /* Alertes */
    .alert-success-custom {
        background: rgba(39,174,96,.1);
        border: 1.5px solid rgba(39,174,96,.3);
        color: #1e7e44;
        border-radius: 12px;
        padding: 14px 18px;
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .alert-error-custom {
        background: rgba(192,57,43,.08);
        border: 1.5px solid rgba(192,57,43,.25);
        color: var(--accent);
        border-radius: 12px;
        padding: 14px 18px;
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 20px;
    }

    /* ===== SECTION LIVRAISON RAPIDE ===== */
    .quick-contact-bar {
        background: var(--primary);
        padding: 30px 0;
    }
    .quick-item {
        display: flex;
        align-items: center;
        gap: 14px;
        color: #fff;
        padding: 10px 20px;
        border-right: 1px solid rgba(255,255,255,.15);
    }
    .quick-item:last-child { border-right: none; }
    .quick-item .q-icon {
        width: 48px;
        height: 48px;
        background: rgba(255,255,255,.1);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        flex-shrink: 0;
    }
    .quick-item h6 { font-size: 14px; font-weight: 700; margin: 0 0 3px; }
    .quick-item p  { font-size: 13px; opacity: .8; margin: 0; }
    .quick-item a  { color: var(--secondary); text-decoration: none; font-weight: 700; }
    .quick-item a:hover { text-decoration: underline; }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 991px) {
        .form-card { margin-top: 24px; padding: 24px 20px; }
        .quick-item { border-right: none; border-bottom: 1px solid rgba(255,255,255,.15); }
        .quick-item:last-child { border-bottom: none; }
    }
    @media (max-width: 576px) {
        .page-hero h1 { font-size: 28px; }
        .hours-grid   { grid-template-columns: 1fr; }
        .hours-row.full { grid-column: 1; }
    }
</style>
@endsection

@section('content')

{{-- ===== HERO ===== --}}
<div class="page-hero">
    <div class="container" style="position:relative;z-index:2;">
        <h1>Contactez-nous</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                <li class="breadcrumb-item active">Contact</li>
            </ol>
        </nav>
    </div>
</div>

{{-- ===== BARRE RAPIDE ===== --}}
<div class="quick-contact-bar">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="quick-item">
                    <div class="q-icon">📞</div>
                    <div>
                        <h6>Appelez-nous</h6>
                        <p><a href="tel:+22900000000">+229 00 000 000</a></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="quick-item">
                    <div class="q-icon">💬</div>
                    <div>
                        <h6>WhatsApp</h6>
                        <p><a href="https://wa.me/22900000000" target="_blank">Chatter maintenant</a></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="quick-item">
                    <div class="q-icon">🚚</div>
                    <div>
                        <h6>Commande avant 12h</h6>
                        <p>Livraison le jour même à Cotonou</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ===== CONTENU PRINCIPAL ===== --}}
<section class="contact-wrapper">
    <div class="container">
        <div class="row">

            {{-- ===== COLONNE INFO ===== --}}
            <div class="col-lg-4 mb-4 mb-lg-0">

                {{-- Coordonnées --}}
                <div class="info-card">
                    <p class="card-section-title">Nos coordonnées</p>

                    <div class="contact-item">
                        <div class="ci-icon">📍</div>
                        <div>
                            <p class="ci-label">Adresse</p>
                            <p class="ci-value">Cotonou, Bénin<br>
                                <small style="font-size:13px;color:var(--muted);font-weight:500;">Quartier Akpakpa, Rue des Pêcheurs</small>
                            </p>
                        </div>
                    </div>

                    <div class="contact-item">
                        <div class="ci-icon">📞</div>
                        <div>
                            <p class="ci-label">Téléphone</p>
                            <p class="ci-value"><a href="tel:+22900000000">+229 00 000 000</a></p>
                        </div>
                    </div>

                    <div class="contact-item">
                        <div class="ci-icon orange">💬</div>
                        <div>
                            <p class="ci-label">WhatsApp</p>
                            <p class="ci-value"><a href="https://wa.me/22900000000" target="_blank">+229 00 000 000</a></p>
                        </div>
                    </div>

                    <div class="contact-item">
                        <div class="ci-icon">✉️</div>
                        <div>
                            <p class="ci-label">Email</p>
                            <p class="ci-value"><a href="mailto:contact@freshmarket.bj">contact@freshmarket.bj</a></p>
                        </div>
                    </div>

                    <div class="contact-item">
                        <div class="ci-icon green">📸</div>
                        <div>
                            <p class="ci-label">Instagram</p>
                            <p class="ci-value"><a href="https://instagram.com/freshmarket.bj" target="_blank">@freshmarket.bj</a></p>
                        </div>
                    </div>

                    {{-- Réseaux sociaux --}}
                    <div class="social-links">
                        <a href="#" class="social-btn" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-btn" title="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="https://wa.me/22900000000" class="social-btn" title="WhatsApp" target="_blank"><i class="fab fa-whatsapp"></i></a>
                        <a href="#" class="social-btn" title="TikTok"><i class="fab fa-tiktok"></i></a>
                    </div>
                </div>

                {{-- Horaires --}}
                <div class="info-card">
                    <p class="card-section-title">Horaires d'ouverture</p>
                    <div class="hours-grid">
                        @php
                            $jourSemaine = now()->locale('fr')->dayName;
                            $joursMap = [
                                'Lundi'    => ['Lun', '7h – 20h', false],
                                'Mardi'    => ['Mar', '7h – 20h', false],
                                'Mercredi' => ['Mer', '7h – 20h', false],
                                'Jeudi'    => ['Jeu', '7h – 20h', false],
                                'Vendredi' => ['Ven', '7h – 20h', false],
                                'Samedi'   => ['Sam', '7h – 18h', false],
                                'Dimanche' => ['Dim', 'Fermé',    true],
                            ];
                        @endphp
                        @foreach($joursMap as $nomJour => [$abr, $heure, $ferme])
                            @php
                                $isToday  = strtolower($jourSemaine) === strtolower($nomJour);
                                $classes  = 'hours-row';
                                $classes .= $nomJour === 'Dimanche' ? ' full closed' : '';
                                $classes .= $isToday && !$ferme ? ' today' : '';
                            @endphp
                            <div class="{{ $classes }}">
                                <span class="day">
                                    {{ $abr }}
                                    @if($isToday) <small style="font-size:10px;">(auj.)</small> @endif
                                </span>
                                <span class="time">{{ $heure }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Carte --}}
                <div class="map-frame">
                    {{-- Remplacez l'URL ci-dessous par votre embed Google Maps réel --}}
                    {{-- <iframe src="https://www.google.com/maps/embed?pb=..."></iframe> --}}
                    <div class="map-placeholder">
                        <div class="map-emoji">🗺️</div>
                        <p>Cotonou, Bénin</p>
                        <span class="map-pin-badge">📍 Akpakpa</span>
                    </div>
                </div>

            </div>

            {{-- ===== FORMULAIRE ===== --}}
            <div class="col-lg-8">
                <div class="form-card">
                    <h2>Envoyez-nous un message</h2>
                    <p class="subtitle">Nous répondons généralement en moins de 24h. Pour une réponse rapide, utilisez WhatsApp.</p>

                    {{-- Alertes --}}
                    @if(session('success'))
                        <div class="alert-success-custom">
                            <i class="fas fa-check-circle" style="font-size:18px;"></i>
                            {{ session('success') }}
                        </div>
                    @endif
                    @if($errors->any())
                        <div class="alert-error-custom">
                            <i class="fas fa-exclamation-circle"></i>
                            Veuillez corriger les erreurs ci-dessous.
                        </div>
                    @endif

                    {{-- Chips sujet --}}
                    <div class="subject-chips" id="subjectChips">
                        <span class="subject-chip active" data-value="Commande">🛒 Commande</span>
                        <span class="subject-chip" data-value="Livraison">🚚 Livraison</span>
                        <span class="subject-chip" data-value="Produit">🐟 Produit</span>
                        <span class="subject-chip" data-value="Partenariat">🤝 Partenariat</span>
                        <span class="subject-chip" data-value="Réclamation">⚠️ Réclamation</span>
                        <span class="subject-chip" data-value="Autre">💬 Autre</span>
                    </div>

                    <form action="{{ route('contact.send') }}" method="POST" id="contactForm" novalidate>
                        @csrf

                        {{-- Champ sujet caché mis à jour par les chips --}}
                        <input type="hidden" name="sujet_chip" id="sujetChip" value="Commande">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="prenom">Prénom <span>*</span></label>
                                    <input
                                        type="text"
                                        id="prenom"
                                        name="prenom"
                                        class="form-control-custom @error('prenom') is-invalid @enderror"
                                        placeholder="Votre prénom"
                                        value="{{ old('prenom') }}"
                                        required
                                    >
                                    @error('prenom')<div style="color:var(--accent);font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="nom">Nom <span>*</span></label>
                                    <input
                                        type="text"
                                        id="nom"
                                        name="nom"
                                        class="form-control-custom @error('nom') is-invalid @enderror"
                                        placeholder="Votre nom"
                                        value="{{ old('nom') }}"
                                        required
                                    >
                                    @error('nom')<div style="color:var(--accent);font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="email">Email <span>*</span></label>
                                    <input
                                        type="email"
                                        id="email"
                                        name="email"
                                        class="form-control-custom @error('email') is-invalid @enderror"
                                        placeholder="vous@email.com"
                                        value="{{ old('email') }}"
                                        required
                                    >
                                    @error('email')<div style="color:var(--accent);font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="telephone">Téléphone / WhatsApp</label>
                                    <input
                                        type="tel"
                                        id="telephone"
                                        name="telephone"
                                        class="form-control-custom @error('telephone') is-invalid @enderror"
                                        placeholder="+229 00 000 000"
                                        value="{{ old('telephone') }}"
                                    >
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="sujet">Sujet <span>*</span></label>
                            <input
                                type="text"
                                id="sujet"
                                name="sujet"
                                class="form-control-custom @error('sujet') is-invalid @enderror"
                                placeholder="Décrivez brièvement votre demande"
                                value="{{ old('sujet') }}"
                                required
                            >
                            @error('sujet')<div style="color:var(--accent);font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="message">Message <span>*</span></label>
                            <textarea
                                id="message"
                                name="message"
                                class="form-control-custom @error('message') is-invalid @enderror"
                                placeholder="Décrivez votre demande en détail..."
                                required
                            >{{ old('message') }}</textarea>
                            @error('message')<div style="color:var(--accent);font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
                        </div>

                        {{-- Honeypot anti-spam --}}
                        <input type="text" name="website" style="display:none;" tabindex="-1" autocomplete="off">

                        <button type="submit" class="btn-send">
                            <i class="fas fa-paper-plane"></i>
                            Envoyer le message
                        </button>

                        <p style="text-align:center;font-size:12px;color:var(--muted);margin-top:14px;">
                            🔒 Vos données sont confidentielles et ne seront jamais partagées.
                        </p>
                    </form>
                </div>
            </div>

        </div>
    </div>
</section>

@endsection

@section('scripts')
<script>
    // Chips de sujet
    document.querySelectorAll('.subject-chip').forEach(chip => {
        chip.addEventListener('click', function () {
            document.querySelectorAll('.subject-chip').forEach(c => c.classList.remove('active'));
            this.classList.add('active');

            const val = this.getAttribute('data-value');
            document.getElementById('sujetChip').value = val;

            // Pré-remplir le champ sujet si vide
            const sujetInput = document.getElementById('sujet');
            if (!sujetInput.value || sujetInput.dataset.auto === 'true') {
                sujetInput.value = val;
                sujetInput.dataset.auto = 'true';
            }
        });
    });

    // Désactiver le flag auto si l'utilisateur tape manuellement
    document.getElementById('sujet').addEventListener('input', function () {
        this.dataset.auto = 'false';
    });
</script>
@endsection
