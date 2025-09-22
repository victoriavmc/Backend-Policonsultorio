{{ $appName }} - Restablecer Contraseña

Hola {{ $userName }},

Recibiste este correo porque solicitaste restablecer la contraseña de tu cuenta en {{ $appName }}.

TU CÓDIGO DE VERIFICACIÓN:
========================
{{ $pin }}
========================

Este código expira en {{ $expirationMinutes }} minutos.

IMPORTANTE:
- No compartas este código con nadie
- Solo úsalo si solicitaste restablecer tu contraseña  
- Si no solicitaste esto, ignora este correo

PASOS PARA RESTABLECER:
1. Ve a la página de restablecimiento de contraseña
2. Ingresa tu email: {{ $user->email }}
3. Ingresa el código: {{ $pin }}
4. Crea tu nueva contraseña

@if(config('app.frontend_url'))
Enlace directo: {{ config('app.frontend_url') }}/reset-password
@endif

---
Este correo fue enviado automáticamente desde {{ $appName }}.
Si tienes problemas, contacta a nuestro soporte.

© {{ date('Y') }} {{ $appName }}. Todos los derechos reservados.