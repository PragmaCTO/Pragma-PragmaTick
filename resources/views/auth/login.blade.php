<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In - PragmaTick</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-page: #081014;
            --bg-surface: #101c24;
            --bg-surface-elevated: #162430;
            --text-main: #f1f5f9;
            --text-muted: #94a3b8;
            --border-color: #1e3240;
            --primary: #06b6d4;
            --primary-hover: #0891b2;
            --primary-light: rgba(6, 182, 212, 0.15);
            --accent-rose: #f43f5e;
            --shadow-lg: 0 12px 28px rgba(0, 0, 0, 0.5);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: var(--bg-page);
            color: var(--text-main);
            font-family: 'Instrument Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            font-size: 15px;
            display: flex;
            min-height: 100vh;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }

        .login-card {
            background: var(--bg-surface);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            width: 100%;
            max-width: 420px;
            padding: 2.5rem 2rem;
            box-shadow: var(--shadow-lg);
        }

        .login-brand {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-brand h1 {
            font-size: 2rem;
            font-weight: 800;
            letter-spacing: -0.03em;
        }

        .login-brand p {
            color: var(--text-muted);
            font-size: 0.88rem;
            margin-top: 0.35rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-group label {
            display: block;
            font-size: 0.85rem;
            font-weight: 700;
            margin-bottom: 0.4rem;
            color: var(--text-main);
        }

        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            background: var(--bg-surface-elevated);
            color: var(--text-main);
            font-family: inherit;
            font-size: 0.92rem;
            outline: none;
            transition: border-color 0.15s ease;
        }

        .form-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 2px var(--primary-light);
        }

        .btn-submit {
            width: 100%;
            padding: 0.8rem 1.25rem;
            border-radius: 8px;
            background: var(--primary);
            color: #ffffff;
            font-size: 0.95rem;
            font-weight: 700;
            border: none;
            cursor: pointer;
            transition: background 0.15s ease;
            margin-top: 0.5rem;
        }

        .btn-submit:hover {
            background: var(--primary-hover);
        }

        .alert-error {
            background: rgba(244, 63, 94, 0.12);
            border: 1px solid var(--accent-rose);
            color: var(--accent-rose);
            padding: 0.75rem 1rem;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 1.25rem;
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="login-brand">
        <div style="display: flex; align-items: center; justify-content: center; gap: 0.75rem; margin-bottom: 0.5rem;">
            <img src="{{ asset('icon.png') }}" alt="PragmaTick Logo" style="height: 42px; width: auto; object-fit: contain;">
            <h1 style="margin: 0;"><span style="color: var(--primary);">Pragma</span>Tick</h1>
        </div>
        <p>Enter your credentials to access your workspace</p>
    </div>

    @if(session('success'))
        <div style="background: rgba(16, 185, 129, 0.12); border: 1px solid #10b981; color: #10b981; padding: 0.75rem 1rem; border-radius: 8px; font-size: 0.85rem; font-weight: 600; margin-bottom: 1.25rem;">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert-error">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('login') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" name="email" id="email" class="form-input" value="{{ old('email') }}" required autofocus placeholder="name@company.com">
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" class="form-input" required placeholder="••••••••">
        </div>

        <button type="submit" class="btn-submit">Log In to Workspace</button>
    </form>
</div>

</body>
</html>
