<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Laravel') }}</title>
        <style>
            :root {
                --bg: #eef2ff;
                --card: #ffffff;
                --muted: #64748b;
                --text: #0f172a;
                --line: #dbe2ea;
                --brand: #4f46e5;
            }

            * {
                box-sizing: border-box;
            }

            body {
                margin: 0;
                min-height: 100vh;
                font-family: Figtree, Arial, sans-serif;
                background:
                    radial-gradient(circle at 15% 20%, rgba(99, 102, 241, 0.25), transparent 35%),
                    radial-gradient(circle at 85% 80%, rgba(56, 189, 248, 0.2), transparent 35%),
                    linear-gradient(135deg, #e8edff 0%, #f8f9ff 100%);
                color: var(--text);
                display: grid;
                place-items: center;
                padding: 24px;
            }

            .login-shell {
                width: 100%;
                max-width: 980px;
                background: var(--card);
                border-radius: 18px;
                box-shadow: 0 20px 45px rgba(15, 23, 42, 0.12);
                overflow: hidden;
                display: grid;
                grid-template-columns: 1fr 1fr;
                position: relative;
                border: 1px solid rgba(255, 255, 255, 0.6);
                backdrop-filter: blur(6px);
                transition: transform 0.35s ease, box-shadow 0.35s ease;
                animation: cardFadeIn 0.7s ease-out both;
            }

            .login-shell::before {
                content: "";
                position: absolute;
                inset: 0;
                background: linear-gradient(130deg, rgba(255, 255, 255, 0.45) 0%, rgba(255, 255, 255, 0.05) 40%, rgba(255, 255, 255, 0.35) 100%);
                pointer-events: none;
            }

            .login-shell:hover {
                transform: translateY(-4px);
                box-shadow: 0 28px 55px rgba(15, 23, 42, 0.18);
            }

            .panel-left {
                padding: 42px 40px;
                background:
                    radial-gradient(circle at top left, rgba(99, 102, 241, 0.08), transparent 45%),
                    linear-gradient(180deg, rgba(255, 255, 255, 0.96) 0%, rgba(248, 250, 255, 0.96) 100%);
                position: relative;
            }

            .panel-right {
                padding: 42px 40px;
                background:
                    radial-gradient(circle at 20% 15%, rgba(255, 255, 255, 0.55), transparent 35%),
                    radial-gradient(circle at 80% 85%, rgba(56, 189, 248, 0.2), transparent 35%),
                    linear-gradient(160deg, #e6ebff 0%, #dbe4ff 45%, #d8ecff 100%);
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                text-align: center;
                position: relative;
                border-left: 1px solid rgba(255, 255, 255, 0.7);
            }

            .panel-right::before {
                content: "";
                position: absolute;
                width: 320px;
                height: 320px;
                border-radius: 50%;
                background: radial-gradient(circle, rgba(99, 102, 241, 0.32) 0%, rgba(56, 189, 248, 0.1) 45%, transparent 70%);
                filter: blur(8px);
                animation: glowFloat 5s ease-in-out infinite;
                z-index: 0;
            }

            .panel-right::after {
                content: "";
                position: absolute;
                inset: 18px;
                border-radius: 14px;
                border: 1px solid rgba(255, 255, 255, 0.45);
                pointer-events: none;
            }

            .title {
                margin: 0;
                font-size: 34px;
                line-height: 1.15;
                font-weight: 700;
            }

            .subtitle {
                margin: 10px 0 0;
                color: var(--muted);
                font-size: 14px;
            }

            .status-box {
                margin-top: 18px;
                border: 1px solid #bfdbfe;
                background: #eff6ff;
                color: #1d4ed8;
                border-radius: 10px;
                padding: 10px 12px;
                font-size: 13px;
            }

            .form {
                margin-top: 22px;
            }

            .field {
                margin-top: 14px;
            }

            label {
                display: block;
                font-size: 13px;
                font-weight: 600;
                margin-bottom: 7px;
            }

            .input {
                width: 100%;
                border: 1px solid var(--line);
                border-radius: 10px;
                padding: 11px 12px;
                font-size: 15px;
                outline: none;
                transition: border-color 0.2s, box-shadow 0.2s;
                background: #fff;
            }

            .input:focus {
                border-color: var(--brand);
                box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.12);
            }

            .error {
                margin-top: 6px;
                color: #dc2626;
                font-size: 12px;
            }

            .row {
                margin-top: 16px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 10px;
            }

            .remember {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                font-size: 13px;
                color: #334155;
            }

            .remember input {
                width: 15px;
                height: 15px;
            }

            .link {
                color: var(--brand);
                text-decoration: none;
                font-size: 13px;
                font-weight: 600;
            }

            .link:hover {
                text-decoration: underline;
            }

            .btn {
                margin-top: 18px;
                width: 100%;
                border: 0;
                border-radius: 10px;
                background: linear-gradient(135deg, #5b4dff 0%, #4f46e5 55%, #4338ca 100%);
                color: #fff;
                font-size: 15px;
                font-weight: 700;
                padding: 12px;
                cursor: pointer;
                box-shadow: 0 10px 18px rgba(79, 70, 229, 0.28);
            }

            .btn:hover {
                background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
            }

            .signup {
                margin-top: 18px;
                text-align: center;
                font-size: 14px;
                color: #475569;
            }

            .company-logo {
                max-width: 240px;
                width: 100%;
                height: auto;
                border-radius: 8px;
                box-shadow: 0 14px 24px rgba(15, 23, 42, 0.18);
                border: 1px solid rgba(255, 255, 255, 0.7);
                position: relative;
                z-index: 1;
            }

            .company-name {
                margin: 16px 0 0;
                font-size: 30px;
                font-weight: 700;
                position: relative;
                z-index: 1;
            }

            .company-note {
                margin: 10px 0 0;
                max-width: 320px;
                color: #475569;
                font-size: 14px;
                line-height: 1.6;
                position: relative;
                z-index: 1;
            }

            @keyframes cardFadeIn {
                from {
                    opacity: 0;
                    transform: translateY(14px) scale(0.99);
                }
                to {
                    opacity: 1;
                    transform: translateY(0) scale(1);
                }
            }

            @keyframes glowFloat {
                0%,
                100% {
                    transform: translateY(0) translateX(0);
                }
                50% {
                    transform: translateY(-10px) translateX(6px);
                }
            }

            @media (max-width: 900px) {
                .login-shell {
                    grid-template-columns: 1fr;
                }

                .panel-right {
                    border-top: 1px solid var(--line);
                }

                .title {
                    font-size: 30px;
                }
            }
        </style>
    </head>
    <body>
        <main class="login-shell">
            <section class="panel-left">
                <h2 class="title">Login to your account</h2>
                <p class="subtitle">Welcome back! Enter your details to log in.</p>

                @if (session('status'))
                    <div class="status-box">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="form">
                    @csrf

                    <div class="field">
                        <label for="email">Email</label>
                        <input id="email" class="input" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
                        @error('email')
                            <p class="error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="password">Password</label>
                        <input id="password" class="input" type="password" name="password" required autocomplete="current-password">
                        @error('password')
                            <p class="error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="row">
                        <label for="remember_me" class="remember">
                            <input id="remember_me" name="remember" type="checkbox">
                            Remember me
                        </label>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="link">Forgot password?</a>
                        @endif
                    </div>

                    <button type="submit" class="btn">Log in</button>
                </form>

                <p class="signup">
                    New here?
                    <a href="{{ route('register') }}" class="link">Create account</a>
                </p>
            </section>

            <section class="panel-right">
                <img src="{{ asset('images/midland.jpg') }}" alt="Company Logo" class="company-logo">
                <h1 class="company-name">Midland Valley Homes</h1>
                <p class="company-note">Trusted property management platform for your daily operations.</p>
            </section>
        </main>
    </body>
</html>
