<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion - FreshMarket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --primary: #0a4f6e;
            --secondary: #e8830a;
        }
        body {
            background: linear-gradient(135deg, #0a4f6e 0%, #1a7a9a 50%, #e8830a 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
        }
        .auth-card {
            background: #fff;
            border-radius: 20px;
            padding: 40px 35px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 420px;
        }
        .auth-logo {
            text-align: center;
            margin-bottom: 28px;
        }
        .auth-logo h2 {
            font-size: 28px;
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 4px;
        }
        .auth-logo p {
            font-size: 14px;
            color: #999;
        }
        .form-label {
            font-size: 12px;
            font-weight: 700;
            color: var(--primary);
            text-transform: uppercase;
            letter-spacing: .8px;
            margin-bottom: 6px;
        }
        .form-control {
            padding: 12px 15px;
            border: 1.5px solid #e5e5e5;
            border-radius: 10px;
            font-size: 14px;
            transition: border-color .3s;
        }
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(10,79,110,.08);
        }
        .input-group-text {
            border: 1.5px solid #e5e5e5;
            border-right: none;
            background: #f8f9fa;
            border-radius: 10px 0 0 10px;
        }
        .input-group .form-control {
            border-left: none;
            border-radius: 0 10px 10px 0;
        }
        .btn-login {
            background: var(--primary);
            color: #fff;
            border: none;
            padding: 13px;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 700;
            width: 100%;
            transition: all .3s;
            margin-top: 6px;
        }
        .btn-login:hover {
            background: var(--secondary);
            transform: translateY(-2px);
        }
        .divider {
            text-align: center;
            margin: 20px 0;
            position: relative;
            color: #bbb;
            font-size: 13px;
        }
        .divider::before, .divider::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 42%;
            height: 1px;
            background: #e5e5e5;
        }
        .divider::before { left: 0; }
        .divider::after  { right: 0; }
        .btn-register {
            background: transparent;
            color: var(--primary);
            border: 2px solid var(--primary);
            padding: 11px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 700;
            width: 100%;
            transition: all .3s;
            text-decoration: none;
            display: block;
            text-align: center;
        }
        .btn-register:hover {
            background: var(--primary);
            color: #fff;
        }
        .alert-danger-fm {
            background: rgba(192,57,43,.08);
            border: 1.5px solid rgba(192,57,43,.25);
            color: #c0392b;
            border-radius: 10px;
            padding: 12px 15px;
            font-size: 13px;
            margin-bottom: 18px;
        }
        .alert-success-fm {
            background: rgba(39,174,96,.08);
            border: 1.5px solid rgba(39,174,96,.25);
            color: #27ae60;
            border-radius: 10px;
            padding: 12px 15px;
            font-size: 13px;
            margin-bottom: 18px;
        }
        .back-home {
            text-align: center;
            margin-top: 20px;
        }
        .back-home a {
            font-size: 13px;
            color: #999;
            text-decoration: none;
        }
        .back-home a:hover { color: var(--primary); }
    </style>
</head>
<body>
    <div class="auth-card">

        {{-- Logo --}}
        <div class="auth-logo">
            <h2>🐟 FreshMarket</h2>
            <p>Connectez-vous à votre compte</p>
        </div>

        {{-- Alertes --}}
        @if(session('success'))
            <div class="alert-success-fm">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert-danger-fm">
                <i class="fas fa-exclamation-circle"></i>
                {{ $errors->first() }}
            </div>
        @endif

        {{-- Formulaire --}}
        <form action="{{ route('login.post') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Email</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-envelope text-muted"></i></span>
                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        placeholder="votre@email.com"
                        value="{{ old('email') }}"
                        required
                    >
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Mot de passe</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock text-muted"></i></span>
                    <input
                        type="password"
                        name="mdp"
                        class="form-control"
                        placeholder="••••••••"
                        required
                    >
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember" style="font-size:13px;color:#666;">
                        Se souvenir de moi
                    </label>
                </div>
                <a href="#" style="font-size:13px;color:var(--primary);text-decoration:none;">
                    Mot de passe oublié ?
                </a>
            </div>

            <button type="submit" class="btn-login">
                <i class="fas fa-sign-in-alt"></i> Se connecter
            </button>
        </form>

        <div class="divider">ou</div>

        <a href="{{ route('register') }}" class="btn-register">
            <i class="fas fa-user-plus"></i> Créer un compte
        </a>

        <div class="back-home">
            <a href="{{ route('home') }}">
                <i class="fas fa-arrow-left"></i> Retour à l'accueil
            </a>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>