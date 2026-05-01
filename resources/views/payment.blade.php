@extends('layouts.app')

@section('title', 'Paiement - FreshMarket')

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

    .payment-wrapper {
        min-height: 60vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--light-bg);
        padding: 40px 0;
    }

    .payment-card {
        background: #fff;
        border-radius: 16px;
        padding: 40px 30px;
        box-shadow: 0 3px 15px rgba(0,0,0,.07);
        text-align: center;
        max-width: 500px;
        width: 100%;
    }

    .payment-icon {
        font-size: 60px;
        margin-bottom: 20px;
    }

    .payment-title {
        font-size: 24px;
        font-weight: 800;
        color: var(--dark);
        margin-bottom: 10px;
    }

    .payment-desc {
        font-size: 16px;
        color: var(--muted);
        line-height: 1.6;
        margin-bottom: 30px;
    }

    .payment-amount {
        font-size: 32px;
        font-weight: 800;
        color: var(--primary);
        margin-bottom: 30px;
    }

    .btn-payment {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 16px 32px;
        border: none;
        border-radius: 12px;
        font-size: 18px;
        font-weight: 800;
        cursor: pointer;
        background: linear-gradient(135deg, var(--green) 0%, #2ecc71 100%);
        color: #fff;
        transition: all .3s;
        text-decoration: none;
    }

    .btn-payment:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(39,174,96,.4);
    }

    .btn-back {
        display: inline-block;
        margin-top: 20px;
        color: var(--primary);
        text-decoration: none;
        font-weight: 600;
    }
</style>
@endsection

@section('content')

<div class="payment-wrapper">
    <div class="payment-card">
        <div class="payment-icon">💳</div>
        <h1 class="payment-title">Paiement sécurisé</h1>
        <p class="payment-desc">
            Vous allez être redirigé vers Kkiapay pour effectuer votre paiement en toute sécurité.
            Acceptez MTN MoMo, Moov Money et cartes bancaires.
        </p>
        <div class="payment-amount">{{ number_format($commande->montant_total, 0, ',', ' ') }} FCFA</div>
        <button class="btn-payment" onclick="openPayment()">
            <i class="fas fa-credit-card"></i>
            Payer maintenant
        </button>
        <br>
        <a href="{{ route('cart.checkout') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Retour à la commande
        </a>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.kkiapay.me/k.js"></script>
<script>
    function openPayment() {
        if (typeof openKkiapayWidget !== 'function') {
            alert('Impossible de charger Kkiapay. Vérifiez votre connexion ou réessayez plus tard.');
            return;
        }

        openKkiapayWidget({
            amount: {{ $commande->montant_total }},
            position: "center",
            key: "{{ config('services.kkiapay.public_key') }}",
            sandbox: {{ config('services.kkiapay.sandbox') ? 'true' : 'false' }},
            paymentMethods: ["momo", "card"],
            name: "{{ trim($commande->client->prenom_client.' '.$commande->client->nom_client) }}",
            phoneNumber: "{{ $commande->client->telephone }}",
            data: {
                prenom: "{{ $commande->client->prenom_client }}",
                nom: "{{ $commande->client->nom_client }}",
                email: "{{ $commande->client->email_client }}",
                telephone: "{{ $commande->client->telephone }}",
                quartier: "{{ $commande->quartier }}",
                adresse: "{{ $commande->adresse_livraison }}",
                instructions: "{{ $commande->instructions_livraison ?? '' }}",
            }
        });
    }

    window.addSuccessListener(function(response) {
        if (response && response.transactionId) {
            window.location.href = "{{ route('cart.payment') }}?transaction_id=" + encodeURIComponent(response.transactionId);
        } else {
            window.location.href = "{{ route('cart.checkout') }}";
        }
    });

    window.addFailedListener(function() {
        alert('Le paiement a échoué. Veuillez réessayer.');
    });

    window.addPendingListener(function() {
        console.log('Paiement en attente...');
    });
</script>
@endsection