<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <title>{{ __('Verificación de Correo') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            margin: 0;
            padding: 0;
            background: #f0f4f8;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        }
        .email-wrapper {
            max-width: 600px;
            margin: 40px auto;
            background: white;
            border-radius: 8px;
            padding: 32px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            color: #1a202c;
            margin-bottom: 24px;
        }
        .content {
            font-size: 16px;
            color: #4a5568;
            line-height: 1.6;
        }
        .button {
            display: inline-block;
            margin: 24px 0;
            padding: 12px 24px;
            background-color: #1a202c;
            color: white !important;
            border-radius: 4px;
            text-decoration: none;
            font-weight: bold;
        }
        .break-url {
            word-break: break-all;
            display: inline-block;
            color: #3182ce;
        }
        .footer {
            font-size: 13px;
            color: #718096;
            margin-top: 40px;
            text-align: center;
        }
        @media only screen and (max-width: 600px) {
            .email-wrapper {
                padding: 20px;
                margin: 20px;
            }
            .footer {
                padding: 10px 0 !important;
                display: block !important;
                width: 100% !important;
            }
        }
    </style>
</head>
<body>
<div class="email-wrapper">
    <div class="header">{{ config('app.name') }}</div>

    <div class="content">
        <h2>{{ __('hello') }}</h2>

        <p>{{ __('verify_text_1') }}</p>

        <p style="text-align: center;">
            <a href="{{ $actionUrl }}" class="button">
                {{ __('verify_link') }}
            </a>
        </p>

        <p>{{ __('verify_text_2') }}</p>

        <p>{{ __('regards') }}, {{ config('app.name') }}</p>

        <hr>

        <p>
            {{ __('verify_text_3') }}
            <br>
            <a href="{{ $actionUrl }}" class="break-url">
                {{ $actionUrl }}
            </a>
        </p>
    </div>

    <div class="footer">
        &copy; {{ now()->year }} {{ config('app.name') }}. {{ __('all_rights') }}
    </div>
</div>
</body>
</html>
