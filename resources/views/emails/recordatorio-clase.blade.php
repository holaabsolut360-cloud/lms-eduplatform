<!DOCTYPE html>
<html>
<body style="margin:0;padding:0;background:#f5f6fa;font-family:Arial,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="padding:30px 0;">
<tr><td align="center">
<table width="480" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:16px;overflow:hidden;">
<tr><td style="background:#6c5ce7;padding:24px;text-align:center;">
<span style="color:#fff;font-size:18px;font-weight:bold;">{{ config('app.name') }}</span>
</td></tr>
<tr><td style="padding:32px;">
<h2 style="color:#1a1c2e;margin:0 0 12px;">¡Hola, {{ $estudiante->nombre }}!</h2>
<p style="color:#4a4d68;font-size:14px;line-height:1.6;">
Tu clase en vivo <strong>{{ $clase->titulo }}</strong> del curso <strong>{{ $clase->curso->titulo }}</strong>
empieza a las <strong>{{ $clase->fecha_hora->format('h:i A') }}</strong> de hoy.
</p>
<p style="color:#4a4d68;font-size:14px;line-height:1.6;">
Plataforma: {{ $clase->plataforma }}
</p>
<p style="text-align:center;margin:28px 0;">
<a href="{{ $clase->link_reunion }}" style="background:#6c5ce7;color:#fff;text-decoration:none;padding:12px 28px;border-radius:24px;font-size:14px;font-weight:bold;display:inline-block;">Unirme a la clase</a>
</p>
<p style="color:#8a8fa8;font-size:12px;">Guarda este correo para acceder al enlace cuando sea la hora.</p>
</td></tr>
</table>
</td></tr>
</table>
</body>
</html>
