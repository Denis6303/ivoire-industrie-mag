<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Confirmation newsletter</title>
</head>
<body style="margin:0;padding:0;background:#f8f9fa;font-family:Arial,Helvetica,sans-serif;">
    <div style="max-width:600px;margin:0 auto;padding:24px;">
        <div style="background:#243e5d;color:#fff;padding:16px;border-radius:8px 8px 0 0;">
            <div style="font-size:18px;font-weight:700;">Ivoire Industrie Magazine</div>
        </div>
        <div style="background:#fff;padding:20px;border-radius:0 0 8px 8px;">
            <h2 style="margin:0 0 14px;font-size:20px;">Confirmez votre inscription</h2>
            <p style="margin:0 0 16px;color:#333;line-height:1.5;font-size:14px;">
                Merci pour votre inscription. Cliquez sur le bouton ci-dessous pour activer votre abonnement.
            </p>
            <p style="margin:0 0 18px;">
                <a href="{{ $confirmUrl }}" style="display:inline-block;padding:10px 16px;background:#0d6efd;color:#fff;text-decoration:none;border-radius:6px;font-weight:700;">
                    Confirmer mon abonnement
                </a>
            </p>
            <p style="margin:0;color:#777;font-size:12px;line-height:1.5;">
                Si vous n'êtes pas à l'origine de cette demande, vous pouvez ignorer cet email
                ou vous désinscrire ici :
                <a href="{{ $unsubscribeUrl }}" style="color:#0d6efd;text-decoration:underline;">se désinscrire</a>.
            </p>
        </div>
    </div>
</body>
</html>
