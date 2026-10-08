<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Masuk Administrator - Yayasan Natural Kapital Indonesia</title>
    
    <!-- Favicon YNKI -->
    <link rel="icon" href="/wp-content/uploads/2026/05/favicon.webp" type="image/webp">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Montserrat:wght@600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --ynki-green: #0F5132;
            --ynki-green-dark: #082d1b;
            --ynki-green-light: #65bd7d;
            --ynki-accent: #198754;
            --ynki-bg-dark: #071f13;
            --ynki-text-dark: #141617;
            --ynki-text-muted: #5a7364;
            --ynki-border: #d4e3db;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: linear-gradient(145deg, #052013 0%, #0d3822 50%, #082818 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            color: var(--ynki-text-dark);
            position: relative;
            overflow: hidden;
        }

        /* Subtle background glow effect */
        body::before {
            content: '';
            position: absolute;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(101, 189, 125, 0.15) 0%, rgba(0, 0, 0, 0) 70%);
            top: -150px;
            right: -150px;
            pointer-events: none;
        }

        body::after {
            content: '';
            position: absolute;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(15, 81, 50, 0.25) 0%, rgba(0, 0, 0, 0) 70%);
            bottom: -150px;
            left: -150px;
            pointer-events: none;
        }

        .login-wrapper {
            width: 100%;
            max-width: 440px;
            position: relative;
            z-index: 10;
        }

        .login-card {
            background: #ffffff;
            border-radius: 20px;
            padding: 42px 36px;
            box-shadow: 0 24px 60px rgba(0, 0, 0, 0.35), 0 0 0 1px rgba(255, 255, 255, 0.1);
        }

        .login-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .login-logo {
            max-width: 220px;
            height: auto;
            margin-bottom: 20px;
            display: inline-block;
        }

        .login-badge {
            display: inline-block;
            background: #eaf5ee;
            color: var(--ynki-green);
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 5px 14px;
            border-radius: 50px;
            margin-bottom: 12px;
            border: 1px solid #cce5d6;
        }

        .login-title {
            font-family: 'Montserrat', 'Inter', sans-serif;
            font-size: 21px;
            font-weight: 800;
            color: var(--ynki-green-dark);
            margin-bottom: 6px;
        }

        .login-subtitle {
            font-size: 13px;
            color: var(--ynki-text-muted);
            line-height: 1.5;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--ynki-text-dark);
            margin-bottom: 8px;
        }

        .form-input {
            width: 100%;
            padding: 13px 16px;
            border: 1.5px solid var(--ynki-border);
            border-radius: 10px;
            font-size: 14px;
            font-family: inherit;
            background: #fdfdfd;
            color: var(--ynki-text-dark);
            transition: all 0.2s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--ynki-green);
            background: #ffffff;
            box-shadow: 0 0 0 3.5px rgba(15, 81, 50, 0.14);
        }

        .form-check {
            display: flex;
            align-items: center;
            gap: 9px;
            font-size: 13px;
            color: var(--ynki-text-muted);
            margin-bottom: 24px;
            cursor: pointer;
        }

        .form-check input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: var(--ynki-green);
            cursor: pointer;
        }

        .btn-submit {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #0F5132 0%, #198754 100%);
            color: #ffffff;
            border: none;
            border-radius: 10px;
            font-size: 14.5px;
            font-weight: 700;
            font-family: 'Montserrat', sans-serif;
            cursor: pointer;
            box-shadow: 0 8px 20px rgba(15, 81, 50, 0.32);
            transition: all 0.2s ease;
        }

        .btn-submit:hover {
            background: linear-gradient(135deg, #093420 0%, #0F5132 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(15, 81, 50, 0.42);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .alert-danger {
            background: #ffebe9;
            border: 1px solid #ffc1bc;
            color: #cf222e;
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 13px;
            margin-bottom: 22px;
            line-height: 1.5;
        }

        .login-footer {
            margin-top: 28px;
            text-align: center;
            font-size: 12px;
            color: var(--ynki-text-muted);
            border-top: 1px solid #edf2ee;
            padding-top: 20px;
        }

        .back-to-site {
            text-align: center;
            margin-top: 18px;
        }

        .back-to-site a {
            color: rgba(255, 255, 255, 0.7);
            font-size: 12.5px;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s ease;
        }

        .back-to-site a:hover {
            color: var(--ynki-green-light);
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="login-card">
            <div class="login-header">
                <!-- Logo Resmi YNKI dari Website Bawaan -->
                <img src="/wp-content/uploads/2026/05/logo-ynki-500.webp?v=3" alt="Yayasan Natural Kapital Indonesia" class="login-logo" onerror="this.onerror=null; this.src='/wp-content/uploads/2026/05/logo-ynki-500.webp?v=3';">
                
                <div>
                    <span class="login-badge">Internal CMS Portal</span>
                </div>
                <h1 class="login-title">Administrator Sign In</h1>
                <p class="login-subtitle">Yayasan Natural Kapital Indonesia</p>
            </div>

            @php
                $effectiveLockout = (int) ($lockout_seconds ?? session('lockout_seconds', 0));
                $isBlocked = $effectiveLockout > 0;
            @endphp

            @if ($isBlocked)
                <div class="alert-danger" id="lockoutBanner" style="text-align: center; font-weight: 600;">
                    <p>Perangkat ini diblokir sementara karena 3x gagal login.</p>
                    <p style="margin-top: 6px; font-size: 15px;">
                        Coba lagi dalam: <span id="countdownTimer" style="color: #b91c1c; font-weight: 800; font-family: monospace; font-size: 17px;">--:--</span>
                    </p>
                </div>
            @elseif ($errors->any())
                <div class="alert-danger">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="email">Alamat Email Administrator</label>
                    <input class="form-input" type="email" id="email" name="email" value="{{ old('email') }}" required {{ $isBlocked ? 'disabled readonly' : 'autofocus' }} style="{{ $isBlocked ? 'background:#f0f0f0;cursor:not-allowed;' : '' }}">
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Kata Sandi</label>
                    <div style="position: relative;">
                        <input class="form-input" style="padding-right: 44px; {{ $isBlocked ? 'background:#f0f0f0;cursor:not-allowed;' : '' }}" type="password" id="password" name="password" required {{ $isBlocked ? 'disabled readonly' : '' }}>
                        <button type="button" id="togglePassword" aria-label="Tampilkan atau sembunyikan kata sandi" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; padding: 4px; display: flex; align-items: center; justify-content: center; color: var(--ynki-text-muted);" {{ $isBlocked ? 'disabled' : '' }}>
                            <svg id="eyeIcon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                        </button>
                    </div>
                </div>

                <label class="form-check" for="remember">
                    <input type="checkbox" id="remember" name="remember">
                    <span>Ingat sesi saya di perangkat ini</span>
                </label>

                <button class="btn-submit" id="btnSubmit" type="submit">Masuk ke Panel Kontrol</button>
            </form>

            <div class="login-footer">
                &copy; {{ date('Y') }} Yayasan Natural Kapital Indonesia.
            </div>
        </div>

        <div class="back-to-site">
            <a href="{{ route('public.home') }}">&larr; Kembali ke Website Utama</a>
        </div>
    </div>

    <script>
        const pwdInput = document.getElementById('password');
        const toggleBtn = document.getElementById('togglePassword');
        const eyeIcon = document.getElementById('eyeIcon');

        if (pwdInput && toggleBtn && eyeIcon) {
            toggleBtn.addEventListener('click', function () {
                const isPassword = pwdInput.type === 'password';
                pwdInput.type = isPassword ? 'text' : 'password';
                eyeIcon.innerHTML = isPassword
                    ? '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line>'
                    : '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>';
            });
        }

        @if ($isBlocked)
        (function() {
            let timeLeft = {{ $effectiveLockout }};
            const countdownEl = document.getElementById('countdownTimer');
            const submitBtn = document.getElementById('btnSubmit');
            const emailInput = document.getElementById('email');
            const pwdInput = document.getElementById('password');
            const toggleBtn = document.getElementById('togglePassword');

            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.style.opacity = '0.6';
                submitBtn.style.cursor = 'not-allowed';
            }

            function updateTimer() {
                const mins = Math.floor(timeLeft / 60);
                const secs = timeLeft % 60;
                if (countdownEl) {
                    countdownEl.textContent = `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
                }
                if (timeLeft <= 0) {
                    clearInterval(timerInterval);
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.style.opacity = '1';
                        submitBtn.style.cursor = 'pointer';
                    }
                    if (emailInput) {
                        emailInput.disabled = false;
                        emailInput.readOnly = false;
                        emailInput.style.background = '#ffffff';
                        emailInput.style.cursor = 'auto';
                    }
                    if (pwdInput) {
                        pwdInput.disabled = false;
                        pwdInput.readOnly = false;
                        pwdInput.style.background = '#ffffff';
                        pwdInput.style.cursor = 'auto';
                    }
                    if (toggleBtn) {
                        toggleBtn.disabled = false;
                    }
                    const banner = document.getElementById('lockoutBanner');
                    if (banner) {
                        banner.style.background = '#eaf5ee';
                        banner.style.borderColor = '#cce5d6';
                        banner.style.color = '#0F5132';
                        banner.innerHTML = '<p>Waktu pemblokiran perangkat telah selesai. Silakan coba masuk kembali.</p>';
                    }
                }
                timeLeft--;
            }

            updateTimer();
            const timerInterval = setInterval(updateTimer, 1000);
        })();
        @endif
    </script>
</body>
</html>
