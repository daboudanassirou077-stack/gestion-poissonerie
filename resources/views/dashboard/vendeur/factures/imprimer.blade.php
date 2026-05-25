<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture #{{ $facture->id_fact }} - FreshMarket</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            font-size: 13px;
            color: #1a1a2e;
            background: #fff;
            padding: 30px;
        }

        /* ── Header ── */
        .facture-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #0a4f6e;
        }
        .brand-logo {
            font-size: 26px;
            font-weight: 900;
            color: #0a4f6e;
            letter-spacing: -1px;
        }
        .brand-logo span { color: #e8830a; }
        .brand-tagline { font-size: 11px; color: #6c757d; margin-top: 4px; }

        .facture-meta { text-align: right; }
        .facture-meta h2 { font-size: 20px; font-weight: 800; color: #0a4f6e; margin-bottom: 6px; }
        .facture-meta p  { font-size: 12px; color: #6c757d; margin-bottom: 3px; }

        /* ── Statut badge ── */
        .statut-badge {
            display: inline-block;
            padding: 5px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            margin-top: 8px;
        }
        .statut-paye    { background: rgba(39,174,96,.15);  color: #1a7a3a; }
        .statut-impayee { background: rgba(192,57,43,.1);   color: #c0392b; }

        /* ── Infos client + facture ── */
        .infos-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 28px;
        }
        .infos-block {
            background: #f4f9fc;
            border-radius: 10px;
            padding: 16px;
        }
        .infos-block h4 {
            font-size: 11px;
            font-weight: 700;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: .8px;
            margin-bottom: 10px;
            border-bottom: 1px solid #e0eaf0;
            padding-bottom: 6px;
        }
        .infos-block p {
            font-size: 13px;
            color: #1a1a2e;
            margin-bottom: 5px;
            font-weight: 600;
        }
        .infos-block p span { color: #6c757d; font-weight: 400; }

        /* ── Tableau produits ── */
        .produits-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .produits-table th {
            background: #0a4f6e;
            color: #fff;
            padding: 10px 14px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .8px;
            text-align: left;
        }
        .produits-table td {
            padding: 11px 14px;
            border-bottom: 1px solid #f0f0f0;
            font-size: 13px;
        }
        .produits-table tr:nth-child(even) td { background: #f9fbfc; }
        .produits-table tfoot td {
            padding: 12px 14px;
            font-weight: 800;
            border-top: 2px solid #0a4f6e;
        }

        /* ── Total ── */
        .total-box {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 28px;
        }
        .total-inner {
            background: #0a4f6e;
            color: #fff;
            border-radius: 12px;
            padding: 16px 24px;
            min-width: 220px;
            text-align: right;
        }
        .total-inner .label { font-size: 12px; font-weight: 600; opacity: .8; margin-bottom: 4px; }
        .total-inner .amount { font-size: 24px; font-weight: 900; }

        /* ── Footer ── */
        .facture-footer {
            border-top: 2px solid #f0f0f0;
            padding-top: 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 11px;
            color: #6c757d;
        }

        /* ── Bouton imprimer (masqué à l'impression) ── */
        .print-actions {
            text-align: center;
            margin-bottom: 30px;
        }
        .btn-print {
            background: #0a4f6e;
            color: #fff;
            border: none;
            padding: 12px 32px;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            margin-right: 10px;
        }
        .btn-back {
            background: #fff;
            color: #0a4f6e;
            border: 2px solid #0a4f6e;
            padding: 12px 32px;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        /* ── Impression ── */
        @media print {
            body { padding: 15px; }
            .print-actions { display: none !important; }
            @page { margin: 1cm; }
        }
    </style>
</head>
<body>

    {{-- Boutons impression (masqués à l'impression) --}}
    <div class="print-actions">
        <button class="btn-print" onclick="window.print()">
            🖨️ Imprimer / Enregistrer en PDF
        </button>
        <a href="{{ route('vendeur.factures.show', $facture->id_fact) }}" class="btn-back">
            ← Retour
        </a>
    </div>

    {{-- En-tête --}}
    <div class="facture-header">
        <div>
            <div class="brand-logo">Fresh<span>Market</span></div>
            <div class="brand-tagline">Poissonnerie & Boucherie — Cotonou, Bénin</div>
            <div style="font-size:11px;color:#6c757d;margin-top:4px;">
                Tél : +229 XX XX XX XX
            </div>
        </div>
        <div class="facture-meta">
            <h2>FACTURE #{{ $facture->id_fact }}</h2>
            <p>Date : {{ \Carbon\Carbon::parse($facture->date_fact)->format('d/m/Y') }}</p>
            <p>Commande : {{ $facture->commande->reference ?? '—' }}</p>
            @php $isPaye = $facture->commande->statut_paiement === 'paye'; @endphp
            <span class="statut-badge {{ $isPaye ? 'statut-paye' : 'statut-impayee' }}">
                {{ $isPaye ? '✅ PAYÉE' : '⏳ EN ATTENTE' }}
            </span>
        </div>
    </div>

    {{-- Infos client + paiement --}}
    <div class="infos-grid">
        <div class="infos-block">
            <h4>📋 Informations client</h4>
            <p>
                {{ $facture->commande->client->prenom_client ?? '—' }}
                {{ $facture->commande->client->nom_client ?? '' }}
            </p>
            <p>📞 <span>{{ $facture->commande->client->telephone ?? '—' }}</span></p>
            <p>📧 <span>{{ $facture->commande->client->email ?? '—' }}</span></p>
            @if($facture->commande->adresse_livraison)
            <p>📍 <span>{{ $facture->commande->adresse_livraison }}</span>
                @if($facture->commande->quartier)
                    — {{ $facture->commande->quartier }}
                @endif
            </p>
            @endif
        </div>
        <div class="infos-block">
            <h4>💳 Informations paiement</h4>
            <p>Mode :
                <span>
                    @if($facture->mode_paie === 'mtn')     📱 MTN MoMo
                    @elseif($facture->mode_paie === 'moov') 📱 Moov Money
                    @elseif($facture->mode_paie === 'carte') 💳 Carte bancaire
                    @else 💵 Espèces
                    @endif
                </span>
            </p>
            @if($facture->commande->momo_numero)
            <p>Numéro : <span>{{ $facture->commande->momo_numero }}</span></p>
            @endif
            <p>Statut : <span>{{ $isPaye ? 'Payée' : 'En attente' }}</span></p>
            <p>Date commande : <span>{{ $facture->commande->date_cmd }}</span></p>
        </div>
    </div>

    {{-- Tableau produits --}}
    <table class="produits-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Désignation</th>
                <th>Catégorie</th>
                <th style="text-align:right;">Prix unitaire</th>
                <th style="text-align:right;">Quantité</th>
                <th style="text-align:right;">Sous-total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($facture->commande->lignes as $i => $ligne)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td><strong>{{ $ligne->produit->libelle_prod ?? '—' }}</strong></td>
                <td style="color:#6c757d;">{{ $ligne->produit->categorie->libelle ?? '—' }}</td>
                <td style="text-align:right;">{{ number_format($ligne->prix_comd, 0, ',', ' ') }} FCFA</td>
                <td style="text-align:right;font-weight:700;">{{ $ligne->quantite_cmder }} kg</td>
                <td style="text-align:right;font-weight:700;color:#0a4f6e;">
                    {{ number_format($ligne->quantite_cmder * $ligne->prix_comd, 0, ',', ' ') }} FCFA
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center;color:#6c757d;padding:20px;">
                    Aucun produit
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Total --}}
    <div class="total-box">
        <div class="total-inner">
            <div class="label">MONTANT TOTAL</div>
            <div class="amount">{{ number_format($facture->montant_fact, 0, ',', ' ') }} FCFA</div>
        </div>
    </div>

    {{-- Instructions livraison --}}
    @if($facture->commande->instructions_livraison)
    <div style="background:#f4f9fc;border-radius:10px;padding:14px;margin-bottom:20px;">
        <strong style="font-size:12px;color:#6c757d;text-transform:uppercase;">
            Instructions de livraison :
        </strong>
        <p style="margin-top:6px;font-size:13px;">{{ $facture->commande->instructions_livraison }}</p>
    </div>
    @endif

    {{-- Footer --}}
    <div class="facture-footer">
        <div>
            <strong>FreshMarket</strong> — Merci pour votre confiance !
        </div>
        <div>
            Facture générée le {{ now()->format('d/m/Y à H:i') }}
        </div>
    </div>

</body>
</html>