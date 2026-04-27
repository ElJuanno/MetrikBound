<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $survey->title ?? 'Encuesta' }} - MetrikBound</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body style="
    margin: 0;
    padding: 0;
    min-height: 100vh;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
">
    <div style="
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 20px;
    ">
        <div style="
            width: 100%;
            max-width: 900px;
            background: white;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0,0,0,.3);
            overflow: hidden;
        ">
            <!-- Header -->
            <div style="
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                padding: 40px;
                text-align: center;
                color: white;
            ">
                <div style="
                    width: 80px;
                    height: 80px;
                    background: rgba(255,255,255,.2);
                    border-radius: 20px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    margin: 0 auto 20px;
                    font-size: 40px;
                ">
                    📋
                </div>
                <h1 style="
                    font-size: 32px;
                    font-weight: 900;
                    margin: 0 0 12px 0;
                    line-height: 1.2;
                ">
                    {{ $survey->title ?? 'Encuesta' }}
                </h1>
                @if(!empty($survey->description))
                    <p style="
                        font-size: 16px;
                        opacity: 0.95;
                        margin: 0;
                        line-height: 1.6;
                    ">
                        {{ $survey->description }}
                    </p>
                @endif
            </div>

            <!-- Content -->
            <div style="padding: 40px;">
                @yield('content')
            </div>

            <!-- Footer -->
            <div style="
                padding: 24px 40px;
                background: #f8fafc;
                border-top: 1px solid #e2e8f0;
                text-align: center;
                color: #64748b;
                font-size: 14px;
            ">
                Powered by <strong style="color: #667eea;">MetrikBound</strong>
            </div>
        </div>
    </div>
</body>
</html>
