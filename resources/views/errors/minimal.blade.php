<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title')</title>
        <style>
            body { font-family: ui-sans-serif, system-ui, sans-serif; background: #111827; color: #f9fafb; margin: 0; padding: 0; }
            .container { min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 16px; }
            .error-box { text-align: center; max-width: 400px; }
            .error-code { font-size: 6rem; font-weight: bold; color: #ef4444; margin-bottom: 16px; }
            .error-message { font-size: 1.5rem; margin-bottom: 24px; }
            .back-link { color: #3b82f6; text-decoration: none; }
            .back-link:hover { text-decoration: underline; }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="error-box">
                <div class="error-code">@yield('code')</div>
                <div class="error-message">@yield('message')</div>
                <a href="{{ url('/') }}" class="back-link">← Retour à l'accueil</a>
            </div>
        </div>
    </body>
</html>
