<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bienvenue sur FreshMarket</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f4f9fc;
            padding: 30px 15px;
        }
        .email-wrapper {
            max-width: 580px;
            margin: 0 auto;
            background: #fff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,.08);
        }
        .email-header {
            background: linear-gradient(135deg, #0a4f6e, #1a7a9a);
            padding: 40px 30px;
            text-align: center;
            color: #fff;
        }
        .email-header h1 {
            font-size: 28px;
            font-weight: 800;
            margin-bottom: 6px;
        }
        .email-header p {
            font-size: 15px;
            opacity: .85;
        }
        .email-body {
            padding: 35px 30px;
        }
        .greeting {
            font-size: 20px;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 14px;
        }
        .email-body p {
            font-size: 15px;
            color: #555;
            line-height: 1.7;
            margin-bottom: 14px;
        }
        .credentials-box {
            background: #f4f9fc;
            border: 2px solid #0a4f6e;
            border-radius: 14px;
            padding: 22px 24px;
            margin: 24px 0;
        }
        .credentials-box h3 {
            font-size: 14px;
            font-weight: 700;
            color: #0a4f6e;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 16px;
        }
        .credential-row {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 0;
            border-bottom: 1px solid #e0eaf0;
        }
        .credential-row:last-child {
            border-bottom: none;
        }
        .credential-label {
            font-size: 13px;
            color: #888;
            font-weight: 600;
            min-width: 120px;
        }
        .credential-value {
            font-size: 15px;
            font-weight: 800;
            color: #1a1a2e;
        }
        .credential-value.password {
            background: #0a4f6e;
            color: #fff;
            padding: 4px 14px;
            border-radius: 8px;
            letter-spacing: 2px;
        }
        .warning-box {
            background: rgba(232,131,10,.08);
            border: 1.5px solid rgba(232,131,10,.3);
            border-radius: 10px;
            padding: 14px 16px;
            font-size: 13px;
            color: #a05c00;
            margin: 20px 0;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }
        .btn-login {
            display: block;
            background: linear-gradient(135deg, #0a4f6e, #1a7a9a);
            color: #fff !important;
            text-decoration: none;
            text-align: center;
            padding: 15px 30px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 700;
            margin: 24px 0;
        }
        .features {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin: 20px 0;
        }
        .feature-item {
            background: #f4f9fc;
            border-radius: 10px;
            padding: 14px;
            text-align: center;
        }
        .feature-item .icon { font-size: 24px; margin-bottom: 6px; }
        .feature-item p {
            font-size: 12px;
            color: #666;
            margin: 0;
            line-height: 1.4;
        }
        .email-footer {
            background: #1a1a2e;
            padding: 25px 30px;
            text-align: center;
            color: rgba(255,255,255,.6);
            font-size: 13px;
        }
        .email-footer a {
            color: #e8830a;
            text-decoration: none;
        }
        .social-links {
            margin: 12px 0;
            display: flex;
            justify-content: center;
            gap: 12px;
        }
        .social-links a {
            color: rgba(255,255,255,.6);
            font-size: 18px;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">

        {{-- Header --}}
        <div class="email-header">
            <h1>🐟 FreshMarket</h1>
            <p>Votre marché protéiné en ligne au Bénin</p>
        </div>

        {{-- Body --}}
        <div class="email-body">

            <p class="greeting">
                Bienvenue {{ $user->prenom }} {{ $user->nom }} ! 🎉
            </p>

            <p>
                Votre compte FreshMarket a été créé avec succès.
                Vous pouvez dès maintenant commander nos produits frais
                et vous faire livrer directement chez vous à Cotonou.
            </p>

            {{-- Identifiants --}}
            <div class="credentials-box">
                <h3>🔐 Vos identifiants de connexion</h3>
                <div class="credential-row">
                    <span class="credential-label">📧 Email</span>
                    <span class="credential-value">{{ $user->email }}</span>
                </div>
                <div class="credential-row">
                    <span class="credential-label">🔑 Mot de passe</span>
                    <span class="credential-value password">{{ $motDePasse }}</span>
                </div>
            </div>

            {{-- Avertissement --}}
            <div class="warning-box">
                ⚠️ <span>Pour votre sécurité, nous vous recommandons de changer votre mot de passe après votre première connexion.</span>
            </div>

            {{-- Bouton connexion --}}
            <a href="{{ url('/login') }}" class="btn-login">
                🚀 Accéder à mon compte
            </a>

            {{-- Fonctionnalités --}}
            <p style="font-weight:700;color:#1a1a2e;margin-bottom:14px;">
                Ce que vous pouvez faire sur FreshMarket :
            </p>
            <div class="features">
                <div class="feature-item">
                    <div class="icon">🐟</div>
                    <p>Commander des poissons frais</p>
                </div>
                <div class="feature-item">
                    <div class="icon">🥩</div>
                    <p>Viandes de qualité</p>
                </div>
                <div class="feature-item">
                    <div class="icon">🚚</div>
                    <p>Livraison rapide à Cotonou</p>
                </div>
                <div class="feature-item">
                    <div class="icon">📱</div>
                    <p>Paiement Mobile Money</p>
                </div>
            </div>

            <p style="color:#888;font-size:13px;">
                Des questions ? Contactez-nous sur WhatsApp :
                <strong style="color:#0a4f6e;">+229 00 000 000</strong>
            </p>

        </div>

        {{-- Footer --}}
        <div class="email-footer">
            <p>© 2024 FreshMarket Bénin — Tous droits réservés</p>
            <p style="margin-top:6px;">
                <a href="{{ url('/') }}">Accueil</a> ·
                <a href="{{ url('/contact') }}">Contact</a> ·
                <a href="{{ url('/shop') }}">Boutique</a>
            </p>
            <p style="margin-top:10px;font-size:11px;">
                Vous recevez cet email car vous venez de créer un compte sur FreshMarket.
            </p>
        </div>

    </div>
</body>
</html>