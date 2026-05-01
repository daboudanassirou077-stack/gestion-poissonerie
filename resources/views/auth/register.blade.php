<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inscription - FreshMarket</title>
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
            padding: 30px 15px;
        }
        .auth-card {
            background: #fff;
            border-radius: 20px;
            padding: 40px 35px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 500px;
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
        .auth-logo p { font-size: 14px; color: #999; }
        .form-label {
            font-size: 12px;
            font-weight: 700;
            color: var(--primary);
            text-transform: uppercase;
            letter-spacing: .8px;
            margin-bottom: 6px;
        }
        .form-label em { color: #c0392b; font-style: normal; }
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
        .btn-register {
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
        .btn-register:hover {
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
        .btn-login {
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
        .btn-login:hover { background: var(--primary); color: #fff; }
        .alert-danger-fm {
            background: rgba(192,57,43,.08);
            border: 1.5px solid rgba(192,57,43,.25);
            color: #c0392b;
            border-radius: 10px;
            padding: 12px 15px;
            font-size: 13px;
            margin-bottom: 18px;
        }
        .field-error {
            font-size: 12px;
            color: #c0392b;
            margin-top: 4px;
        }
        .password-strength {
            height: 4px;
            border-radius: 2px;
            margin-top: 6px;
            transition: all .3s;
            background: #e0e0e0;
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
            <p>Créez votre compte en quelques secondes</p>
        </div>

        {{-- Alertes --}}
        @if($errors->any())
            <div class="alert-danger-fm">
                <i class="fas fa-exclamation-circle"></i>
                {{ $errors->first() }}
            </div>
        @endif

        {{-- Formulaire --}}
        <form action="{{ route('register.post') }}" method="POST">
            @csrf

            {{-- Nom & Prénom --}}
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nom <em>*</em></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user text-muted"></i></span>
                        <input
                            type="text"
                            name="nom"
                            class="form-control @error('nom') is-invalid @enderror"
                            placeholder="Mensah"
                            value="{{ old('nom') }}"
                            required
                        >
                    </div>
                    @error('nom')<p class="field-error">{{ $message }}</p>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Prénom <em>*</em></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user text-muted"></i></span>
                        <input
                            type="text"
                            name="prenom"
                            class="form-control @error('prenom') is-invalid @enderror"
                            placeholder="Adjoua"
                            value="{{ old('prenom') }}"
                            required
                        >
                    </div>
                    @error('prenom')<p class="field-error">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Email --}}
            <div class="mb-3">
                <label class="form-label">Email <em>*</em></label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-envelope text-muted"></i></span>
                    <input
                        type="email"
                        name="email"
                        class="form-control @error('email') is-invalid @enderror"
                        placeholder="votre@email.com"
                        value="{{ old('email') }}"
                        required
                    >
                </div>
                @error('email')<p class="field-error">{{ $message }}</p>@enderror
            </div>

            {{-- Téléphone --}}
            <div class="mb-3">
                <label class="form-label">Téléphone <em>*</em></label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-phone text-muted"></i></span>
                    <input
                        type="tel"
                        name="telephone"
                        class="form-control @error('telephone') is-invalid @enderror"
                        placeholder="+229 00 000 000"
                        value="{{ old('telephone') }}"
                        required
                    >
                </div>
                @error('telephone')<p class="field-error">{{ $message }}</p>@enderror
            </div>

            {{-- Adresse --}}
            <div class="mb-3">
                <label class="form-label">Adresse <em>*</em></label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-map-marker-alt text-muted"></i></span>
                    <input
                        type="text"
                        name="adresse"
                        class="form-control @error('adresse') is-invalid @enderror"
                        placeholder="Quartier, rue... Cotonou"
                        value="{{ old('adresse') }}"
                        required
                    >
                </div>
                @error('adresse')<p class="field-error">{{ $message }}</p>@enderror
            </div>

            {{-- Mot de passe --}}
            <div class="mb-3">
                <label class="form-label">Mot de passe <em>*</em></label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock text-muted"></i></span>
                    <input
                        type="password"
                        name="mdp"
                        id="mdp"
                        class="form-control @error('mdp') is-invalid @enderror"
                        placeholder="Minimum 6 caractères"
                        required
                        oninput="checkStrength(this.value)"
                    >
                </div>
                <div class="password-strength" id="strengthBar"></div>
                @error('mdp')<p class="field-error">{{ $message }}</p>@enderror
            </div>

            {{-- Confirmation mot de passe --}}
            <div class="mb-4">
                <label class="form-label">Confirmer le mot de passe <em>*</em></label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock text-muted"></i></span>
                    <input
                        type="password"
                        name="mdp_confirmation"
                        class="form-control"
                        placeholder="Répétez le mot de passe"
                        required
                    >
                </div>
            </div>

            <button type="submit" class="btn-register">
                <i class="fas fa-user-plus"></i> Créer mon compte
            </button>
        </form>

        <div class="divider">déjà inscrit ?</div>

        <a href="{{ route('login') }}" class="btn-login">
            <i class="fas fa-sign-in-alt"></i> Se connecter
        </a>

        <div class="back-home">
            <a href="{{ route('home') }}">
                <i class="fas fa-arrow-left"></i> Retour à l'accueil
            </a>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function checkStrength(val) {
            const bar = document.getElementById('strengthBar');
            if (val.length === 0) {
                bar.style.width = '0%';
                bar.style.background = '#e0e0e0';
            } else if (val.length < 4) {
                bar.style.width = '25%';
                bar.style.background = '#c0392b';
            } else if (val.length < 6) {
                bar.style.width = '50%';
                bar.style.background = '#e8830a';
            } else if (val.length < 10) {
                bar.style.width = '75%';
                bar.style.background = '#f1c40f';
            } else {
                bar.style.width = '100%';
                bar.style.background = '#27ae60';
            }
        }
    </script>
</body>
</html>