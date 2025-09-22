<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 10px;
        }
        .pin-container {
            background-color: #f8fafc;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin: 30px 0;
        }
        .pin {
            font-size: 32px;
            font-weight: bold;
            color: #1f2937;
            letter-spacing: 8px;
            margin: 10px 0;
            font-family: 'Courier New', monospace;
        }
        .warning {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            font-size: 12px;
            color: #6b7280;
            text-align: center;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #2563eb;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">{{ $appName }}</div>
            <h1>Restablecer Contraseña</h1>
        </div>

        <p>Hola {{ $userName }},</p>
        
        <p>Recibiste este correo porque solicitaste restablecer la contraseña de tu cuenta en {{ $appName }}.</p>

        <div class="pin-container">
            <h2>Tu código de verificación es:</h2>
            <div class="pin">{{ $pin }}</div>
            <p style="margin-top: 15px; font-size: 14px; color: #6b7280;">
                Este código expira en <strong>{{ $expirationMinutes }} minutos</strong>
            </p>
        </div>

        <div class="warning">
            <strong>⚠️ Importante:</strong>
            <ul style="margin: 10px 0; padding-left: 20px;">
                <li>No compartas este código con nadie</li>
                <li>Solo úsalo si solicitaste restablecer tu contraseña</li>
                <li>Si no solicitaste esto, ignora este correo</li>
            </ul>
        </div>

        <p>Para completar el restablecimiento:</p>
        <ol>
            <li>Ve a la página de restablecimiento de contraseña</li>
            <li>Ingresa tu email: <strong>{{ $user->email }}</strong></li>
            <li>Ingresa el código: <strong>{{ $pin }}</strong></li>
            <li>Crea tu nueva contraseña</li>
        </ol>

        @if(config('app.frontend_url'))
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ config('app.frontend_url') }}/reset-password" class="button">
                Restablecer Contraseña
            </a>
        </div>
        @endif

        <div class="footer">
            <p>Este correo fue enviado automáticamente desde {{ $appName }}.</p>
            <p>Si tienes problemas, contacta a nuestro soporte.</p>
            <p>&copy; {{ date('Y') }} {{ $appName }}. Todos los derechos reservados.</p>
        </div>
    </div>
</body>
</html>