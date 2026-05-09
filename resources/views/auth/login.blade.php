<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - MomSpire</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
        }
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .auth-logo {
            width: 92px;
            height: 92px;
            margin: 0 auto 16px;
            border-radius: 9999px;
            padding: 8px;
            overflow: hidden;
            border: 4px solid rgba(102, 126, 234, 0.14);
            background: #ffffff;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        }
        .auth-logo img {
            width: 100%;
            height: 100%;
            border-radius: 9999px;
            object-fit: cover;
            display: block;
        }
        .login-header h1 {
            color: #333;
            font-size: 28px;
            margin: 0 0 10px 0;
        }
        .login-header p {
            color: #666;
            margin: 0;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
        }
        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            box-sizing: border-box;
            transition: border-color 0.3s;
        }
        .form-group input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        .error-message {
            color: #e74c3c;
            font-size: 13px;
            margin-top: 5px;
        }
        .submit-btn {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s;
        }
        .submit-btn:hover {
            transform: translateY(-2px);
        }
        .submit-btn:active {
            transform: translateY(0);
        }
        .form-footer {
            text-align: center;
            margin-top: 20px;
        }
        .form-footer a {
            color: #667eea;
            text-decoration: none;
            font-size: 14px;
        }
        .form-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <div class="auth-logo">
                <img src="{{ asset('foto/logo.jpg') }}" alt="Logo MomSpire">
            </div>
            <h1>MomSpire</h1>
            <p>Masuk ke akun Anda</p>
        </div>

        @if ($errors->any())
            <div style="background: #f8d7da; color: #721c24; padding: 12px; border-radius: 5px; margin-bottom: 20px;">
                @foreach ($errors->all() as $error)
                    <p style="margin: 0;">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
                @error('password')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group" style="display:flex; justify-content:space-between; align-items:center;">
                <label style="display: flex; align-items: center; margin:0;">
                    <input type="checkbox" name="remember" style="width: auto; margin-right: 8px;">
                    <span>Ingat saya</span>
                </label>
                <button id="forgot-link" type="button" style="background:none;border:none;padding:0;color:#ff4d7a;text-decoration:underline;cursor:pointer;font-weight:600;">Lupa password?</button>
            </div>

            <button type="submit" class="submit-btn">Masuk</button>

            <div class="form-footer">
                <p>Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a></p>
            </div>
        </form>

        <!-- Hidden forgot-password form (toggled via JS) -->
        <div id="forgot-password-card" style="display:none; margin-top:18px;">
            <div style="background:#fff; padding:20px; border-radius:8px; box-shadow:0 8px 20px rgba(0,0,0,0.06);">
                <h2 style="margin:0 0 8px 0; font-size:20px; text-align:center;">Lupa Password</h2>
                <p style="text-align:center; color:#666; margin:0 0 12px 0;">Masukkan email Anda untuk menerima tautan reset.</p>

                @if(session('status'))
                    <div style="background:#e6fffa;color:#065f46;padding:10px;border-radius:6px;margin-bottom:12px">{{ session('status') }}</div>
                @endif

                @if ($errors->has('email'))
                    <div class="error-message">{{ $errors->first('email') }}</div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf
                    <div class="form-group" style="margin-bottom:12px;">
                        <label for="forgot_email">Email</label>
                        <input id="forgot_email" type="email" name="email" value="{{ old('email') }}" required style="width:100%; padding:10px; border:1px solid #e6e9ef; border-radius:6px;">
                    </div>
                    <div style="display:flex; gap:8px;">
                        <button type="submit" class="submit-btn" style="flex:1; padding:10px; border-radius:6px;">Kirim Tautan Reset</button>
                        <button type="button" id="cancel-forgot" style="flex:1; padding:10px; border-radius:6px; background:#f1f5f9; border:none;">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        (function(){
            const forgotLink = document.getElementById('forgot-link');
            const forgotCard = document.getElementById('forgot-password-card');
            const loginEmail = document.getElementById('email');
            const forgotEmail = document.getElementById('forgot_email');
            const cancelBtn = document.getElementById('cancel-forgot');

            console.log('[login] forgot-link:', !!forgotLink, 'forgot-card:', !!forgotCard);

            // bind immediately if element exists
            if (forgotLink) {
                forgotLink.addEventListener('click', function(e){
                    e && e.preventDefault && e.preventDefault();
                    console.log('[login] forgot-link clicked');
                    openForgot();
                });
            }

            if (cancelBtn) {
                cancelBtn.addEventListener('click', function(){
                    console.log('[login] cancel-forgot clicked');
                    closeForgot();
                });
            }

            // Event delegation: handle clicks on dynamically inserted forgot-link
            document.addEventListener('click', function(e){
                const btn = e.target.closest && e.target.closest('#forgot-link');
                if (btn) {
                    e.preventDefault();
                    console.log('[login][delegated] forgot-link clicked');
                    openForgot();
                }

                const cancel = e.target.closest && e.target.closest('#cancel-forgot');
                if (cancel) {
                    console.log('[login][delegated] cancel-forgot clicked');
                    closeForgot();
                }
            });

            // If there are validation errors for email or a status message, show the forgot card by default
            @if($errors->has('email') || session('status'))
                openForgot();
            @endif
            function openForgot() {
                if (loginEmail && forgotEmail && loginEmail.value) {
                    forgotEmail.value = loginEmail.value;
                }
                forgotCard.style.display = 'block';
                forgotCard.scrollIntoView({behavior: 'smooth'});
            }
            function closeForgot() {
                forgotCard.style.display = 'none';
            }
        })();
    </script>
</body>
</html>
