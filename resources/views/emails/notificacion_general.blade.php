<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #333; line-height: 1.6; }
        .wrapper { background-color: #f4f4f4; padding: 20px; }
        .container { background-color: #ffffff; padding: 30px; border-radius: 8px; max-width: 600px; margin: 0 auto; border: 1px solid #e0e0e0; }
        .header { border-bottom: 2px solid #0056b3; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { color: #0056b3; font-size: 22px; margin: 0; }
        .content { font-size: 16px; white-space: pre-line; }
        .footer { margin-top: 30px; font-size: 12px; color: #777; border-top: 1px solid #eee; padding-top: 10px; }
        .badge { background: #e7f3ff; color: #0056b3; padding: 5px 10px; border-radius: 4px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <div class="header">
                <h1>{{ $titulo }}</h1>
            </div>
            
            <div class="content">
                {{ $mensaje }}
            </div>

            @if(!empty($datos))
                <div style="margin-top: 20px; background: #fafafa; padding: 15px; border-radius: 5px;">
                    <h3 style="font-size: 14px; margin-top: 0;">Resumen del servicio:</h3>
                    <p><strong>Localizador:</strong> <span class="badge">{{ $datos['localizador'] ?? 'N/A' }}</span></p>
                    <p><strong>Estado actual:</strong> {{ ucfirst($datos['estado'] ?? 'Pendiente') }}</p>
                </div>
            @endif

            <div class="footer">
                <p>Este es un mensaje automático de <strong>Enders Transfer Solutions</strong>.</p>
                <p>Si tienes alguna duda, por favor contacta con soporte.</p>
            </div>
        </div>
    </div>
</body>
</html>