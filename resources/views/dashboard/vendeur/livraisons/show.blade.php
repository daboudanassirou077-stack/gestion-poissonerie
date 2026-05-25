@extends('layouts.dashboard')

@section('title', 'Livraison - FreshMarket')

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
    .dash-avatar { width:60px; height:60px; background:linear-gradient(135deg,#e8830a,#f0a83a); border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:24px; font-weight:800; color:#fff; border:3px solid rgba(255,255,255,.3); flex-shrink:0; }

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

    .info-grid { display:grid; grid-template-columns:1fr 1fr; gap:16px; padding:22px; }
    .info-block { background:var(--light-bg); border-radius:12px; padding:16px; }
    .info-block label { font-size:11px; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:.8px; display:block; margin-bottom:6px; }
    .info-block span { font-size:14px; font-weight:700; color:var(--dark); }

    .admin-table { width:100%; border-collapse:collapse; }
    .admin-table th { background:var(--light-bg); padding:11px 16px; font-size:11px; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:.8px; text-align:left; }
    .admin-table td { padding:13px 16px; border-bottom:1px solid #f5f5f5; font-size:14px; color:var(--dark); vertical-align:middle; }
    .admin-table tr:last-child td { border-bottom:none; }

    .status-badge { display:inline-flex; align-items:center; gap:5px; padding:4px 10px; border-radius:20px; font-size:11px; font-weight:700; }
    .status-en_attente  { background:rgba(232,131,10,.12); color:#a05c00; }
    .status-en_cours    { background:rgba(39,174,96,.1);   color:var(--green); }
    .status-livree      { background:rgba(39,174,96,.15);  color:#1a7a3a; }
    .status-en_attente-cmd  { background:rgba(232,131,10,.12); color:#a05c00; }
    .status-confirmee       { background:rgba(10,79,110,.1);   color:var(--primary); }
    .status-en_preparation  { background:rgba(155,89,182,.1);  color:#6c3483; }
    .status-en_livraison    { background:rgba(39,174,96,.1);   color:var(--green); }
    .status-livree-cmd      { background:rgba(39,174,96,.15);  color:#1a7a3a; }

    .btn-sm-fm { padding:8px 18px; border-radius:8px; font-size:13px; font-weight:700; border:none; cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; gap:6px; transition:all .2s; }
    .btn-primary-fm { background:var(--primary); color:#fff; }
    .btn-primary-fm:hover { background:#083d56; color:#fff; }
    .btn-success-fm { background:var(--green); color:#fff; }
    .btn-success-fm:hover { background:#1e8449; color:#fff; }
    .btn-outline-fm { background:#fff; color:var(--primary); border:2px solid var(--primary); }
    .btn-outline-fm:hover { background:var(--primary); color:#fff; }

    .alert-fm { border-radius:10px; padding:12px 16px; font-size:14px; font-weight:600; margin-bottom:20px; display:flex; align-items:center; gap:8px; }
    .alert-success-fm { background:rgba(39,174,96,.1); border:1.5px solid rgba(39,174,96,.25); color:#1e7e44; }

    /* Livreur card */
    .livreur-card { background:var(--light-bg); border-radius:12px; padding:16px; display:flex; align-items:center; gap:14px; margin:22px; }
    .livreur-avatar { width:48px; height:48px; border-radius:50%; background:linear-gradient(135deg,#27ae60,#2ecc71); display:flex; align-items:center; justify-content:center; font-size:18px; font-weight:800; color:#fff; flex-shrink:0; }

    @media(max-width:768px) { .info-grid { grid-template-columns:1fr; } }
</style>
@endsection

@section('content')

<div class="dash-header">
    <div class="container">
        <div class="d-flex align-items-center gap-3">
            <div class="dash-avatar">{{ strtoupper(substr(auth()->user()->prenom, 0, 1)) }}</div>
            <div>
                <h1>Détail livraison 🚚</h1>
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

        <div class="row">

            {{-- Sidebar --}}
            <div class="col-lg-3 mb-4">
                <div class="dash-menu">
                    <div class="dash-menu-title">Ventes</div>
                    <a href="{{ route('vendeur.dashboard') }}" class="dash-menu-item">
                        <i class="fas fa-tachometer-alt"></i> Tableau de bord
                    </a>
                    <a href="{{ route('vendeur.produits') }}" class="dash-menu-item">
                        <i class="fas fa-search"></i> Recherche produits
                    </a>
                    <a href="{{ route('vendeur.commandes') }}" class="dash-menu-item">
                        <i class="fas fa-shopping-bag"></i> Commandes
                    </a>
                    <a href="{{ route('vendeur.livraisons') }}" class="dash-menu-item active">
                        <i class="fas fa-truck"></i> Livraisons
                    </a>
                    <div class="dash-menu-title" style="margin-top:4px;">Gestion</div>
                    <a href="{{ route('vendeur.clients') }}" class="dash-menu-item">
                        <i class="fas fa-users"></i> Clients
                    </a>
                    <a href="{{ route('vendeur.factures') }}" class="dash-menu-item">
                        <i class="fas fa-file-invoice"></i> Factures
                    </a>
                    <div class="dash-menu-title" style="margin-top:4px;">Système</div>
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

                {{-- Actions --}}
                <div class="section-card">
                    <div class="section-card-header"><h5>⚡ Actions</h5></div>
                    <div style="padding:16px;display:flex;flex-direction:column;gap:10px;">
                        @if($livraison->statut !== 'livree')
                        <form action="{{ route('vendeur.livraisons.livrer', $livraison->id_livcl) }}" method="POST">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn-sm-fm btn-success-fm w-100" style="justify-content:center;"
                                onclick="return confirm('Confirmer la livraison ?')">
                                <i class="fas fa-check-double"></i> Marquer comme livrée
                            </button>
                        </form>
                        @else
                        <div style="text-align:center;padding:10px;background:rgba(39,174,96,.1);border-radius:10px;color:var(--green);font-weight:700;">
                            ✅ Livraison effectuée
                        </div>
                        @endif
                        <a href="{{ route('vendeur.commandes.show', $livraison->commande->id_cmd) }}" class="btn-sm-fm btn-primary-fm w-100" style="justify-content:center;">
                            <i class="fas fa-shopping-bag"></i> Voir la commande
                        </a>
                        <a href="{{ route('vendeur.livraisons') }}" class="btn-sm-fm btn-outline-fm w-100" style="justify-content:center;">
                            <i class="fas fa-arrow-left"></i> Retour aux livraisons
                        </a>
                    </div>
                </div>
            </div>

            {{-- Contenu --}}
            <div class="col-lg-9">

                {{-- Statut livraison --}}
                <div class="section-card">
                    <div class="section-card-header">
                        <h5>📍 Statut de la livraison</h5>
                        <span class="status-badge status-{{ $livraison->statut }}">
                            @if($livraison->statut === 'en_attente') ⏳ En attente
                            @elseif($livraison->statut === 'en_cours') 🚚 En cours
                            @else ✅ Livrée
                            @endif
                        </span>
                    </div>

                    {{-- Timeline --}}
                    @php
                        $etapes = ['en_attente', 'en_cours', 'livree'];
                        $etapeLabels = ['En attente', 'En cours', 'Livrée'];
                        $etapeIcons  = ['⏳', '🚚', '✅'];
                        $currentIndex = array_search($livraison->statut, $etapes);
                    @endphp
                    <div style="display:flex;align-items:center;padding:24px 30px;gap:0;">
                        @foreach($etapes as $i => $etape)
                        @php
                            $isDone    = $currentIndex !== false && $i < $currentIndex;
                            $isCurrent = $currentIndex !== false && $i === $currentIndex;
                        @endphp
                        <div style="display:flex;flex-direction:column;align-items:center;flex:1;position:relative;">
                            @if($i < count($etapes) - 1)
                            <div style="position:absolute;top:16px;left:50%;width:100%;height:3px;background:{{ $isDone ? '#27ae60' : '#e0eaf0' }};z-index:0;"></div>
                            @endif
                            <div style="width:32px;height:32px;border-radius:50%;border:3px solid {{ $isCurrent ? '#0a4f6e' : ($isDone ? '#27ae60' : '#e0eaf0') }};background:{{ $isCurrent ? '#0a4f6e' : ($isDone ? '#27ae60' : '#fff') }};display:flex;align-items:center;justify-content:center;font-size:14px;position:relative;z-index:1;">
                                {{ $etapeIcons[$i] }}
                            </div>
                            <div style="font-size:11px;font-weight:700;color:{{ $isCurrent ? '#0a4f6e' : ($isDone ? '#27ae60' : '#6c757d') }};margin-top:6px;text-align:center;">
                                {{ $etapeLabels[$i] }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Infos livraison --}}
                <div class="section-card">
                    <div class="section-card-header">
                        <h5>🚚 Informations de livraison</h5>
                    </div>
                    <div class="info-grid">
                        <div class="info-block">
                            <label>Date prévue</label>
                            <span>{{ \Carbon\Carbon::parse($livraison->date_livcl)->format('d/m/Y') }}</span>
                        </div>
                        <div class="info-block">
                            <label>Statut</label>
                            <span class="status-badge status-{{ $livraison->statut }}">
                                @if($livraison->statut === 'en_attente') ⏳ En attente
                                @elseif($livraison->statut === 'en_cours') 🚚 En cours
                                @else ✅ Livrée
                                @endif
                            </span>
                        </div>
                        <div class="info-block" style="grid-column:1/-1;">
                            <label>Adresse de livraison</label>
                            <span>📍 {{ $livraison->adresse_livcl }}</span>
                        </div>
                    </div>
                </div>

                {{-- Livreur --}}
                @if($livraison->livreur)
                <div class="section-card">
                    <div class="section-card-header">
                        <h5>🧑 Livreur assigné</h5>
                    </div>
                    <div class="livreur-card">
                        <div class="livreur-avatar">
                            {{ strtoupper(substr($livraison->livreur->prenom ?? '?', 0, 1)) }}
                        </div>
                        <div>
                            <div style="font-size:15px;font-weight:800;color:var(--dark);">
                                {{ $livraison->livreur->prenom ?? '—' }}
                                {{ $livraison->livreur->nom ?? '' }}
                            </div>
                            <div style="font-size:13px;color:var(--muted);margin-top:3px;">
                                📞 {{ $livraison->livreur->telephone ?? '—' }}
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Commande associée --}}
                <div class="section-card">
                    <div class="section-card-header">
                        <h5>📦 Commande associée</h5>
                        <a href="{{ route('vendeur.commandes.show', $livraison->commande->id_cmd) }}" class="btn-sm-fm btn-primary-fm">
                            Voir détail <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                    <div class="info-grid">
                        <div class="info-block">
                            <label>Référence</label>
                            <span>{{ $livraison->commande->reference ?? '—' }}</span>
                        </div>
                        <div class="info-block">
                            <label>Montant</label>
                            <span style="color:var(--primary);">
                                {{ number_format($livraison->commande->montant_total ?? 0, 0, ',', ' ') }} FCFA
                            </span>
                        </div>
                        <div class="info-block">
                            <label>Client</label>
                            <span>
                                {{ $livraison->commande->client->prenom_client ?? '—' }}
                                {{ $livraison->commande->client->nom_client ?? '' }}
                            </span>
                        </div>
                        <div class="info-block">
                            <label>Téléphone client</label>
                            <span>{{ $livraison->commande->client->telephone ?? '—' }}</span>
                        </div>
                    </div>

                    {{-- Produits de la commande --}}
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Produit</th>
                                <th>Quantité</th>
                                <th>Prix unitaire</th>
                                <th>Sous-total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $icons = ['Poissons frais'=>'🐟','Poissons fumés'=>'🔥','Viandes de bœuf'=>'🥩','Volailles'=>'🍗','Escargots'=>'🐌','Abats'=>'🫀'];
                            @endphp
                            @forelse($livraison->commande->produits as $produit)
                            @php $libCat = $produit->categorie->libelle ?? ''; @endphp
                            <tr>
                                <td>
                                    <div style="display:flex;align-items:center;gap:8px;">
                                        <span style="font-size:18px;">{{ $icons[$libCat] ?? '📦' }}</span>
                                        <strong>{{ $produit->libelle_prod ?? '—' }}</strong>
                                    </div>
                                </td>
                                <td><strong>{{ $produit->pivot->quantite_cmder }} kg</strong></td>
                                <td>{{ number_format($produit->pivot->prix_comd, 0, ',', ' ') }} FCFA</td>
                                <td style="color:var(--primary);font-weight:800;">
                                    {{ number_format($produit->pivot->quantite_cmder * $produit->pivot->prix_comd, 0, ',', ' ') }} FCFA
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" style="text-align:center;color:var(--muted);padding:20px;">
                                    Aucun produit
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr style="background:var(--light-bg);">
                                <td colspan="3" style="padding:12px 16px;font-weight:800;">Total</td>
                                <td style="padding:12px 16px;font-weight:800;color:var(--primary);">
                                    {{ number_format($livraison->commande->montant_total ?? 0, 0, ',', ' ') }} FCFA
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection