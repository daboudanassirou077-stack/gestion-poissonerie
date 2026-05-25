@extends('layouts.app')

@section('title', 'À propos - FreshMarket Poissonnerie')

@section('styles')
<style>
:root{
  --primary:#0a4f6e;
  --primary-dark:#073a52;
  --secondary:#e8830a;
  --accent:#c0392b;
  --green:#27ae60;
  --light:#f4f9fc;
  --dark:#1a1a2e;
  --muted:#6c757d;
}

/* ===== PAGE HERO ===== */
.page-hero{
  background:linear-gradient(135deg,var(--primary-dark) 0%,var(--primary) 60%,#1a7a9a 100%);
  padding:60px 0;
  position:relative;
  overflow:hidden;
}
.page-hero::before{content:'🐟';position:absolute;font-size:250px;opacity:.05;right:-30px;top:-40px;transform:rotate(-20deg);}
.page-hero::after{content:'🥩';position:absolute;font-size:180px;opacity:.05;left:20px;bottom:-30px;transform:rotate(15deg);}
.page-hero h1{font-size:42px;color:#fff;font-weight:800;margin-bottom:14px;}
.breadcrumb{background:transparent;padding:0;margin:0;}
.breadcrumb-item a{color:rgba(255,255,255,.85);}
.breadcrumb-item.active{color:var(--secondary);}
.breadcrumb-item+.breadcrumb-item::before{color:rgba(255,255,255,.4);}

/* ===== À PROPOS PRINCIPAL ===== */
.about-main{padding:90px 0;}
.about-img-frame{
  border-radius:24px;height:480px;
  background:linear-gradient(135deg,var(--primary),#1a8aac);
  display:flex;align-items:center;justify-content:center;font-size:160px;
  position:relative;
}
.about-img-badge{
  position:absolute;bottom:30px;right:-20px;
  background:#fff;border-radius:16px;padding:20px 24px;
  box-shadow:0 10px 40px rgba(0,0,0,.15);text-align:center;min-width:140px;
}
.about-img-badge h3{font-size:36px;color:var(--primary);font-weight:800;margin-bottom:4px;}
.about-img-badge p{font-size:13px;color:var(--muted);font-weight:600;margin:0;}
.about-img-badge2{
  position:absolute;top:30px;left:-20px;
  background:var(--secondary);border-radius:16px;padding:16px 20px;
  box-shadow:0 10px 30px rgba(232,131,10,.35);text-align:center;min-width:130px;
}
.about-img-badge2 h3{font-size:24px;color:#fff;font-weight:800;margin-bottom:2px;}
.about-img-badge2 p{font-size:12px;color:rgba(255,255,255,.85);font-weight:600;margin:0;}
.about-content{padding-left:40px;}
.about-content h2{font-size:40px;font-weight:800;color:var(--dark);line-height:1.2;margin-bottom:20px;}
.about-content h2 span{color:var(--secondary);}
.about-content p{color:var(--muted);font-size:16px;line-height:1.8;margin-bottom:16px;}
.about-list{list-style:none;padding:0;margin:20px 0 30px;}
.about-list li{display:flex;align-items:center;gap:12px;padding:10px 0;border-bottom:1px solid #f0f0f0;font-size:15px;color:var(--dark);font-weight:500;}
.about-list li i{color:var(--green);font-size:16px;}
.btn-about{background:var(--primary);color:#fff;padding:14px 32px;border-radius:50px;font-weight:700;font-size:15px;transition:all .3s;display:inline-flex;align-items:center;gap:8px;}
.btn-about:hover{background:var(--secondary);color:#fff;transform:translateY(-2px);box-shadow:0 8px 25px rgba(232,131,10,.4);}

/* ===== SECTION TITLE ===== */
.sec-label{display:inline-block;background:rgba(10,79,110,.08);color:var(--primary);padding:6px 18px;border-radius:30px;font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:1.5px;margin-bottom:14px;}
.sec-divider{width:50px;height:4px;background:linear-gradient(90deg,var(--primary),var(--secondary));border-radius:2px;margin:16px auto 0;}

/* ===== VALEURS ===== */
.values-section{padding:80px 0;background:var(--light);}
.value-card{background:#fff;border-radius:20px;padding:35px 28px;box-shadow:0 4px 20px rgba(0,0,0,.06);transition:all .35s;border-bottom:4px solid transparent;text-align:center;height:100%;}
.value-card:hover{transform:translateY(-8px);box-shadow:0 16px 50px rgba(0,0,0,.12);border-bottom-color:var(--secondary);}
.value-icon{width:72px;height:72px;border-radius:20px;display:flex;align-items:center;justify-content:center;font-size:30px;margin:0 auto 20px;}
.value-icon-blue{background:rgba(10,79,110,.1);}
.value-icon-orange{background:rgba(232,131,10,.1);}
.value-icon-green{background:rgba(39,174,96,.1);}
.value-card h3{font-size:20px;font-weight:800;color:var(--dark);margin-bottom:12px;}
.value-card p{color:var(--muted);font-size:14px;line-height:1.75;margin:0;}

/* ===== CHIFFRES ===== */
.numbers-section{padding:70px 0;background:linear-gradient(135deg,var(--primary-dark),var(--primary));}
.number-item{text-align:center;padding:30px 20px;border-right:1px solid rgba(255,255,255,.15);}
.number-item:last-child{border-right:none;}
.number-item h3{font-size:48px;color:var(--secondary);font-weight:800;margin-bottom:8px;}
.number-item h4{color:#fff;font-size:15px;font-weight:700;margin-bottom:6px;}
.number-item p{color:rgba(255,255,255,.6);font-size:13px;margin:0;}

/* ===== ÉQUIPE ===== */
.team-section{padding:90px 0;background:#fff;}
.team-card{background:var(--light);border-radius:20px;overflow:hidden;transition:all .35s;text-align:center;height:100%;}
.team-card:hover{transform:translateY(-8px);box-shadow:0 16px 50px rgba(0,0,0,.12);}
.team-avatar{height:220px;background:linear-gradient(135deg,var(--primary),#1a8aac);display:flex;align-items:center;justify-content:center;font-size:90px;}
.team-body{padding:22px 18px;}
.team-body h4{font-weight:800;font-size:17px;color:var(--dark);margin-bottom:4px;}
.team-role{font-size:13px;color:var(--secondary);font-weight:700;text-transform:uppercase;letter-spacing:1px;margin-bottom:12px;display:block;}
.team-body p{font-size:13px;color:var(--muted);line-height:1.6;margin-bottom:16px;}
.team-socials{display:flex;justify-content:center;gap:8px;}
.team-socials a{width:34px;height:34px;background:#fff;border-radius:10px;display:flex;align-items:center;justify-content:center;color:var(--primary);font-size:13px;transition:all .2s;box-shadow:0 2px 8px rgba(0,0,0,.08);}
.team-socials a:hover{background:var(--primary);color:#fff;}

/* ===== INSTAGRAM ===== */
.instagram-section{background:var(--light);padding-bottom:0;}
.insta-grid{display:grid;grid-template-columns:repeat(5,1fr);}
.insta-item{position:relative;overflow:hidden;height:380px;display:flex;align-items:center;justify-content:center;font-size:60px;cursor:pointer;}
.insta-item:nth-child(1){background:linear-gradient(135deg,var(--primary),#1a8aac);}
.insta-item:nth-child(2){background:linear-gradient(135deg,#8B4513,#c0392b);}
.insta-item:nth-child(3){background:linear-gradient(135deg,#2d6a2d,#4caf50);}
.insta-item:nth-child(4){background:linear-gradient(135deg,#6a1f6a,#9b59b6);}
.insta-item:nth-child(5){background:linear-gradient(135deg,#c47c2f,#e8a048);}
.insta-overlay{position:absolute;inset:0;background:rgba(10,79,110,.7);display:flex;align-items:center;justify-content:center;opacity:0;transition:all .3s;}
.insta-item:hover .insta-overlay{opacity:1;}
.insta-overlay i{color:#fff;font-size:28px;}

@media(max-width:991px){
  .about-content{padding-left:0;margin-top:40px;}
  .about-img-badge{right:10px;}
  .about-img-badge2{left:10px;}
  .number-item{border-right:none;border-bottom:1px solid rgba(255,255,255,.15);}
  .number-item:last-child{border-bottom:none;}
  .insta-grid{grid-template-columns:repeat(3,1fr);}
}
</style>
@endsection

@section('content')

{{-- ===== BREADCRUMB HERO ===== --}}
<div class="page-hero">
    <div class="container" style="position:relative;z-index:2;">
        <h1>À Propos de Nous</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                <li class="breadcrumb-item active">À Propos</li>
            </ol>
        </nav>
    </div>
</div>

{{-- ===== SECTION PRINCIPALE ===== --}}
<section class="about-main">
    <div class="container">
        <div class="row align-items-center">

            {{-- Image --}}
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="about-img-frame" style="position:relative;">
                    🐟
                    <div class="about-img-badge">
                        <h3>10+</h3>
                        <p>Ans d'expérience</p>
                    </div>
                    <div class="about-img-badge2">
                        <h3>500+</h3>
                        <p>Clients fidèles</p>
                    </div>
                </div>
            </div>

            {{-- Texte --}}
            <div class="col-lg-6">
                <div class="about-content">
                    <span class="sec-label">Notre Histoire</span>
                    <h2>Nous sommes <span>FreshMarket</span></h2>
                    <div class="sec-divider" style="margin:0 0 22px;"></div>

                    <p>FreshMarket est votre marché protéiné en ligne au Bénin. Fondé avec la passion de rendre les produits frais accessibles à tous, nous sélectionnons chaque matin les meilleurs poissons, viandes, volailles et produits de la mer pour vous les livrer directement chez vous à Cotonou.</p>

                    <p>Notre mission est simple : vous offrir des produits de qualité irréprochable, à des prix justes, avec un service de livraison rapide. Chaque produit est soigneusement choisi auprès de nos fournisseurs locaux et pêcheurs partenaires.</p>

                    <ul class="about-list">
                        <li><i class="fas fa-check-circle"></i> Produits frais sélectionnés chaque matin</li>
                        <li><i class="fas fa-check-circle"></i> Livraison rapide à Cotonou et environs</li>
                        <li><i class="fas fa-check-circle"></i> Fournisseurs locaux certifiés</li>
                        <li><i class="fas fa-check-circle"></i> Paiement sécurisé (Mobile Money, espèces)</li>
                        <li><i class="fas fa-check-circle"></i> Service client disponible 7j/7</li>
                    </ul>

                    <a href="{{ route('shop') }}" class="btn-about">
                        <i class="fas fa-shopping-bag"></i> Découvrir nos produits
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ===== NOS VALEURS ===== --}}
<section class="values-section">
    <div class="container">
        <div class="text-center mb-5">
            <span class="sec-label">Ce qui nous définit</span>
            <h2 class="mt-2" style="font-size:36px;font-weight:800;color:var(--dark);">Nos Valeurs</h2>
            <p class="text-muted" style="max-width:500px;margin:12px auto 0;">Nous construisons notre activité sur des principes solides pour vous servir au mieux</p>
            <div class="sec-divider"></div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="value-card">
                    <div class="value-icon value-icon-blue">🤝</div>
                    <h3>Nous sommes de Confiance</h3>
                    <p>Depuis notre création, nous avons bâti une relation de confiance avec nos clients. Chaque commande est traitée avec sérieux et honnêteté.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="value-card">
                    <div class="value-icon value-icon-orange">⭐</div>
                    <h3>Nous sommes Professionnels</h3>
                    <p>Notre équipe est formée pour vous offrir le meilleur service. De la sélection des produits à la livraison, chaque étape est maîtrisée.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="value-card">
                    <div class="value-icon value-icon-green">🏆</div>
                    <h3>Nous sommes Experts</h3>
                    <p>Avec plus de 10 ans d'expérience, nos experts sélectionnent uniquement ce qu'il y a de meilleur pour votre famille.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===== CHIFFRES CLÉS ===== --}}
<section class="numbers-section">
    <div class="container">
        <div class="row text-center">
            <div class="col-6 col-lg-3">
                <div class="number-item">
                    <h3>500+</h3>
                    <h4>Clients satisfaits</h4>
                    <p>Nous grandissons chaque jour</p>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="number-item">
                    <h3>50+</h3>
                    <h4>Produits disponibles</h4>
                    <p>Viandes, poissons, volailles...</p>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="number-item">
                    <h3>10+</h3>
                    <h4>Années d'expérience</h4>
                    <p>Dans la vente de produits frais</p>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="number-item">
                    <h3>7j/7</h3>
                    <h4>Jours de livraison</h4>
                    <p>Toujours disponibles pour vous</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===== NOTRE ÉQUIPE ===== --}}
<section class="team-section">
    <div class="container">
        <div class="text-center mb-5">
            <span class="sec-label">Les personnes derrière FreshMarket</span>
            <h2 class="mt-2" style="font-size:36px;font-weight:800;color:var(--dark);">Notre Équipe</h2>
            <p class="text-muted" style="max-width:500px;margin:12px auto 0;">Des professionnels passionnés qui travaillent chaque jour pour vous servir</p>
            <div class="sec-divider"></div>
        </div>
        <div class="row">

            @isset($equipe)
                @foreach($equipe as $membre)
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="team-card">
                        <div class="team-avatar">
                            @if($membre->photo)
                                <img src="{{ asset('images/equipe/'.$membre->photo) }}" style="width:100%;height:100%;object-fit:cover;" alt="{{ $membre->nom }}">
                            @else
                               <img src="{{ asset('images/equipe/admin.jpg') }}" 
                                alt="admin"
                                style="width:100%;height:100%;object-fit:cover;">
                            @endif
                        </div>
                        <div class="team-body">
                            <h4>{{ $membre->nom }}</h4>
                            <span class="team-role">{{ $membre->role }}</span>
                            <p>{{ $membre->description }}</p>
                            <div class="team-socials">
                                <a href="#"><i class="fab fa-facebook-f"></i></a>
                                <a href="#"><i class="fab fa-whatsapp"></i></a>
                                <a href="#"><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                {{-- Données statiques par défaut --}}
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="team-card">
                        <div class="team-avatar">
                            <img src="{{ asset('images/equipe/geran.jpg') }}" style="width:100%;height:100%;object-fit:cover;" alt="Gérant">
                        </div>
                        <div class="team-body">
                            <h4>Kofi Mensah</h4>
                            <span class="team-role">Gérant Général</span>
                            <p>Responsable de la gestion globale et de la stratégie de développement.</p>
                            <div class="team-socials">
                                <a href="#"><i class="fab fa-facebook-f"></i></a>
                                <a href="#"><i class="fab fa-whatsapp"></i></a>
                                <a href="#"><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="team-card">
                        <div class="team-avatar">
                            <img src="{{ asset('images/equipe/vendeur.jpg') }}" style="width:100%;height:100%;object-fit:cover;" alt="Vendeur">
                        </div>
                        <div class="team-body">
                            <h4>Adjoua Bello</h4>
                            <span class="team-role">Responsable Produits</span>
                            <p>Sélectionne chaque matin les meilleurs produits auprès de nos fournisseurs.</p>
                            <div class="team-socials">
                                <a href="#"><i class="fab fa-facebook-f"></i></a>
                                <a href="#"><i class="fab fa-whatsapp"></i></a>
                                <a href="#"><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="team-card">
                        <div class="team-avatar">
                            <img src="{{ asset('images/equipe/livraison.jpg') }}" style="width:100%;height:100%;object-fit:cover;" alt="Livraison">
                        </div>
                        <div class="team-body">
                            <h4>Segun Dossou</h4>
                            <span class="team-role">Responsable Livraison</span>
                            <p>Coordonne toutes les livraisons pour garantir que vos commandes arrivent fraîches.</p>
                            <div class="team-socials">
                                <a href="#"><i class="fab fa-facebook-f"></i></a>
                                <a href="#"><i class="fab fa-whatsapp"></i></a>
                                <a href="#"><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="team-card">
                        <div class="team-avatar">
                            <img src="{{ asset('images/equipe/admin.jpg') }}" style="width:100%;height:100%;object-fit:cover;" alt="Admin">
                        </div>
                        <div class="team-body">
                            <h4>Fatou Akplé</h4>
                            <span class="team-role">Service Client</span>
                            <p>Disponible 7j/7 pour répondre à toutes vos questions et assurer votre satisfaction.</p>
                            <div class="team-socials">
                                <a href="#"><i class="fab fa-facebook-f"></i></a>
                                <a href="#"><i class="fab fa-whatsapp"></i></a>
                                <a href="#"><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            @endisset

        </div>
    </div>
</section>

{{-- ===== INSTAGRAM ===== --}}
<section class="instagram-section">
    <div class="container text-center pb-4">
        <h3 style="font-size:28px;font-weight:800;color:var(--dark);">Suivez-nous sur Instagram</h3>
        <p class="text-muted mt-2">@freshmarket.bj — Arrivages du jour, promotions et coulisses</p>
    </div>
    <div class="insta-grid">
        <div class="insta-item"><img src="{{ asset('images/bare/poule.jpg') }}" style="width:100%;height:100%;object-fit:cover;" alt="Poule"><div class="insta-overlay"><i class="fab fa-instagram"></i></div></div>
        <div class="insta-item"><img src="{{ asset('images/bare/poisson.jpg') }}" style="width:100%;height:100%;object-fit:cover;" alt="Poisson"><div class="insta-overlay"><i class="fab fa-instagram"></i></div></div>
        <div class="insta-item"><img src="{{ asset('images/bare/lapin.jpg') }}" style="width:100%;height:100%;object-fit:cover;" alt="Lapin"><div class="insta-overlay"><i class="fab fa-instagram"></i></div></div>
        <div class="insta-item"><img src="{{ asset('images/bare/mouton.jpg') }}" style="width:100%;height:100%;object-fit:cover;" alt="Mouton"><div class="insta-overlay"><i class="fab fa-instagram"></i></div></div>
        <div class="insta-item"><img src="{{ asset('images/bare/boeuf.jpg') }}" style="width:100%;height:100%;object-fit:cover;" alt="Boeuf"><div class="insta-overlay"><i class="fab fa-instagram"></i></div></div>
    </div>
</section>

@endsection
