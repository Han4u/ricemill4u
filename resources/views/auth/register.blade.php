<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar — Rice Mill App</title>
    <meta name="description" content="Daftar akun baru di Rice Mill App — Sistem Monitoring Pertanian Terpadu">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Serif+Display&display=swap" rel="stylesheet">

    <!-- Iconify (Heroicons) -->
    <script src="https://code.iconify.design/3/3.1.0/iconify.min.js"></script>

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --primary:       #1a5c38;
            --primary-light: #2d7a50;
            --primary-dark:  #0e3d24;
            --accent:        #e8b84b;
            --bg-card:       #ffffff;
            --text-main:     #1c2b1e;
            --text-muted:    #6b7c6e;
            --border:        #dde5de;
            --error:         #dc3545;
            --shadow-soft:   0 8px 32px rgba(26, 92, 56, .08);
            --shadow-hover:  0 12px 40px rgba(26, 92, 56, .14);
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 50%, #d1fae5 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            position: relative;
            overflow-x: hidden;
        }

        /* Decorative background circles */
        body::before {
            content: '';
            position: fixed;
            top: -180px; right: -120px;
            width: 500px; height: 500px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(26,92,56,.06), rgba(232,184,75,.04));
            z-index: 0;
        }
        body::after {
            content: '';
            position: fixed;
            bottom: -200px; left: -100px;
            width: 450px; height: 450px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(232,184,75,.06), rgba(26,92,56,.03));
            z-index: 0;
        }

        .auth-wrapper {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 520px;
        }

        /* Logo / Brand */
        .brand-header {
            text-align: center;
            margin-bottom: 28px;
        }

        .brand-icon {
            width: 56px; height: 56px;
            background: var(--primary);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
            box-shadow: 0 4px 16px rgba(26, 92, 56, .2);
        }

        .brand-icon i, .brand-icon svg { color: #fff; width: 28px; height: 28px; }

        .brand-name {
            font-family: 'DM Sans', sans-serif;
            font-size: 1.65rem;
            font-weight: 700;
            color: var(--primary);
            letter-spacing: .01em;
        }

        .brand-sub {
            font-size: .82rem;
            color: var(--text-muted);
            margin-top: 4px;
            letter-spacing: .02em;
        }

        /* Card */
        .auth-card {
            background: var(--bg-card);
            border-radius: 20px;
            padding: 36px 36px 32px;
            box-shadow: var(--shadow-soft);
            border: 1px solid var(--border);
            transition: box-shadow .3s ease;
        }

        .auth-card:hover {
            box-shadow: var(--shadow-hover);
        }

        .auth-card-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--text-main);
            text-align: center;
            margin-bottom: 4px;
        }

        .auth-card-subtitle {
            font-size: .84rem;
            color: var(--text-muted);
            text-align: center;
            margin-bottom: 24px;
        }

        /* Form groups */
        .form-group {
            margin-bottom: 18px;
        }

        .form-group label.field-label {
            display: block;
            font-size: .83rem;
            font-weight: 500;
            color: var(--text-main);
            margin-bottom: 7px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            width: 18px; height: 18px;
            color: var(--text-muted);
            pointer-events: none;
        }

        .input-wrapper input {
            width: 100%;
            padding: 12px 14px 12px 44px;
            border: 1.5px solid var(--border);
            border-radius: 12px;
            font-size: .9rem;
            font-family: 'DM Sans', sans-serif;
            color: var(--text-main);
            background: #fff;
            transition: border .2s, box-shadow .2s;
            outline: none;
        }

        .input-wrapper input::placeholder {
            color: #a8b5ab;
        }

        .input-wrapper input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(26, 92, 56, .1);
        }

        .input-wrapper input.is-invalid {
            border-color: var(--error);
        }

        .input-wrapper input.is-invalid:focus {
            box-shadow: 0 0 0 3px rgba(220, 53, 69, .1);
        }

        .toggle-password {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: var(--text-muted);
            padding: 4px;
            display: flex;
            align-items: center;
        }

        .toggle-password:hover { color: var(--primary); }
        .toggle-password i { width: 18px; height: 18px; }

        .error-text {
            display: block;
            font-size: .76rem;
            color: var(--error);
            margin-top: 5px;
            padding-left: 2px;
        }

        /* ===== Role Selection ===== */
        .role-section-label {
            font-size: .83rem;
            font-weight: 500;
            color: var(--text-main);
            margin-bottom: 10px;
        }

        .role-options {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-bottom: 22px;
        }

        .role-option {
            position: relative;
        }

        .role-option input[type="radio"] {
            position: absolute;
            opacity: 0;
            width: 0; height: 0;
        }

        .role-option .role-label {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 16px;
            border: 1.5px solid var(--border);
            border-radius: 14px;
            cursor: pointer;
            transition: all .22s ease;
            background: #fff;
        }

        .role-option .role-label:hover {
            border-color: var(--primary-light);
            background: #f8fbf9;
        }

        .role-option input[type="radio"]:checked + .role-label {
            border-color: var(--primary);
            background: #edf7f1;
            box-shadow: 0 0 0 3px rgba(26, 92, 56, .08);
        }

        .role-icon-box {
            width: 40px; height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            background: #f0f4f1;
            transition: all .22s ease;
        }

        .role-icon-box i {
            width: 20px; height: 20px;
            color: var(--text-muted);
            transition: color .22s ease;
        }

        .role-option input[type="radio"]:checked + .role-label .role-icon-box {
            background: var(--primary);
        }

        .role-option input[type="radio"]:checked + .role-label .role-icon-box i {
            color: #fff;
        }

        .role-info { flex: 1; }

        .role-info .role-name {
            font-size: .88rem;
            font-weight: 600;
            color: var(--text-main);
            line-height: 1.2;
        }

        .role-info .role-desc {
            font-size: .76rem;
            color: var(--text-muted);
            margin-top: 2px;
        }

        .role-check {
            width: 22px; height: 22px;
            border-radius: 50%;
            border: 2px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: all .22s ease;
        }

        .role-check i { width: 14px; height: 14px; color: #fff; display: none; }

        .role-option input[type="radio"]:checked + .role-label .role-check {
            background: var(--primary);
            border-color: var(--primary);
        }

        .role-option input[type="radio"]:checked + .role-label .role-check i {
            display: block;
        }

        /* Submit */
        .btn-submit {
            width: 100%;
            padding: 13px;
            background: var(--primary);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-size: .95rem;
            font-weight: 600;
            font-family: 'DM Sans', sans-serif;
            cursor: pointer;
            transition: all .22s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-submit:hover {
            background: var(--primary-light);
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(26, 92, 56, .25);
        }

        .btn-submit:active { transform: translateY(0); }
        .btn-submit i { width: 18px; height: 18px; }

        /* Divider */
        .auth-divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 22px 0;
        }

        .auth-divider::before,
        .auth-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border);
        }

        .auth-divider span {
            font-size: .78rem;
            color: var(--text-muted);
            white-space: nowrap;
        }

        /* Footer */
        .auth-footer {
            text-align: center;
            margin-top: 24px;
            font-size: .86rem;
            color: var(--text-muted);
        }

        .auth-footer a {
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
        }

        .auth-footer a:hover { text-decoration: underline; }

        /* Alert */
        .alert-danger-custom {
            background: #fde8e8;
            border: 1px solid #f5b8b8;
            color: #8b1a1a;
            padding: 12px 16px;
            border-radius: 12px;
            font-size: .84rem;
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .alert-danger-custom i { width: 16px; height: 16px; flex-shrink: 0; }

        /* Password strength indicator */
        .password-strength {
            height: 4px;
            background: #eee;
            border-radius: 2px;
            margin-top: 8px;
            overflow: hidden;
        }

        .password-strength .bar {
            height: 100%;
            width: 0;
            border-radius: 2px;
            transition: width .3s, background .3s;
        }

        .password-hint {
            font-size: .74rem;
            color: var(--text-muted);
            margin-top: 4px;
        }

        /* Responsive */
        @media (max-width: 560px) {
            .auth-card { padding: 28px 22px 24px; }
            body { padding: 16px; }
        }

        /* Entrance animation */
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .auth-wrapper { animation: slideUp .5s ease-out; }
    </style>
</head>
<body>

<div class="auth-wrapper">

    <!-- Brand -->
    <div class="brand-header">
        <div class="brand-icon">
            <span class="iconify" data-icon="ph:plant" style="color:#fff; width:28px; height:28px;"></span>
        </div>
        <div class="brand-name">SiMonTani</div>
        <div class="brand-sub">Bergabunglah dengan ekosistem pertanian digital</div>
    </div>

    <!-- Register Card -->
    <div class="auth-card" id="register-card">
        <h2 class="auth-card-title">Buat akun SiMonTani</h2>
        <p class="auth-card-subtitle">Gratis untuk petani, rice mill, dan packager.</p>

        {{-- Validation Error Summary --}}
        @if($errors->any())
            <div class="alert-danger-custom">
                <span class="iconify" data-icon="heroicons:exclamation-circle"></span>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" id="register-form">
            @csrf

            <!-- Nama -->
            <div class="form-group">
                <label class="field-label" for="name-input">Nama lengkap</label>
                <div class="input-wrapper">
                    <span class="iconify input-icon" data-icon="heroicons:user"></span>
                    <input id="name-input"
                           type="text"
                           name="name"
                           value="{{ old('name') }}"
                           required
                           class="{{ $errors->has('name') ? 'is-invalid' : '' }}"
                           placeholder="Contoh: Budi Santoso">
                </div>
                @error('name')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <!-- Email -->
            <div class="form-group">
                <label class="field-label" for="email-input">Email</label>
                <div class="input-wrapper">
                    <span class="iconify input-icon" data-icon="heroicons:envelope"></span>
                    <input id="email-input"
                           type="email"
                           name="email"
                           value="{{ old('email') }}"
                           required
                           class="{{ $errors->has('email') ? 'is-invalid' : '' }}"
                           placeholder="nama@simontani.id">
                </div>
                @error('email')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <!-- Role Selection (Dropdown) -->
            <div class="form-group">
                <label class="field-label" for="role-input">Peran</label>
                <div class="input-wrapper">
                    <span class="iconify input-icon" data-icon="heroicons:shield-check"></span>
                    <select id="role-input"
                            name="role"
                            required
                            class="{{ $errors->has('role') ? 'is-invalid' : '' }}"
                            style="width: 100%; padding: 12px 14px 12px 44px; border: 1.5px solid var(--border); border-radius: 12px; font-size: .9rem; font-family: 'DM Sans', sans-serif; color: var(--text-main); background: #fff; appearance: none; outline: none; transition: border .2s, box-shadow .2s; cursor: pointer;">
                        <option value="" disabled {{ old('role') ? '' : 'selected' }}>Pilih peran Anda</option>
                        <option value="petani" {{ old('role') === 'petani' ? 'selected' : '' }}>Petani</option>
                        <option value="rice_mill" {{ old('role') === 'rice_mill' ? 'selected' : '' }}>Rice Mill</option>
                        <option value="packager" {{ old('role') === 'packager' ? 'selected' : '' }}>Packager</option>
                    </select>
                    <!-- Custom Dropdown Arrow -->
                    <span class="iconify" data-icon="heroicons:chevron-down" style="position: absolute; right: 14px; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; color: var(--text-muted); pointer-events: none;"></span>
                </div>
                @error('role')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <!-- Password -->
            <div class="form-group">
                <label class="field-label" for="password-input">Password</label>
                <div class="input-wrapper">
                    <span class="iconify input-icon" data-icon="heroicons:lock-closed"></span>
                    <input id="password-input"
                           type="password"
                           name="password"
                           required
                           class="{{ $errors->has('password') ? 'is-invalid' : '' }}"
                           placeholder="Minimal 8 karakter">
                    <button type="button" class="toggle-password" onclick="togglePasswordVisibility('password-input', this)">
                        <span class="iconify" data-icon="heroicons:eye"></span>
                    </button>
                </div>
                @error('password')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <!-- Password Confirmation -->
            <div class="form-group">
                <label class="field-label" for="password-confirm-input">Konfirmasi password</label>
                <div class="input-wrapper">
                    <span class="iconify input-icon" data-icon="heroicons:lock-closed"></span>
                    <input id="password-confirm-input"
                           type="password"
                           name="password_confirmation"
                           required
                           placeholder="Ulangi password">
                    <button type="button" class="toggle-password" onclick="togglePasswordVisibility('password-confirm-input', this)">
                        <span class="iconify" data-icon="heroicons:eye"></span>
                    </button>
                </div>
            </div>

            <!-- Term and Privacy Checkbox -->
            <div class="form-group" style="margin-top: 10px; margin-bottom: 24px;">
                <label style="display: flex; align-items: flex-start; gap: 8px; font-size: .84rem; color: var(--text-muted); cursor: pointer; font-weight: 400;">
                    <input type="checkbox" name="terms" required style="width: 16px; height: 16px; accent-color: var(--primary); margin-top: 2px;">
                    <span style="line-height: 1.4;">Saya menyetujui <a href="#" style="color: var(--primary); font-weight: 600; text-decoration: none;">Syarat Layanan</a> dan <a href="#" style="color: var(--primary); font-weight: 600; text-decoration: none;">Kebijakan Privasi</a>.</span>
                </label>
            </div>

            <!-- Submit -->
            <button type="submit" class="btn-submit" id="register-btn">
                Daftar Sekarang
            </button>
        </form>

        <!-- Divider -->
        <div class="auth-divider">
            <span style="text-transform: uppercase;">Atau</span>
        </div>

        <!-- Footer -->
        <div class="auth-footer">
            Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
        </div>
    </div>
</div>

<script>
    function togglePasswordVisibility(inputId, btn) {
        const input = document.getElementById(inputId);
        const icon = btn.querySelector('.iconify');
        if (input.type === 'password') {
            input.type = 'text';
            icon.setAttribute('data-icon', 'heroicons:eye-slash');
        } else {
            input.type = 'password';
            icon.setAttribute('data-icon', 'heroicons:eye');
        }
    }


</script>

</body>
</html>