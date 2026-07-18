<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Smart-Hub Management</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --bg: #ffffff; --bg-secondary: #f7f7f8; --border: #e5e5e8;
            --text-primary: #1a1a1e; --text-secondary: #5e5e6e; --text-muted: #9898a8;
            --accent: #5e6ad2; --accent-hover: #4a55c0; --accent-light: #eef0fc;
            --danger: #dc2626; --danger-bg: #fef2f2; --radius: 8px;
        }
        body { font-family: 'Inter', sans-serif; background: var(--bg-secondary); color: var(--text-primary); min-height: 100vh; display: flex; align-items: center; justify-content: center; font-size: 14px; }

        .login-wrapper { width: 100%; max-width: 380px; padding: 24px; }

        .login-logo { text-align: center; margin-bottom: 32px; }
        .login-logo .icon { width: 44px; height: 44px; background: var(--accent); border-radius: 11px; display: flex; align-items: center; justify-content: center; margin: 0 auto 14px; }
        .login-logo h1 { font-size: 22px; font-weight: 700; letter-spacing: -0.5px; color: var(--text-primary); }
        .login-logo p { font-size: 13.5px; color: var(--text-muted); margin-top: 4px; }

        .login-card { background: var(--bg); border: 1px solid var(--border); border-radius: 12px; padding: 28px; box-shadow: 0 1px 3px rgba(0,0,0,.06), 0 4px 20px rgba(0,0,0,.04); }

        .form-group { margin-bottom: 16px; }
        .form-label { display: block; font-size: 13px; font-weight: 500; color: var(--text-primary); margin-bottom: 6px; }
        .form-control { width: 100%; padding: 9px 12px; border: 1px solid var(--border); border-radius: var(--radius); font-size: 13.5px; font-family: inherit; color: var(--text-primary); background: var(--bg); transition: border-color .15s, box-shadow .15s; outline: none; }
        .form-control:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(94,106,210,.12); }
        .form-control::placeholder { color: var(--text-muted); }
        .form-control.is-invalid { border-color: var(--danger); }
        .invalid-feedback { font-size: 12px; color: var(--danger); margin-top: 4px; }

        .remember-row { display: flex; align-items: center; gap: 8px; margin-bottom: 20px; }
        .remember-row input[type=checkbox] { width: 15px; height: 15px; accent-color: var(--accent); cursor: pointer; }
        .remember-row label { font-size: 13px; color: var(--text-secondary); cursor: pointer; }

        .btn-login { width: 100%; padding: 10px; background: var(--accent); color: white; border: none; border-radius: var(--radius); font-size: 14px; font-weight: 500; font-family: inherit; cursor: pointer; transition: background .15s; letter-spacing: -0.1px; }
        .btn-login:hover { background: var(--accent-hover); }

        .password-wrapper { position: relative; }
        .password-toggle { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; color: var(--text-muted); cursor: pointer; display: flex; align-items: center; justify-content: center; padding: 0; }
        .password-toggle:hover { color: var(--text-primary); }
        .form-control.has-icon-right { padding-right: 36px; }

        .alert { padding: 10px 14px; border-radius: var(--radius); font-size: 13px; margin-bottom: 18px; background: var(--danger-bg); border: 1px solid #fecaca; color: var(--danger); }

        .login-footer { text-align: center; margin-top: 20px; font-size: 12.5px; color: var(--text-muted); }
        .login-footer span { color: var(--accent); }

        .divider { height: 1px; background: var(--border); margin: 20px 0; position: relative; }
        .divider::after { content: 'Smart-Hub Portal'; position: absolute; top: 50%; left: 50%; transform: translate(-50%,-50%); background: var(--bg); padding: 0 10px; font-size: 11px; color: var(--text-muted); white-space: nowrap; }
    </style>
</head>
<body>
<div class="login-wrapper">
    <div class="login-logo">
        <div class="icon">
            <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/>
            </svg>
        </div>
        <h1>Smart-Hub</h1>
        <p>Masuk ke portal layanan</p>
    </div>

    <div class="login-card">
        <div class="divider"></div>

        @if ($errors->any() && !$errors->has('email') && !$errors->has('password'))
            <div class="alert">Email atau password salah. Silakan coba lagi.</div>
        @elseif ($errors->has('email') && !str_contains($errors->first('email'), 'member'))
            <div class="alert">{{ $errors->first('email') }}</div>
        @elseif ($errors->has('email') && str_contains($errors->first('email'), 'member'))
            <div class="alert" style="background:#fef3c7;border-color:#fde68a;color:#92400e;">
                {{ $errors->first('email') }}
            </div>
        @endif

        @if (session('status'))
            <div class="alert" style="background:#f0fdf4;border-color:#bbf7d0;color:#16a34a;">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label class="form-label" for="email">Email</label>
                <input id="email" type="email" name="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" value="{{ old('email') }}" placeholder="admin@smarthub.com" required autofocus>
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <div class="password-wrapper">
                    <input id="password" type="password" name="password" class="form-control has-icon-right {{ $errors->has('password') ? 'is-invalid' : '' }}" placeholder="••••••••" required>
                    <button type="button" class="password-toggle" id="togglePassword" aria-label="Toggle password visibility">
                        <svg id="eye-icon" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <svg id="eye-off-icon" style="display:none;" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                        </svg>
                    </button>
                </div>
                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="remember-row">
                <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                <label for="remember">Ingat saya selama 30 hari</label>
            </div>

            <button type="submit" class="btn-login">Masuk ke Dashboard</button>
        </form>
    </div>

    <div class="login-footer">
        Smart-Hub Management System &copy; {{ date('Y') }}
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eye-icon');
        const eyeOffIcon = document.getElementById('eye-off-icon');

        togglePassword.addEventListener('click', function () {
            // Toggle the type attribute
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            // Toggle the icon
            if (type === 'text') {
                eyeIcon.style.display = 'none';
                eyeOffIcon.style.display = 'block';
            } else {
                eyeIcon.style.display = 'block';
                eyeOffIcon.style.display = 'none';
            }
        });
    });
</script>
</body>
</html>
