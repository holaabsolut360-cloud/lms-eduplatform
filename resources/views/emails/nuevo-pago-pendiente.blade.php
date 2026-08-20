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
<h2 style="color:#1a1c2e;margin:0 0 12px;">Nuevo comprobante por revisar</h2>
<p style="color:#4a4d68;font-size:14px;line-height:1.6;">
<strong>{{ $orden->estudiante->nombre }}</strong> subió un comprobante de pago para el curso
<strong>{{ $orden->curso->titulo }}</strong>.
</p>
<table width="100%" cellpadding="6" cellspacing="0" style="background:#f8f8fb;border-radius:8px;font-size:13px;color:#4a4d68;margin:16px 0;">
<tr><td><strong>Código</strong></td><td>{{ $orden->codigo }}</td></tr>
<tr><td><strong>Monto</strong></td><td>{{ $orden->moneda }} {{ number_format($orden->monto, 2) }}</td></tr>
<tr><td><strong>Método</strong></td><td>{{ $orden->metodoPago->nombre ?? '—' }}</td></tr>
</table>
<p style="text-align:center;margin:28px 0;">
<a href="{{ route('admin.pagos.index') }}" style="background:#6c5ce7;color:#fff;text-decoration:none;padding:12px 28px;border-radius:24px;font-size:14px;font-weight:bold;display:inline-block;">Revisar pago</a>
</p>
</td></tr>
</table>
</td></tr>
</table>
</body>
</html>
