<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    @yield('styles')
  <style>

    .footer {
    background: linear-gradient(135deg, #0a4f6e, #1a1a2e);
    color: #fff;
    padding: 60px 0 20px;
    font-size: 14px;
}

.footer-logo {
    font-weight: 800;
    margin-bottom: 15px;
}

.footer-text {
    color: rgba(255,255,255,0.7);
    line-height: 1.7;
}

.footer-title {
    font-weight: 700;
    margin-bottom: 15px;
    color: #e8830a;
}

.footer-links {
    list-style: none;
    padding: 0;
}

.footer-links li {
    margin-bottom: 8px;
}

.footer-links a {
    color: rgba(255,255,255,0.75);
    text-decoration: none;
    transition: 0.3s;
}

.footer-links a:hover {
    color: #e8830a;
    padding-left: 5px;
}

.footer-contact {
    list-style: none;
    padding: 0;
}

.footer-contact li {
    margin-bottom: 10px;
    color: rgba(255,255,255,0.75);
}

.footer-contact i {
    margin-right: 8px;
    color: #e8830a;
}

.footer-payments span {
    display: inline-block;
    margin-right: 10px;
    margin-top: 10px;
    background: rgba(255,255,255,0.1);
    padding: 6px 10px;
    border-radius: 8px;
    font-size: 13px;
}

.footer-bottom {
    border-top: 1px solid rgba(255,255,255,0.1);
    text-align: center;
    padding-top: 15px;
    margin-top: 20px;
    color: rgba(255,255,255,0.6);
}
</style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand fw-bold" href="{{ route('home') }}">🐟 FreshMarket</a>

        <!-- Bouton mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Menu -->
        <div class="collapse navbar-collapse" id="navbarNav">

            <!-- Liens principaux -->
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}">Accueil</a>
                </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('shop') }}">Boutique</a>
                    </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('about') }}">À propos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('contact') }}">Contact</a>
                </li>
            </ul>

            <!-- Droite -->
            <ul class="navbar-nav ms-auto align-items-center gap-2">

                <!-- Panier (accessible à tous) -->
                <li class="nav-item">
                    <a class="nav-link position-relative" href="{{ route('cart') }}">
                        <i class="fas fa-shopping-cart fs-5"></i>
                        @php $cartCount = collect(session('cart', []))->sum('quantite'); @endphp
                        @if($cartCount > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size:10px;">
                            {{ $cartCount }}
                        </span>
                        @endif
                    </a>
                </li>

                @guest
                {{-- Visiteur : Connexion + Inscription --}}
                <li class="nav-item">
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm px-3">
                        <i class="fas fa-sign-in-alt"></i> Connexion
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('register') }}" class="btn btn-warning btn-sm px-3 fw-bold text-dark">
                        <i class="fas fa-user-plus"></i> Inscription
                    </a>
                </li>
                @endguest

                @auth
                {{-- Connecté : icône profil → dashboard --}}
                @php
                    $dashboardRoute = match(auth()->user()->role) {
                        'admin'   => route('admin.dashboard'),
                        'gerant'  => route('gerant.dashboard'),
                        'vendeur' => route('vendeur.dashboard'),
                        default   => route('client.dashboard'),
                    };
                @endphp

                <li class="nav-item">
                    <a href="{{ $dashboardRoute }}"
                       class="nav-link d-flex align-items-center gap-2"
                       title="Mon espace — {{ auth()->user()->prenom }}"
                    >
                        {{-- Avatar rond avec initiale --}}
                        <div style="
                            width:36px; height:36px;
                            background: linear-gradient(135deg, #e8830a, #d4720a);
                            border-radius:50%;
                            display:flex; align-items:center; justify-content:center;
                            font-weight:800; font-size:15px; color:#fff;
                            border: 2px solid rgba(255,255,255,.4);
                        ">
                            {{ strtoupper(substr(auth()->user()->prenom, 0, 1)) }}
                        </div>
                        {{-- Nom visible sur grand écran --}}
                        <span class="d-none d-lg-inline" style="font-size:14px;">
                            {{ auth()->user()->prenom }}
                        </span>
                    </a>
                </li>

                {{-- Bouton déconnexion --}}
                <li class="nav-item">
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit"
                                class="btn btn-outline-danger btn-sm"
                                title="Déconnexion"
                                onclick="return confirm('Voulez-vous vous déconnecter ?')"
                        >
                            <i class="fas fa-sign-out-alt"></i>
                        </button>
                    </form>
                </li>
                @endauth

            </ul>
        </div>
    </div>
</nav>
    @yield('content')
    
    <footer class="footer">
    <div class="container">
        <div class="row">

            <!-- Logo & description -->
            <div class="col-lg-4 col-md-6 mb-4">
                <h4 class="footer-logo">🐟 FreshMarket</h4>
                <p class="footer-text">
                    Votre poissonnerie et boucherie en ligne au Bénin. Produits frais sélectionnés chaque matin,
                    livrés rapidement chez vous à Cotonou.
                </p>

                <div class="footer-payments">
                    <span>📱 Mobile Money</span>
                    <span>💵 Espèces</span>
                    <span>💳 Carte</span>
                </div>
            </div>

            <!-- Navigation -->
            <div class="col-lg-2 col-md-6 mb-4">
                <h6 class="footer-title">Navigation</h6>
                <ul class="footer-links">
                    <li><a href="/">Accueil</a></li>
                    <li><a href="{{ route('about') }}">À propos</a></li>
                    <li><a href="{{ route('shop') }}">Boutique</a></li>
                    <li><a href="#">Promotions</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
            </div>

            <!-- Informations -->
            <div class="col-lg-3 col-md-6 mb-4">
                <h6 class="footer-title">Informations</h6>
                <ul class="footer-links">
                    <li><a href="#">Service client</a></li>
                    <li><a href="#">Livraison</a></li>
                    <li><a href="#">Retours</a></li>
                    <li><a href="#">CGV</a></li>
                    <li><a href="#">Confidentialité</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div class="col-lg-3 col-md-6 mb-4">
                <h6 class="footer-title">Contact</h6>
                <ul class="footer-contact">
                    <li><i class="fas fa-map-marker-alt"></i> Cotonou, Bénin</li>
                    <li><i class="fas fa-phone"></i> +229 00 000 000</li>
                    <li><i class="fas fa-envelope"></i> contact@freshmarket.bj</li>
                    <li><i class="fas fa-clock"></i> Lun–Sam : 7h–20h</li>
                </ul>
            </div>

        </div>

        <div class="footer-bottom">
            <p>© 2026 FreshMarket Bénin. Tous droits réservés.</p>
        </div>
    </div>
</footer>

   
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
 @yield('scripts')
</body>
</html>