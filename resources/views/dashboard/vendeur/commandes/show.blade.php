@extends('layouts.dashboard')

@section('title', 'Commande '.$commande->reference.' - FreshMarket')

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

    /* Sidebar */
    .dash-menu { background:#fff; border-radius:16px; box-shadow:0 2px 12px rgba(0,0,0,.06); overflow:hidden; margin-bottom:24px; }
    .dash-menu-title { padding:14px 20px; font-size:11px; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:1.2px; border-bottom:1px solid #f0f0f0; background:var(--light-bg); }
    .dash-menu-item { display:flex; align-items:center; gap:12px; padding:13px 20px; border-bottom:1px solid #f5f5f5; text-decoration:none; color:var(--dark); font-size:14px; font-weight:600; transition:all .2s; }
    .dash-menu-item:last-child { border-bottom:none; }
    .dash-menu-item:hover { background:var(--light-bg); color:var(--primary); padding-left:26px; }
    .dash-menu-item.active { background:rgba(10,79,110,.06); color:var(--primary); border-left:3px solid var(--primary); }
    .dash-menu-item i { width:18px; text-align:center; color:var(--primary); }

    /* Cards */
    .section-card { background:#fff; border-radius:16px; box-shadow:0 2px 12px rgba(0,0,0,.06); margin-bottom:24px; overflow:hidden; }
    .section-card-header { padding:16px 22px; border-bottom:2px solid var(--light-bg); display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px; }
    .section-card-header h5 { font-size:15px; font-weight:800; color:var(--dark); margin:0; }

    /* Info grid */
    .info-grid { display:grid; grid-template-columns:1fr 1fr; gap:20px; padding:22px; }
    .info-block { background:var(--light-bg); border-radius:12px; padding:16px; }
    .info-block label { font-size:11px; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:.8px; display:block; margin-bottom:6px; }
    .info-block span { font-size:14px; font-weight:700; color:var(--dark); }

    /* Table lignes */
    .admin-table { width:100%; border-collapse:collapse; }
    .admin-table th { background:var(--light-bg); padding:11px 16px; font-size:11px; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:.8px; text-align:left; }
    .admin-table td { padding:13px 16px; border-bottom:1px solid #f5f5f5; font-size:14px; color:var(--dark); vertical-align:middle; }
    .admin-table tr:last-child td { border-bottom:none; }

    /* Badges */
    .status-badge { display:inline-flex; align-items:center; gap:5px; padding:4px 10px; border-radius:20px; font-size:11px; font-weight:700; }
    .status-en_attente     { background:rgba(232,131,10,.12); color:#a05c00; }
    .status-confirmee      { background:rgba(10,79,110,.1);   color:var(--primary); }
    .status-en_preparation { background:rgba(155,89,182,.1);  color:#6c3483; }
    .status-en_livraison   { background:rgba(39,174,96,.1);   color:var(--green); }
    .status-livree         { background:rgba(39,174,96,.15);  color:#1a7a3a; }
    .status-annulee        { background:rgba(192,57,43,.1);   color:var(--accent); }

    /* Boutons */
    .btn-sm-fm { padding:8px 18px; border-radius:8px; font-size:13px; font-weight:700; border:none; cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; gap:6px; transition:all .2s; }
    .btn-primary-fm { background:var(--primary); color:#fff; }
    .btn-primary-fm:hover { background:#083d56; color:#fff; }
    .btn-success-fm { background:var(--green); color:#fff; }
    .btn-success-fm:hover { background:#1e8449; color:#fff; }
    .btn-warning-fm { background:var(--secondary); color:#fff; }
    .btn-outline-fm { background:#fff; color:var(--primary); border:2px solid var(--primary); }
    .btn-outline-fm:hover { background:var(--primary); color:#fff; }

    /* Timeline statut */
    .statut-timeline { display:flex; align-items:center; padding:20px 22px; gap:0; overflow-x:auto; }
    .statut-step { display:flex; flex-direction:column; align-items:center; flex:1; min-width:80px; position:relative; }
    .statut-step:not(:last-child)::after { content:''; position:absolute; top:16px; left:50%; width:100%; height:3px; background:#e0eaf0; z-index:0; }
    .statut-step.done:not(:last-child)::after { background:var(--green); }
    .statut-dot { width:32px; height:32px; border-radius:50%; border:3px solid #e0eaf0; background:#fff; display:flex; align-items:center; justify-content:center; font-size:14px; position:relative; z-index:1; }
    .statut-step.done .statut-dot { border-color:var(--green); background:var(--green); color:#fff; }
    .statut-step.current .statut-dot { border-color:var(--primary); background:var(--primary); color:#fff; }
    .statut-label { font-size:11px; font-weight:700; color:var(--muted); margin-top:6px; text-align:center; }
    .statut-step.done .statut-label { color:var(--green); }
    .statut-step.current .statut-label { color:var(--primary); }

    /* Alert */
    .alert-fm { border-radius:10px; padding:12px 16px; font-size:14px; font-weight:600; margin-bottom:20px; display:flex; align-items:center; gap:8px; }
    .alert-success-fm { background:rgba(39,174,96,.1); border:1.5px solid rgba(39,174,96,.25); color:#1e7e44; }

    @media(max-width:768px) { .info-grid { grid-template-columns:1fr; } }
</style>
@endsection

@section('content')

<div class="dash-header">
    <div class="container">
        <div class="d-flex align-items-center gap-3">
            <div class="dash-avatar">{{ strtoupper(substr(auth()->user()->prenom, 0, 1)) }}</div>
            <div>
                <h1>Commande #{{ $commande->reference }} 📦</h1>
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
                    <a href="{{ route('vendeur.commandes') }}" class="dash-menu-item active">
                        <i class="fas fa-shopping-bag"></i> Commandes
                    </a>
                    <a href="{{ route('vendeur.livraisons') }}" class="dash-menu-item">
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

                {{-- Actions rapides --}}
                <div class="section-card">
                    <div class="section-card-header"><h5>⚡ Actions</h5></div>
                    <div style="padding:16px;display:flex;flex-direction:column;gap:10px;">
                        @if($commande->statut_cmd === 'en_attente')
                        <form action="{{ route('vendeur.commandes.confirmer', $commande->id_cmd) }}" method="POST">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn-sm-fm btn-success-fm w-100" style="justify-content:center;">
                                <i class="fas fa-check"></i> Confirmer la commande
                            </button>
                        </form>
                        @endif
                        @if($commande->statut_cmd === 'confirmee')
                        <form action="{{ route('vendeur.commandes.preparer', $commande->id_cmd) }}" method="POST">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn-sm-fm btn-warning-fm w-100" style="justify-content:center;">
                                <i class="fas fa-box-open"></i> Mettre en préparation
                            </button>
                        </form>
                        @endif
                        @if($commande->statut_cmd === 'en_preparation')
                            {{-- Formulaire assignation livreur --}}
                            <div style="background:var(--light-bg);border-radius:12px;padding:16px;margin-bottom:10px;">
                                <div style="font-size:13px;font-weight:700;color:var(--dark);margin-bottom:12px;">
                                    🚚 Assigner un livreur
                                </div>
                                <form action="{{ route('vendeur.commandes.assigner-livreur', $commande->id_cmd) }}" method="POST">
                                    @csrf @method('POST')
                                    <div class="mb-2">
                                        <select name="id_livreur" class="form-select form-select-sm" required>
                                            <option value="">Choisir un livreur</option>
                                            @foreach($livreurs as $livreur)
                                            <option value="{{ $livreur->id_livreur }}">
                                                {{ $livreur->prenom }} {{ $livreur->nom }} — {{ $livreur->telephone }}
                                            </option>
                                            @endforeach
                                        </select>
                                            </div>
                                            <div class="mb-2">
                                                <input type="date" name="date_livcl" class="form-control form-control-sm"
                                                    value="{{ now()->addDay()->toDateString() }}" required>
                                            </div>
                                            <div class="mb-2">
                                                <input type="text" name="adresse_livcl" class="form-control form-control-sm"
                                                    placeholder="Adresse de livraison"
                                                    value="{{ $commande->adresse_livraison }}" required>
                                           </div>
                                    <button type="submit" class="btn-sm-fm btn-success-fm w-100" style="justify-content:center;">
                                        <i class="fas fa-truck"></i> Envoyer en livraison
                                    </button>
                                </form>
                            </div>
                        @endif
                        <a href="{{ route('vendeur.commandes') }}" class="btn-sm-fm btn-outline-fm w-100" style="justify-content:center;">
                            <i class="fas fa-arrow-left"></i> Retour aux commandes
                        </a>
                        @if($commande->facture)
                        <a href="{{ route('vendeur.factures.show', $commande->facture->id_fact) }}" class="btn-sm-fm btn-primary-fm w-100" style="justify-content:center;">
                            <i class="fas fa-file-invoice"></i> Voir la facture
                        </a>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Contenu --}}
            <div class="col-lg-9">

                {{-- Timeline statut --}}
                <div class="section-card">
                    <div class="section-card-header">
                        <h5>📍 Suivi de la commande</h5>
                        <span class="status-badge status-{{ $commande->statut_cmd }}">
                            {{ ucfirst(str_replace('_', ' ', $commande->statut_cmd)) }}
                        </span>
                    </div>
                    @php
                        $etapes = ['en_attente','confirmee','en_preparation','en_livraison','livree'];
                        $currentIndex = array_search($commande->statut_cmd, $etapes);
                        $etapeLabels  = ['En attente','Confirmée','Préparation','Livraison','Livrée'];
                        $etapeIcons   = ['⏳','✅','📦','🚚','🎉'];
                    @endphp
                    <div class="statut-timeline">
                        @foreach($etapes as $i => $etape)
                        @php
                            $isDone    = $currentIndex !== false && $i < $currentIndex;
                            $isCurrent = $currentIndex !== false && $i === $currentIndex;
                        @endphp
                        <div class="statut-step {{ $isDone ? 'done' : ($isCurrent ? 'current' : '') }}">
                            <div class="statut-dot">{{ $etapeIcons[$i] }}</div>
                            <div class="statut-label">{{ $etapeLabels[$i] }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Infos commande + client --}}
                <div class="section-card">
                    <div class="section-card-header">
                        <h5>📋 Informations de la commande</h5>
                        <span style="font-size:13px;color:var(--muted);">{{ $commande->created_at->format('d/m/Y à H:i') }}</span>
                    </div>
                    <div class="info-grid">
                        <div class="info-block">
                            <label>Référence</label>
                            <span>{{ $commande->reference }}</span>
                        </div>
                        <div class="info-block">
                            <label>Date commande</label>
                            <span>{{ $commande->date_cmd }}</span>
                        </div>
                        <div class="info-block">
                            <label>Client</label>
                            <span>{{ $commande->client->prenom_client ?? '—' }} {{ $commande->client->nom_client ?? '' }}</span>
                        </div>
                        <div class="info-block">
                            <label>Téléphone</label>
                            <span>{{ $commande->client->telephone ?? '—' }}</span>
                        </div>
                        <div class="info-block">
                            <label>Adresse livraison</label>
                            <span>{{ $commande->adresse_livraison ?? '—' }}</span>
                        </div>
                        <div class="info-block">
                            <label>Quartier</label>
                            <span>{{ $commande->quartier ?? '—' }}</span>
                        </div>
                        <div class="info-block">
                            <label>Mode de paiement</label>
                            <span>
                                @if($commande->momo_operateur === 'mtn') 📱 MTN MoMo
                                @elseif($commande->momo_operateur === 'moov') 📱 Moov Money
                                @elseif($commande->momo_operateur === 'especes') 💵 Espèces
                                @else —
                                @endif
                            </span>
                        </div>
                        <div class="info-block">
                            <label>Statut paiement</label>
                            <span class="status-badge {{ $commande->statut_paiement === 'paye' ? 'status-livree' : 'status-en_attente' }}">
                                {{ $commande->statut_paiement === 'paye' ? '✅ Payé' : '⏳ En attente' }}
                            </span>
                        </div>
                        @if($commande->instructions_livraison)
                        <div class="info-block" style="grid-column:1/-1;">
                            <label>Instructions livraison</label>
                            <span>{{ $commande->instructions_livraison }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Lignes de commande --}}
                <div class="section-card">
                    <div class="section-card-header">
                        <h5>🛒 Produits commandés</h5>
                        <span style="font-size:13px;color:var(--muted);">{{ $commande->produits->count() }} article(s)</span>
                    </div>
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Produit</th>
                                <th>Catégorie</th>
                                <th>Prix unitaire</th>
                                <th>Quantité</th>
                                <th>Sous-total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($commande->produits as $ligne)
                            @php
                                $icons = ['Poissons frais'=>'🐟','Poissons fumés'=>'🔥','Viandes de bœuf'=>'🥩','Volailles'=>'🍗','Escargots'=>'🐌','Abats'=>'🫀'];
                                $libCat = $ligne->categorie->libelle ?? '';
                            @endphp
                            <tr>
                                <td>
                                    <div style="display:flex;align-items:center;gap:8px;">
                                        <span style="font-size:20px;">{{ $icons[$libCat] ?? '📦' }}</span>
                                        <strong>{{ $ligne->libelle_prod ?? '—' }}</strong>
                                    </div>
                                </td>
                                <td style="color:var(--muted);font-size:13px;">{{ $libCat ?: '—' }}</td>
                                <td>{{ number_format($ligne->pivot->prix_comd, 0, ',', ' ') }} FCFA</td>
                                <td><strong>{{ $ligne->pivot->quantite_cmder }} kg</strong></td>
                                <td>
                                    <strong style="color:var(--primary);">
                                        {{ number_format($ligne->pivot->quantite_cmder * $ligne->pivot->prix_comd, 0, ',', ' ') }} FCFA
                                    </strong>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" style="text-align:center;color:var(--muted);padding:30px;">
                                    Aucun produit trouvé
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr style="background:var(--light-bg);">
                                <td colspan="4" style="padding:14px 16px;font-weight:800;font-size:15px;">Total</td>
                                <td style="padding:14px 16px;font-weight:800;font-size:16px;color:var(--primary);">
                                    {{ number_format($commande->montant_total, 0, ',', ' ') }} FCFA
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                {{-- Livraison associée --}}
                @if($commande->livraison)
                <div class="section-card">
                    <div class="section-card-header">
                        <h5>🚚 Livraison associée</h5>
                        <a href="{{ route('vendeur.livraisons.show', $commande->livraison->id_livcl) }}" class="btn-sm-fm btn-primary-fm">
                            Voir détail <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                    <div class="info-grid">
                        <div class="info-block">
                            <label>Date livraison prévue</label>
                            <span>{{ $commande->livraison->date_livcl }}</span>
                        </div>
                        <div class="info-block">
                            <label>Statut livraison</label>
                            <span class="status-badge status-{{ $commande->livraison->statut === 'livree' ? 'livree' : 'en_livraison' }}">
                                {{ ucfirst($commande->livraison->statut) }}
                            </span>
                        </div>
                        <div class="info-block" style="grid-column:1/-1;">
                            <label>Adresse</label>
                            <span>{{ $commande->livraison->adresse_livcl }}</span>
                        </div>
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>
</div>

@endsection